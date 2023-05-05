<?php

namespace App\Http\Controllers;

use App\Conversations\HighscoreConversation;
use App\Conversations\MaterialConversation;
use App\Conversations\PrivacyConversation;
use App\Conversations\QuizConversation;
use App\Conversations\ScheduleConversation;
use App\Conversations\TaskConversation;
use App\Http\Responses\RedirectResponse;
use App\Models\TelegramUsers;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Cache\LaravelCache;
use BotMan\BotMan\Drivers\DriverManager;
use App\Http\Middleware\PreventDoubleClicks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Incoming\Answer;

class BotManController extends Controller
{
    public function messageIndex()
    {
        return view('sms.sms');
    }


    public function handle()
    {
        DriverManager::loadDriver(\BotMan\Drivers\Telegram\TelegramDriver::class);

        $config = [
            'user_cache_time' => 720,

            'config' => [
                'conversation_cache_time' => 720,
            ],

            "telegram" => [
                "token" => env('TELEGRAM_TOKEN'),
            ]
        ];

        $botman = BotManFactory::create($config, new LaravelCache());

        $botman->middleware->captured(new PreventDoubleClicks);

        $botman->hears('hello', function (BotMan $bot) {

        });

        $botman->hears('start|/start', function (BotMan $bot) {
            $u = TelegramUsers::where('telegram_chat_id', $bot->getUser()->getId())->first();
            if (empty($u)) {
                $user = TelegramUsers::create([
                    'username' => $bot->getUser()->getUsername(),
                    'telegram_chat_id' => $bot->getUser()->getId()
                ]);
            }
            $bot->reply('Добро пожаловать  ' . $bot->getUser()->getUsername());
        })->stopsConversation();

        $botman->hears('quiz|/quiz', function (BotMan $bot) {
            $bot->startConversation(new QuizConversation());
        })->stopsConversation();

        $botman->hears('/highscore|highscore', function (BotMan $bot) {
            $bot->startConversation(new HighscoreConversation());
        })->stopsConversation();

        $botman->hears('/schedule|schedule', function (BotMan $bot) {
            $bot->startConversation(new ScheduleConversation());
        })->stopsConversation();

        $botman->hears('/task|task', function (BotMan $bot) {
            $bot->startConversation(new TaskConversation());
        })->stopsConversation();

        $botman->hears('/about|about', function (BotMan $bot) {
            $bot->reply('Это бот помощник предподователя');
        })->stopsConversation();

        $botman->hears('/material|material', function (BotMan $bot) {
            $bot->startConversation(new MaterialConversation());
        })->stopsConversation();

        $botman->listen();
    }
}
