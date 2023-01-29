<?php

namespace App\Services\MyElastic\ElasticSearchBuilder;


use App\Services\MyElastic\ElasticSearchBuilder\Interfce\ElasticBuilderInterface;
use Exception;

class ElasticBuilder implements ElasticBuilderInterface
{

    public array $query;

    protected function reset(): void
    {
        $this->query = [];
    }

    public function SetIndex(string $index)
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
    public function SetQuery(int $size = 10)
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

    public function setSize(int $size = 10)
    {
        $this->query["body"] += [
            "size" => $size,
        ];

        return $this;
    }

    public function setAggs(string $field)
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

    public function SetMultiMatch()
    {
        $this->query["body"]["query"] += [
            "multi_match" => [

            ]
        ];

        return $this;
    }

    public function setAnalyzer(string $analyzer)
    {
        $this->query["body"]["query"]["multi_match"] += [
            "analyzer" => $analyzer,
        ];

        return  $this;
    }

    public function SetFuzziness(int $level)
    {
        $this->query["body"]["query"]["multi_match"] += [
            "fuzziness" => $level,
        ];

        return  $this;
    }

    public function setFields(array $fields)
    {
        $this->query["body"]["query"]["multi_match"] += [
            "fields" => $fields,
        ];

        return  $this;
    }

    public function SetSearchValue(string $value)
    {
        $this->query["body"]["query"]["multi_match"] += [
            "query" => $value,
        ];

        return  $this;
    }

    public function setJsonEncode()
    {
        $this->query["body"] = json_encode($this->query['body']);
        return  $this;
    }

    public function getQuery(): array
    {
        return $this->query;
    }


    public function setMatch(array $data)
    {
        $this->query["body"]["query"] += [
            "match" => [
                array_key_first($data) => $data[array_key_first($data)]
            ]
        ];

        return $this;
    }

    public function setMatchWithFuzziness(array $data, int $level = 1)
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
