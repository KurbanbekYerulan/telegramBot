<?php

namespace App\Conversations;

use App\Models\Groups;
use App\Models\Homeworks;
use App\Models\Question;
use App\Models\Schedule;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer as BotManAnswer;
use BotMan\BotMan\Messages\Outgoing\Question as BotManQuestion;
use Illuminate\Support\Facades\Log;

class TaskConversation extends Conversation
{
    /** @var Groups */
    protected $quizGroups;

    /** @var Question */
    protected $quizQuestions;

    /** @var integer */
    protected $userPoints = 0;

    /** @var integer */
    protected $userCorrectAnswers = 0;

    /** @var integer */
    protected $questionCount;

    /** @var integer */
    protected $currentQuestion = 1;


    /**
     * Start the conversation.
     *
     * @return mixed
     */
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

        return $this->ask($this->chooseTrack(), function (BotManAnswer $answer) {

            if ($answer->isInteractiveMessageReply()) {
                $selectedOption = $answer->getValue();
                $selectedTrack = Groups::find($answer->getValue());
            } else {
                $selectedTrack = Groups::where('name', $answer->getText())->first();
            }

            if (!$selectedTrack) {
                $this->say('Извините, я этого не понял. Пожалуйста, используйте кнопки.');
                return $this->selectTrack();
            }

            return $this->setTrackQuestions($selectedTrack->id);
        }, [
            'parse_mode' => 'Markdown'
        ]);
    }

    private function setTrackQuestions($id)
    {
        $homeworks = Homeworks::where('group_id', $id)->get()->toArray();
        if (empty($homeworks)){
            $this->say('Нет задач', [
                'parse_mode' => 'Markdown',
            ]);
        }else{
            foreach ($homeworks as $homework) {
                $this->say($homework['description'], [
                    'parse_mode' => 'Markdown',
                ]);
            }
        }
    }

    private function chooseTrack()
    {
        $questionTemplate = BotManQuestion::create("➡️ Пожалуйста, выберите группу");

        foreach ($this->quizGroups->shuffle() as $answer) {
            $questionTemplate->addButton(Button::create($answer->name)
                ->value($answer->id));
        }
        return $questionTemplate;
    }
}
