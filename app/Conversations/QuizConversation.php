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

        return $this->ask($this->chooseGroup(), function (BotManAnswer $answer) {
            $selectedTrack = Groups::where('name', $answer->getText())->first();
            if (empty($selectedTrack)) {
                $this->say('Извините, я этого не понял. Пожалуйста, напишите правильно.');
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
            $isCorrect = false;
            if (strcmp($answer->getText(), 'A') == 0 || strcmp($answer->getText(), 'a') == 0) {
                $quizAnswer = Answer::where('question_id', $question->id)->get()->first();
                if (!empty($quizAnswer)) {
                    $isCorrect = true;
                }
            } elseif (strcmp($answer->getText(), 'B') == 0 || strcmp($answer->getText(), 'b') == 0) {
                $quizAnswer = Answer::where('question_id', $question->id)->orderBy('id', 'desc')->get()->first();
                if (!empty($quizAnswer)) {
                    $isCorrect = true;
                }
            }
            if (!$isCorrect) {
                $this->say("Извините, я этого не понял. Пожалуйста, \n Напишите А или В (А первый, В второй вариант).");
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

    private function chooseGroup()
    {
        $question = '';
        foreach ($this->quizGroups as $answer) {
            $question = $question . "\n" . $answer->name;
        }
        $questionTemplate = BotManQuestion::create("➡️ Пожалуйста, выберите группу" . $question);

        return $questionTemplate;
    }

    private function createQuestionTemplate(Question $question)
    {
        $questions = '';
        foreach ($question->answers as $answer) {
            $questions = $questions . "\n" . $answer->text;
        }
        $questionTemplate = BotManQuestion::create("➡️ *Вопрос {$this->currentQuestion} / {$this->questionCount}* \n{$question->text} \n Напишите А или В (А первый, В второй вариант) \n" . $questions);

        return $questionTemplate;
    }
}
