<?php
/**
 * Created by PhpStorm.
 * User: macbook
 * Date: 12/4/17
 * Time: 10:00 AM
 */
namespace App\Repositories;

use App\Repositories\Interfaces\RepositoryInterface;
use Elasticsearch\Client;
use App\Repositories\ESRepository;
use App\Repositories\Repository;
use Illuminate\Database\Eloquent\Collection;
use App\Plan;
use Illuminate\Database\Eloquent\Model;

class ESPlanRepository extends ESRepository implements RepositoryInterface
{
    private $search;

    private $model;

    private $actualTotal;

    public function __construct(Client $client) {

        parent::__construct($client);
        $this->search = $client;
        $this->model  = new Plan();
    }

    public function search($query = "",$category = null, $lat = null, $lng = null, $distance = '80.5km', $from = 0, $maxResults = 25)
    {
        $items = $query ? $this->searchOnElasticsearch($query, $category, $lat, $lng, $distance, $from ) : $this->getAllItems();

        return $this->buildCollection($items);
    }

    private function searchOnElasticsearch($query, $category, $lat, $lng, $distance, $from = 0, $maxResults = 25)
    {
        $instance = $this->model;
        $shouldArray = [
            [
                'match' => [
                    'stripe_plan_name'  => $query,
                ]
            ],
            [
                'match' => [
                    'description'       => $query
                ]
            ],
            [
                'match' => [
                    'name'       => $query
                ]
            ],
            [
                'wildcard' => [
                    'business_handle'   => "*$query*"
                ]
            ]
        ];
        $mustArray = [
            [
                'exists' => [
                    'field' => 'featured_photo_path'
                ],
            ]
        ];

        if($category) {
            $mustArray[] = [
                'match' => [
                    'category' => $category
                ]
            ];
        }

        if(abs($lat + $lng) > 0) {
            $filter = [
                'geo_distance' => [
                    'distance' => $distance,
                    'location' => [
                        'lat' => $lat,
                        'lon' => $lng
                    ]
                ]
            ];
        } else {
            $filter = [];
        }

        $items = $this->search->search([
            'index' => $instance->getSearchIndex(),
            'type' => $instance->getSearchType(),
            'body' => [
                "from" => !$from ? 0 : $from,
                "size" => $maxResults,
                'query' => [
                    'bool' => [
                        'should' => [
                            $shouldArray
                        ],
                        'must' => [
                            $mustArray
                        ],
                        "minimum_should_match" => 1,
                        "filter" => $filter
                    ]
                ],
            ],
        ]);

        return $items;
    }

    private function getAllItems()
    {
        $instance = $this->model;

        $items = $this->search->search([
            'index' => $instance->getSearchIndex(),
            'type' => $instance->getSearchType(),
            'body' => [
                "from" => 0,
                "size" => 25,
                'query' => [
                    'bool' => [
                        'must' => [
                            'exists' => [
                                'field' => 'featured_photo_path'
                            ],
                        ]
                  ],
                ],
            ],
        ]);

        return $items;
    }

    private function buildCollection(array $items)
    {
        /**
         * The data comes in a structure like this:
         *
         * [
         *      'hits' => [
         *          'hits' => [
         *              [ '_source' => 1 ],
         *              [ '_source' => 2 ],
         *          ]
         *      ]
         * ]
         *
         * And we only care about the _source of the documents.
         */
        $hits = array_pluck($items['hits']['hits'], '_source') ?: [];

        $sources = array_map(function ($source) {
            // The hydrate method will try to decode this
            // field but ES gives us an array already.
            $source['description'] = json_encode($source['description']);
            return $source;
        }, $hits);

        $plans = $this->model;

        $results = [
            // We have to convert the results array into Eloquent Models.
            'plans' => $plans::hydrate($sources),
            'actualTotal' => $items['hits']['total']
        ];

        return $results;
    }
}

//'match' => [
//    'stripe_plan_name' => 'jawn',
//],