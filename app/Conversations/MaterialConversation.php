<?php

namespace App\Conversations;

use App\Models\Groups;
use App\Models\Answer;
use App\Models\Material;
use App\Models\Played;
use App\Models\Question;
use App\Models\Highscore;
use App\Models\Schedule;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer as BotManAnswer;
use BotMan\BotMan\Messages\Outgoing\Question as BotManQuestion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MaterialConversation extends Conversation
{
    /** @var Groups */
    protected $quizGroups;

    public function run()
    {
        $this->quizGroups = Groups::all();
        $this->selectTrack();
    }

    private function selectTrack()
    {
        $this->say(
            "У нас есть " . $this->quizGroups->count() . " групп. \n Вы должны написать номер одного, чтобы продолжить.",
            ['parse_mode' => 'Markdown']
        );
        $this->bot->typesAndWaits(1);

        return $this->ask($this->chooseGroup(), function (BotManAnswer $answer) {

            $selectedTrack = Groups::where('name', $answer->getText())->first();
            if (empty($selectedTrack)) {
                $this->say('Извините, я этого не понял. Пожалуйста, напишите правильно.');
                return $this->selectTrack();
            }
            return $this->setGroupMaterial($selectedTrack->id);
        }, [
            'parse_mode' => 'Markdown'
        ]);
    }

    private function setGroupMaterial($id)
    {
        $materials = Material::with('user')->where('group_id', $id)->get()->toArray();
        if (empty($materials)) {
            $this->say('Материал не опубликовано', [
                'parse_mode' => 'Markdown',
            ]);
        } else {
            foreach ($materials as $material) {
                $this->say('Создал ' . $material['user']['surname'] . ' ' . $material['user']['name'] . "\n" . $material['description'], [
                    'parse_mode' => 'Markdown',
                ]);
            }
        }
    }

    private function chooseGroup()
    {
        $question = '';
        foreach ($this->quizGroups as $answer) {
            $question = $question . "\n" . $answer->name;
        }
        $questionTemplate = BotManQuestion::create("➡️ Пожалуйста, выберите группу" . $question);

        return $questionTemplate;
    }
}
