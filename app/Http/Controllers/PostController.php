<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\MyElastic\ElasticConnect\MyElasticConnect;
use App\Services\MyElastic\ElasticEloquent\Interface\ElasticEloquent;
use App\Services\MyElastic\ElasticSearchBuilder\ElasticBuilder;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use function response;

class PostController extends Controller
{
    /**
     * @throws Exception
     */
    public function elkaBuilder(ElasticBuilder $elkaBuilder, Request $request)
    {
        $client = app(MyElasticConnect::class);

        $elkaBuilder
            ->SetIndex(Post::ELASTICSEARCH_INDEX)
            ->SetQuery(1000)
            ->SetMultiMatch()
            ->setAnalyzer("my_analyzer")
            ->setFields([
                "content",
                "title"
            ])
            ->SetSearchValue("sa")
            ->SetFuzziness(2)
            ->setJsonEncode()
            ->getQuery();

        $results = $client->search($elkaBuilder->query)->asObject();

        $elasticEloquent = new ElasticEloquent($results);

        return $elasticEloquent->paginate((int)$request->perPage, (int)$request->page);


    }

    public function search(Request $request)
    {
        $client = app(MyElasticConnect::class);

        $query = [
            "query" => [
                "multi_match" => [
                    "query" => "ханк",
                    "analyzer" => "my_analyzer",
                    "fields" => [
                        "content",
                        "title"
                    ],
                    "fuzziness" => 2
                ]
            ]
        ];

        $params = [
            'index' => Post::ELASTICSEARCH_INDEX,
            'body' => json_encode($query)
        ];

        $results = $client->search($params)->asArray();
        $answer = [];
        foreach ($results['hits']['hits'] as $result) {
            $answer[] =
            $result['_source'] += ["id" => (int)$result['_id']];
        }

        $paginator = $this->paginate($answer, $request->per_page ?? null, $request->page ?? null);

        return response()->json($paginator);
    }

    private function paginate($items, $perPage, $page)
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
