<?php

namespace App\Conversations;

use App\Models\Highscore;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer as BotManAnswer;
use BotMan\BotMan\Messages\Outgoing\Question as BotManQuestion;

class HighscoreConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->showHighscore();
    }

    private function showHighscore()
    {
        $topUsers = Highscore::topUsers();

        if (!$topUsers->count()) {
            return $this->say('Рекорд по-прежнему пуст. Будь первым! 👍');
        }

        $topUsers->transform(function ($user) {
            return "_{$user->rank} - {$user->name}_ *{$user->points} points*";
        });

        $this->say('Вот текущий рекорд, показывающий 5 лучших результатов.');
        $this->bot->typesAndWaits(1);
        $this->say('🏆 РЕКОРД 🏆');
        $this->bot->typesAndWaits(1);
        $this->say($topUsers->implode("\n"), ['parse_mode' => 'Markdown']);
        $this->bot->typesAndWaits(2);
        $this->say("Если вы хотите пройти тест еще один раз, нажмите: /quiz \n");
    }

}
