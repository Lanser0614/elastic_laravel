<?php

namespace App\Services\MyElastic\ElasticEloquent\Interface\ElasticEloquentInterface;

use Illuminate\Pagination\LengthAwarePaginator;

interface ElasticEloquentInterface
{
    public function paginate( int $perPage = 1, int $page = 1): LengthAwarePaginator;

    public function find(int $id);

    public function getTook();

    public function getShards();

    public function getTotal();

    public function getItems();

    public function getCollection();
}
