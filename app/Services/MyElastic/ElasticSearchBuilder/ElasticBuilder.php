<?php

namespace App\Services\MyElastic\ElasticSearchBuilder;


use App\Services\MyElastic\ElasticConnect\MyElasticConnect;
use App\Services\MyElastic\ElasticSearchBuilder\Interfce\ElasticBuilderInterface;
use Elastic\Elasticsearch\Response\Elasticsearch;
use Exception;
use Http\Promise\Promise;

class ElasticBuilder implements ElasticBuilderInterface
{

    public array $query;

    protected function reset(): void
    {
        $this->query = [];
    }

    public function SetIndex(string $index): ElasticBuilderInterface
    {
        $this->reset();
        $this->query = [
            "index" => $index,
            "body" => []
        ];
        return $this;
    }

    /**
     * @throws Exception
     */
    public function SetQuery(int $size = 10): ElasticBuilderInterface
    {
        if (empty($this->query)) {
            throw new Exception("Set index for query");
        }

        $this->query["body"] += [
            "size" => $size,
            "query" => [

            ]
        ];

        return $this;
    }

    public function setSize(int $size = 10): ElasticBuilderInterface
    {
        $this->query["body"] += [
            "size" => $size,
        ];

        return $this;
    }

    public function setAggs(string $field): ElasticBuilderInterface
    {
        $this->query["body"] += [
            "aggs" => [
              "genres" => [
                  "terms" => [
                      "field" => $field
                  ]
              ]
            ]
        ];

        return $this;
    }

    public function SetMultiMatch(): ElasticBuilderInterface
    {
        $this->query["body"]["query"] += [
            "multi_match" => [

            ]
        ];

        return $this;
    }

    public function setAnalyzer(string $analyzer): ElasticBuilderInterface
    {
        $this->query["body"]["query"]["multi_match"] += [
            "analyzer" => $analyzer,
        ];

        return  $this;
    }

    public function SetFuzziness(int $level): ElasticBuilderInterface
    {
        $this->query["body"]["query"]["multi_match"] += [
            "fuzziness" => $level,
        ];

        return  $this;
    }

    public function setFields(array $fields): ElasticBuilderInterface
    {
        $this->query["body"]["query"]["multi_match"] += [
            "fields" => $fields,
        ];

        return  $this;
    }

    public function SetSearchValue(string $value): ElasticBuilderInterface
    {
        $this->query["body"]["query"]["multi_match"] += [
            "query" => $value,
        ];

        return  $this;
    }

    public function setJsonEncode(): ElasticBuilderInterface
    {
        $this->query["body"] = json_encode($this->query['body']);
        return  $this;
    }

    public function getQuery(): array
    {
        return $this->query;
    }


    public function setMatch(array $data): ElasticBuilderInterface
    {
        $this->query["body"]["query"] += [
            "match" => [
                array_key_first($data) => $data[array_key_first($data)]
            ]
        ];

        return $this;
    }

    public function setMatchWithFuzziness(array $data, int $level = 1): ElasticBuilderInterface
    {
        $this->query["body"]["query"] += [
            "match" => [
                array_key_first($data) => [
                    "query" => $data[array_key_first($data)],
                    "fuzziness" => $level
                ]
            ]
        ];

        return $this;
    }

}
