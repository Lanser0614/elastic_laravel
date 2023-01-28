<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Services\MyElastic\ElasticConnect\MyElasticConnect;
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
    public function handle(): void
    {
        $client = app(MyElasticConnect::class);

        // Create a new index with settings
//        $response = $client->indices()->create(Post::getIndexSettings());


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
}
