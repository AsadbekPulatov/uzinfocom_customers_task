<?php

namespace App\Services;

use Elastic\Elasticsearch\ClientBuilder;

class ElasticsearchService
{
    protected $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts(['laravel_es:9200'])
            ->build();
    }
    public function exists(array $params)
    {
        return $this->client->indices()->exists($params);
    }
    public function createIndexes(array $params)
    {
        return $this->client->indices()->create($params);
    }

    public function deleteIndexes(array $params)
    {
        return $this->client->indices()->delete($params);
    }

    public function index(array $params)
    {
        return $this->client->index($params);
    }

    public function bulk(array $params)
    {
        return $this->client->bulk($params);
    }

    public function get(array $params)
    {
        return $this->client->get($params);
    }

    public function update(array $params)
    {
        return $this->client->update($params);
    }

    public function delete(array $params){
        return $this->client->delete($params);
    }
    public function search(array $params)
    {
        return $this->client->search($params);
    }
}
