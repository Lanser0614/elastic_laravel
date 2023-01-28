<?php

namespace App\Services\MyElastic\ElasticSearchBuilder\Interfce;

interface ElasticBuilderInterface
{
    public function SetIndex(string $index);

    public function SetQuery(int $size = 10);

    public function setSize(int $size);

    public function SetMultiMatch();

    public function setAnalyzer(string $analyzer);

    public function SetFuzziness(int $level);

    public function setFields(array $fields);

    public function SetSearchValue(string $value);

    public function setJsonEncode();

    public function setMatch(array $data);

    public function setMatchWithFuzziness(array $data, int $level = 1);

    public function getQuery(): array;
}
