<?php

namespace App\Http\Controllers;

use App\Http\Responses\RedirectResponse;
use App\Http\Responses\ViewResponse;
use App\Models\Answer;
use App\Models\Groups;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index($id)
    {
        $this->show($id);
    }

    public function show($id)
    {
        $question = Question::with('answers')->where('group_id', $id)->get();
        return new ViewResponse('test.questions.index', ['data' => $question]);
    }

    public function create()
    {
        $question = new Question();
        $groups = Groups::all();
        $question->group = $groups;
        $question->group_id = 0;
        $question->correct_one = 'C';
        return new ViewResponse('test.questions.create', ['data' => $question]);
    }

    public function store(Request $request)
    {
        $question = Question::create([
            'text' => $request->text,
            'group_id' => $request->group_id,
            'points' => 10
        ]);
        if ($question->id) {
            if (strcmp('A', $request->correct_one) == 0) {
                $answerA = Answer::create([
                    'question_id' => $question->id,
                    'text' => $request->answerA,
                    'correct_one' => 1
                ]);
                $answerB = Answer::create([
                    'question_id' => $question->id,
                    'text' => $request->answerB,
                    'correct_one' => 0
                ]);
            } else {
                $answerA = Answer::create([
                    'question_id' => $question->id,
                    'text' => $request->answerA,
                    'correct_one' => 0
                ]);
                $answerB = Answer::create([
                    'question_id' => $question->id,
                    'text' => $request->answerB,
                    'correct_one' => 1
                ]);
            }
            return new RedirectResponse(route('questions.show', $request->group_id), ['flash_success' => 'Успешно сохранено']);
        } else {
            return new RedirectResponse(route('questions.create'), ['flash_error' => 'Не удалось сохранить']);
        }
    }

    public function edit($id)
    {
        $question = Question::with('group', 'answers')->find($id);
        $groups = Groups::all();
        $question->answerA = $question->answers[0]['text'];
        $question->answerB = $question->answers[1]['text'];
        if ($question->answers[0]['correct_one'] == 1) {
            $question->correct_one = 'A';
        } else {
            $question->correct_one = 'B';
        }
        $question->group = $groups;
        return new ViewResponse('test.questions.edit', ['data' => $question]);
    }

    public function update(Request $request, $id)
    {
        $question = Question::find($id);
        $question->text = $request->text;
        $question->update();
        if ($question->id) {
            if (strcmp('A', $request->correct_one) == 0) {
                $answerA = Answer::where('question_id', $question->id)->first();
                $answerB = Answer::where('question_id', $question->id)->orderBy('id', 'desc')->first();

                $answerA->text = $request->answerA;
                $answerA->correct_one = 1;
                $answerA->update();

                $answerB->text = $request->answerB;
                $answerB->correct_one = 0;
                $answerB->update();
                //dd($request->correct_one, '--', $answerA, $answerB, '+++', Answer::where('question_id', $question->id)->first(), '---', Answer::where('question_id', $question->id)->orderBy('created_at', 'desc')->first());

            } else {
                $answerA = Answer::where('question_id', $question->id)->first();
                $answerB = Answer::where('question_id', $question->id)->orderBy('id', 'desc')->first();

                $answerA->text = $request->answerA;
                $answerA->correct_one = 0;
                $answerA->update();

                //dd($request->correct_one, '--', $answerA, $answerB);

                $answerB->text = $request->answerB;
                $answerB->correct_one = 1;
                $answerB->update();
            }
            return new RedirectResponse(route('questions.show', $request->group_id), ['flash_success' => 'Успешно обновлено']);
        } else {
            return new RedirectResponse(route('questions.create'), ['flash_error' => 'Не удалось обновить']);
        }
    }

    public function destroy($id)
    {
        if (Schedule::destroy($id)) {
            return new RedirectResponse(route('schedule.index'), ['flash_success' => 'Успешно удалено']);
        }

        return new RedirectResponse(route('schedule.index'), ['flash_error' => 'Не удалось удалить']);
    }
}
