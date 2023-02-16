<?php

namespace App\Services\MyElastic\ElasticSearchBuilder\Interfce;

interface ElasticBuilderInterface
{
    public function SetIndex(string $index): ElasticBuilderInterface;

    public function SetQuery(int $size = 10): ElasticBuilderInterface;

    public function setSize(int $size = 10): ElasticBuilderInterface;

    public function SetMultiMatch(): ElasticBuilderInterface;

    public function setAnalyzer(string $analyzer): ElasticBuilderInterface;

    public function SetFuzziness(int $level): ElasticBuilderInterface;

    public function setFields(array $fields): ElasticBuilderInterface;

    public function SetSearchValue(string $value): ElasticBuilderInterface;

    public function setJsonEncode(): ElasticBuilderInterface;

    public function setMatch(array $data): ElasticBuilderInterface;

    public function setMatchWithFuzziness(array $data, int $level = 1): ElasticBuilderInterface;

    public function getQuery(): array;
}
