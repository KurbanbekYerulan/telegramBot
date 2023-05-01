<?php

namespace App\Http\Controllers;

use App\Http\Responses\RedirectResponse;
use App\Http\Responses\ViewResponse;
use App\Models\Groups;
use Illuminate\Http\Request;

class GroupsController extends Controller
{
    public function index()
    {
        $groups = Groups::get()->toArray();
        return view('groups.index')->with('data', $groups);
    }

    public function create()
    {
        return view('groups.create');
    }

    public function store(Request $request)
    {
        $group = Groups::create([
            'name' => $request->name
        ]);
        $groups = Groups::get()->toArray();
        return new ViewResponse('groups.index', ['data' => $groups]);
    }

    public function edit($id)
    {
        $group = Groups::find($id);
        return new ViewResponse('groups.edit', ['data' => $group]);
    }

    public function update(Request $request, $id)
    {
        $group = Groups::find($id);
        $group->name = $request->name;
        $group->update();
        return new RedirectResponse(route('groups.index'), ['flash_success' => 'Успешно обновлено!']);
    }

    public function destroy($id)
    {
        if (Groups::destroy($id)) {
            return new RedirectResponse(route('groups.index'), ['flash_success' => 'Успешно удалено']);
        }

        return new RedirectResponse(route('groups.index'), ['flash_error' => 'Не удалось удалить']);
    }
}
