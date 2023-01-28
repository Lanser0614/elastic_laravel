<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public const ELASTICSEARCH_INDEX = 'posts';


    public static function getIndexSettings(): array
    {
        return [
            'index' => self::ELASTICSEARCH_INDEX,
            'body' => [
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
            ]
        ];
    }
}
