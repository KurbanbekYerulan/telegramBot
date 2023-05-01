<?php

namespace App\Http\Controllers;

use App\Http\Responses\RedirectResponse;
use App\Models\TelegramUsers;
use App\Notifications\SendNotification;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Cache\LaravelCache;
use BotMan\BotMan\Drivers\DriverManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use NotificationChannels\Telegram\Telegram;
use Spatie\FlareClient\Api;
use BotMan\BotMan\BotMan;

class MessageController extends Controller
{
    public function messageSend(Request $request)
    {
        $message = $request->input('message');
        $users = TelegramUsers::all();

        foreach ($users as $user) {
           // $botman->say($message, $user->telegram_chat_id);
            $user->notify(new SendNotification($message));

        }

        return new RedirectResponse(route('message.index'), ['flash_success' => 'Успешно отправлено']);
    }

}
