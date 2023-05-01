<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class TelegramUsers extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'telegram_chat_id',
        'username'
    ];

    public function routeNotificationForTelegram()
    {
        return $this->telegram_chat_id;
    }
}
