<?php

namespace App\MyElastic;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\AuthenticationException;

class MyElasticConnect
{
    private Client $client;

    /**
     * @throws AuthenticationException
     */
    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts(['http://localhost:9200'])
            ->setBasicAuthentication('elastic', 'changeme')
            ->build();
    }

    public function getElasticClient(): Client
    {
        return $this->client;
    }
}
