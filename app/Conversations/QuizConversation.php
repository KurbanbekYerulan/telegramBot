<?php

namespace App\Conversations;

use App\Models\Groups;
use App\Models\Answer;
use App\Models\Played;
use App\Models\Question;
use App\Models\Highscore;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer as BotManAnswer;
use BotMan\BotMan\Messages\Outgoing\Question as BotManQuestion;
use Illuminate\Support\Facades\Log;

class QuizConversation extends Conversation
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

            return $this->setTrackQuestions($selectedTrack);
        }, [
            'parse_mode' => 'Markdown'
        ]);
    }

    private function showInfo($selectedTrack = 'Laravel')
    {
        $this->say(
            "Вам будет показано *{$this->questionCount} вопросы* для группы {$selectedTrack}.",
            ['parse_mode' => 'Markdown']
        );
        $this->bot->typesAndWaits(1);
        $this->say('💡🍀 Пожалуйста, соблюдайте справедливость и не пользуйтесь чьей-либо помощью. Всего наилучшего!', [
            'parse_mode' => 'Markdown',
        ]);

        $this->bot->typesAndWaits(1);

        return $this->checkForNextQuestion();
    }

    private function checkForNextQuestion()
    {
        if ($this->quizQuestions->count()) {
            return $this->askQuestion($this->quizQuestions->first());
        }

        $this->showResult();
    }

    private function setTrackQuestions(Groups $track)
    {
        $this->quizQuestions = $track->questions->shuffle()->take(10);
        $this->questionCount = $this->quizQuestions->count();
        $this->quizQuestions = $this->quizQuestions->keyBy('id');
        return $this->showInfo($track->name);
    }

    private function askQuestion(Question $question)
    {
        $this->bot->typesAndWaits(1);
        $this->ask($this->createQuestionTemplate($question), function (BotManAnswer $answer) use ($question) {
            if ($answer->isInteractiveMessageReply()) {
                $quizAnswer = Answer::find($answer->getValue());
            } else {
                if (strcmp($answer->getText(), 'A') == 0) {
                    $quizAnswer = Answer::where('question_id', $question->id)->get()->first();
                } elseif (strcmp($answer->getText(), 'B') == 0) {
                    $quizAnswer = Answer::where('question_id', $question->id)->orderBy('id', 'desc')->get()->first();
                }
            }

            if (!$quizAnswer) {
                $this->say('Извините, я этого не понял. Пожалуйста, используйте кнопки.');
                return $this->checkForNextQuestion();
            }

            $this->quizQuestions->forget($question->id);

            if ($quizAnswer->correct_one) {
                $this->userPoints += $question->points;
                $this->userCorrectAnswers++;
                $answerResult = '✅';
            } else {
                $correctAnswer = $question->answers()
                    ->where('correct_one', true)
                    ->first()->text;
                $answerResult = "❌ _(Правильный: {$correctAnswer})_";
            }
            $this->currentQuestion++;

            $this->say("*Ваш ответ:* {$quizAnswer->text} {$answerResult}", [
                'parse_mode' => 'Markdown'
            ]);
            $this->checkForNextQuestion();
        }, [
            'parse_mode' => 'Markdown'
        ]);
    }

    private function showResult()
    {
        Played::create([
            'chat_id' => $this->bot->getUser()->getId(),
            'points' => $this->userPoints,
        ]);
        $this->bot->typesAndWaits(1);
        $this->say('🏁 Finished 🏁');
        $this->bot->typesAndWaits(1);
        $this->say(
            "Вы справились со всеми вопросами. \n Правильные ответы: {$this->userCorrectAnswers} / {$this->questionCount}",
            ['parse_mode' => 'Markdown']
        );
        $user = Highscore::saveUser($this->bot->getUser(), $this->userCorrectAnswers, $this->userCorrectAnswers);
        return $this->bot->startConversation(new HighscoreConversation());
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

    private function createQuestionTemplate(Question $question)
    {
        $questionTemplate = BotManQuestion::create("➡️ *Вопрос {$this->currentQuestion} / {$this->questionCount}* \n{$question->text} \n Напишите А или В (А первый, В второй вариант)");

        foreach ($question->answers as $answer) {
            $questionTemplate->addButton(Button::create($answer->text)->value($answer->id)->additionalParameters(['parse_mode' => 'Markdown']));
        }
        return $questionTemplate;
    }
}
