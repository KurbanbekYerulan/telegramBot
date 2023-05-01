<?php

namespace App\Http\Controllers;

use App\Http\Responses\RedirectResponse;
use App\Http\Responses\ViewResponse;
use App\Models\Groups;
use App\Models\Homeworks;
use Illuminate\Http\Request;

class HomeworksController extends Controller
{
    public function index()
    {
        $data = Homeworks::with('group')->get();
        return new ViewResponse('homeworks.index', ['data' => $data]);
    }

    public function create()
    {
        $homework = new Homeworks();
        $groups = Groups::all();
        $homework->group = $groups;
        $homework->group_id = 0;
        return new ViewResponse('homeworks.create', ['data' => $homework]);

    }

    public function store(Request $request)
    {
        $homework = Homeworks::create([
            'description' => $request->description,
            'group_id' => $request->group_id
        ]);
        return new RedirectResponse(route('homeworks.index'), ['flash_success' => 'Успешно создано!']);

    }

    public function edit($id)
    {
        $data = Homeworks::with('group')->find($id);
        $groups = Groups::all();
        $data->group = $groups;
        return new ViewResponse('homeworks.edit', ['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $homework = Homeworks::find($id);
        $homework->description = $request->description;
        $homework->group_id = $request->group_id;
        $homework->update();
        return new RedirectResponse(route('homeworks.index'), ['flash_success' => 'Успешно обновлено!']);
    }

    public function destroy($id)
    {
        if (Homeworks::destroy($id)) {
            return new RedirectResponse(route('schedule.index'), ['flash_success' => 'Успешно удалено']);
        }

        return new RedirectResponse(route('schedule.index'), ['flash_error' => 'Не удалось удалить']);
    }
}
