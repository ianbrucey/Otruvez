<?php

namespace App\Providers;

use App\Plan;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\PlanRepository;
use \App\Repositories\Repository;
use App\Repositories\ESRepository;
use App\SearchClient\ElasticsearchObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

//        Plan::observe(ElasticsearchObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton(RepositoryInterface::class, function($app) {
            // This is useful in case we want to turn-off our
            // search cluster or when deploying the search
            // to a live, running application at first.
            // Todo: make this work
            if (!config('services.search.enabled')) {
                return new Repository($app->make(Model::class));
            }

            return new ESRepository(
                $app->make(Client::class)
            );
        });

        $this->bindSearchClient();
    }

    private function bindSearchClient()
    {
        $this->app->bind(Client::class, function ($app) {
            return ClientBuilder::create()
                ->setHosts(config('services.search.hosts'))
                ->build();
        });
    }
}
