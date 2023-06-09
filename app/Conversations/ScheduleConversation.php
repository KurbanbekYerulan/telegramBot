<?php

namespace App\Conversations;

use App\Models\Groups;
use App\Models\Answer;
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

class ScheduleConversation extends Conversation
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

        return $this->ask($this->chooseGroup(), function (BotManAnswer $answer) {

            $selectedTrack = Groups::where('name', $answer->getText())->first();
            if (empty($selectedTrack)) {
                $this->say('Извините, я этого не понял. Пожалуйста, напишите правильно.');
                return $this->selectTrack();
            }

            return $this->setTrackQuestions($selectedTrack->id);
        }, [
            'parse_mode' => 'Markdown'
        ]);
    }

    private function setTrackQuestions($id)
    {
        $daysOfWeek = [
            'Понедельник' => 1,
            'Вторник' => 2,
            'Среда' => 3,
            'Четверг' => 4,
            'Пятница' => 5,
            'Суббота' => 6,
            'Воскресенье' => 7,
        ];

        $schedules = Schedule::with('user')->where('group_id', $id)
            ->orderBy(DB::raw('FIELD(weekDay, "' . implode('","', array_keys($daysOfWeek)) . '")'))->get()->toArray();
        if (empty($schedules)) {
            $this->say('Расписание не опубликовано', [
                'parse_mode' => 'Markdown',
            ]);
        } else {
            foreach ($schedules as $schedule) {
                $this->say($schedule['weekDay'] . "\n" . $schedule['time'] . "\n" . $schedule['user']['surname'] . ' ' . $schedule['user']['name'], [
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
