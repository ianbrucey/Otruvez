<?php

namespace App\Console\Commands;

use App\Business;
use App\Plan;
use App\Rating;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Console\Command;

class ReindexCommand extends Command
{
    protected $name = "search:reindex";
    protected $description = "Indexes all plans to elasticsearch";
    private $search;

    public function __construct(Client $search)
    {
        parent::__construct();

        $this->search = $search;
    }

    public function handle()
    {
        shell_exec("curl -XDELETE 'localhost:9200/plans?pretty'");
        $this->output->write("building index.... \n");
        $this->buildPlanIndex(); // builds schema
        $this->output->write("index built \n");


        $this->info('Indexing all plans. Might take a while...');
        $this->output->write(sprintf("there are %s plans", count(Plan::cursor())));
        foreach (Plan::cursor() as $model) {

            if($model->business) {
                $body = $model->toSearchArray();
                $location = ['lat' => $model->business->lat,'lon' => $model->business->lng];
                $body['location'] = $location;
                $body['rating'] = (new Rating())->where('plan_id', $model->id)->avg('rate_number') ?: 0;

                $this->search->index([
                    'index' => $model->getSearchIndex(),
                    'type' => $model->getSearchType(),
                    'id' => $model->id,
                    'body' => $body,
                ]);
            }
        }

        $this->info("\nDone!");
    }

    public function buildPlanIndex() {
        // structure == localhost:9200/{index}/{type}
        $client = ClientBuilder::create()->setHosts(config('services.search.hosts'))->build();
        $params = [
            'index' => 'plans',
            'body' => [
                'settings' => [
                    'number_of_shards' => 5,
                    'number_of_replicas' => 1
                ],
                'mappings' => [
                    'plans' => [ // type
                        '_source' => [
                            'enabled' => true
                        ],
                        'properties' => [
                            'stripe_plan_name' => [
                                'type' => 'text',
                                'analyzer' => 'english'
                            ],
                            'id' => [
                                'type' => 'integer',
                                'index' => 'false'
                            ],
                            'description' => [
                                'type' => 'text',
                                'analyzer' => 'english'
                            ],
                            "location" => [
                                "type" => "geo_point"
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return $client->indices()->create($params);
    }

    public function deleteIndex($id)
    {
        $client = ClientBuilder::create()->setHosts(config('services.search.hosts'))->build();
        $params = [
            'index' => 'plans',
            'type'  => 'plans',
            'id'    => '6'
        ];
        $client->delete($params);
    }



}