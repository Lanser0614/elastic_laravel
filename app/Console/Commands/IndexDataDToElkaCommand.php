<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\MyElastic\MyElasticConnect;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Illuminate\Console\Command;

/**
 *
 */
class IndexDataDToElkaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'index:elka';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index all data to elasticsearch';

    /**
     * Execute the console command.
     *
     * @throws AuthenticationException
     * @throws ClientResponseException
     * @throws ServerResponseException
     * @throws MissingParameterException
     */
    public function handle()
    {
        $client = app(MyElasticConnect::class);

        // Create new index with settings
        //        $params = [
//            'index' => 'posts',
//            'body' => $this->getIndexSettings()
//        ];

//        $response = $client->indices()->create($params);


        $posts = Post::query();

        $this->output->progressStart($posts->count());

        $posts->chunk(100, function ($rows) use ($client) {
            foreach ($rows as $row) {
                $this->output->progressAdvance();

                $params = [
                    'index' => Post::ELASTICSEARCH_INDEX,
                    'id' => $row->id,
                    'body' => [
                        'title' => $row->title,
                        'content' => $row->content,
                        'phone' => $row->phone,
                    ]
                ];

                // Document will be indexed to my_index/_doc/my_id
                $client->index($params);
            }
        });

        $this->output->progressFinish();

    }

    private function getIndexSettings(): array
    {
        return [
            "settings" => [
                "analysis" => [
                    "analyzer" => [
                        "my_analyzer" => [
                            "tokenizer" => "standard",
                            "char_filter" => [
                                "my_mappings_char_filter"
                            ]
                        ],
                        "regex_analyzer" => [
                            "tokenizer" => "standard",
                            "char_filter" => [
                                "my_regex_char_filter"
                            ]
                        ]
                    ],
                    "char_filter" => [
                        "my_mappings_char_filter" => [
                            "type" => "mapping",
                            "mappings" => [
                                "а => a",
                                "б => b",
                                "в => v",
                                "г => g",
                                "д => d",
                                "е => e",
                                "ё => yo",
                                "ж => j",
                                "з => z",
                                "и => i",
                                "й => y",
                                "к => k",
                                "л => l",
                                "м => m",
                                "н => n",
                                "о => o",
                                "п => p",
                                "р => r",
                                "с => s",
                                "т => t",
                                "у => u",
                                "ф => f",
                                "х => h",
                                "ц => c",
                                "ч => ch",
                                "ш => sh",
                                "щ => sch",
                                "ь => ''",
                                "ы => y",
                                "ъ => ''",
                                "э => e",
                                "ю => yu",
                                "я => ya",
                                "ў => o'",
                                "қ => q",
                                "ғ => g'",
                                "ҳ => x",
                                "А => A",
                                "Б => B",
                                "В => V",
                                "Г => G",
                                "Д => D",
                                "Е => E",
                                "Ё => Yo",
                                "Ж => J",
                                "З => Z",
                                "И => I",
                                "Й => Y",
                                "К => K",
                                "Л => L",
                                "М => M",
                                "Н => N",
                                "О => O",
                                "П => P",
                                "Р => R",
                                "С => S",
                                "Т => T",
                                "У => U",
                                "Ф => F",
                                "Х => H",
                                "Ц => C",
                                "Ч => Ch",
                                "Ш => Sh",
                                "Щ => Sch",
                                "Ь => ''",
                                "Ы => Y",
                                "Ъ => ''",
                                "Э => E",
                                "Ю => Yu",
                                "Я => Ya",
                                "Ў => O'",
                                "Қ => Q",
                                "Ғ => G'",
                                "Ҳ => X"
                            ]
                        ],
                        "my_regex_char_filter" => [
                            "type" => "pattern_replace",
                            "pattern" => "[- !@#$%^&+*)(]{0,20}",
                            "replacement" => ""
                        ]
                    ]
                ]
            ],
            "mappings" => [
                "properties" => [
                    "content" => [
                        "type" => "text",
                        "analyzer" => "my_analyzer",
                        "search_analyzer" => "standard"
                    ],
                    "title" => [
                        "type" => "text",
                        "analyzer" => "my_analyzer",
                        "search_analyzer" => "standard"
                    ],
                    "phone" => [
                        "type" => "text",
                        "analyzer" => "regex_analyzer"
                    ]
                ]
            ]
        ];


    }
}
