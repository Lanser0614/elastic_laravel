<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\MyElastic\ElasticConnect\MyElasticConnect;
use App\Services\MyElastic\ElasticEloquent\ElasticEloquent;
use App\Services\MyElastic\ElasticSearchBuilder\ElasticBuilder;
use Dflydev\DotAccessData\Data;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    /**
     * @throws Exception
     */
    public function index(ElasticBuilder $elkaBuilder)
    {
        $client = app(MyElasticConnect::class);

        $elkaBuilder
            ->SetIndex("abc-sql-log-route-new")
            ->setSize(1000)
            ->setAggs("context.REQUEST_TIME")
//            ->setMatchWithFuzziness(["title" => "Dr. Bernardo Leannon"], 1)
            ->setJsonEncode()
            ->getQuery();

        $results = $client->search($elkaBuilder->query)->asObject();

        $elasticEloquent = new ElasticEloquent($results);
        $items = $elasticEloquent->getCollection()->groupBy([['REQUEST_TIME'], function ($item) {
            $array = json_decode(json_encode($item), true);
            return
                $array['_source']['context']['REQUEST_TIME'] ?? 1;
        }], true)->toArray();

        krsort($items[""]);

        $data = [
            "items" => $items[""]
        ];
        return view('welcome', compact('data'));


//        dd($data);
//        return $data;

//        return $this->paginate(collect($items[""])->sort());
    }

    private function paginate($items, int $perPage = null, int $page = null)
    {
        if (is_null($perPage)) {
            $perPage = 5;
        }

        if (is_null($page)) {
            $page = 1;
        }
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
    }

}
