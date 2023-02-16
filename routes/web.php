<?php

use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $markup = [
        'inline_keyboard' => [
            [
                [
                    "text" => "bos",
                    "callback_data" => "Bosdin"
                ],
                [
                    "text" => "Google",
                    "url" => "https://google.com"
                ]
            ]
        ]
    ];
    $res = Http::get("https://api.telegram.org/bot5979846976:AAFh29LFEFfPUgs8MaBnHInM54_TKuiI-zQ/getWebhookInfo")
        ->json();
//   $res =  Http::attach('document', public_path('favicon.ico'), 'test.ico')
//       ->post("https://api.telegram.org/bot5979846976:AAFh29LFEFfPUgs8MaBnHInM54_TKuiI-zQ/sendMessage", [
//        'chat_id' => 717273923,
//        'text' => 'Knopka keldi',
//           "reply_markup"=> json_encode($markup)
//    ])->json();
   dd($res);
});
