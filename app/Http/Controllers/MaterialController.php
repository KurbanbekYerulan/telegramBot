<?php

namespace App\Http\Controllers;

use App\Http\Responses\RedirectResponse;
use App\Http\Responses\ViewResponse;
use App\Models\Groups;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Material::with('group')->where('user_id', auth()->id())->get();
        return new ViewResponse('material.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $material = new Material();
        $groups = Groups::all();
        $material->group = $groups;
        $material->group_id = 0;
        return new ViewResponse('material.create', ['data' => $material]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = Material::create([
            'description' => $request->description,
            'user_id' => auth()->id(),
            'group_id' => $request->group_id
        ]);
        return new RedirectResponse(route('material.index'), ['flash_success' => 'Успешно сохранено!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Material $material)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = Material::with('group')->find($id);
        $group = Groups::all();
        $data->group = $group;
        return new ViewResponse('material.edit', ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = Material::find($id);
        $data->description = $request->description;
        $data->update();
        return new RedirectResponse(route('material.index'), ['flash_success' => 'Успешно обновлено!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (Material::destroy($id)) {
            return new RedirectResponse(route('material.index'), ['flash_success' => 'Успешно удалено']);
        }

        return new RedirectResponse(route('material.index'), ['flash_error' => 'Не удалось удалить']);
    }
}
