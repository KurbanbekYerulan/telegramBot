<?php

namespace App\Http\Controllers;

use App\Http\Responses\RedirectResponse;
use App\Http\Responses\ViewResponse;
use App\Models\Groups;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index()
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

        $schedules = Schedule::with('user')->where('user_id', auth()->id())
            ->orderBy(DB::raw('FIELD(weekDay, "' . implode('","', array_keys($daysOfWeek)) . '")'))->get();

        return new ViewResponse('schedule.index', ['data' => $schedules]);
    }

    public function create()
    {
        $schedule = new Schedule();
        $groups = Groups::all();
        $schedule->group = $groups;
        $schedule->group_id = 0;
        return new ViewResponse('schedule.create', ['data' => $schedule]);
    }

    public function store(Request $request)
    {
        $newschedule = Schedule::create([
            'weekDay' => $request->weekDay,
            'time' => $request->timeOt . '-' . $request->timeDo,
            'user_id' => auth()->id(),
            'group_id' => $request->group_id
        ]);
        $schedule = Schedule::with('group')->where('user_id', auth()->id())->get();
        return new ViewResponse('schedule.index', ['data' => $schedule]);
    }

    public function edit($id)
    {
        $schedule = Schedule::find($id);
        return new ViewResponse('schedule.edit', ['data' => $schedule]);
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::find($id);
        $schedule->name = $request->name;
        $schedule->update();
        return new RedirectResponse(route('schedule.index'), ['flash_success' => 'Успешно обновлено!']);
    }

    public function destroy($id)
    {
        if (Schedule::destroy($id)) {
            return new RedirectResponse(route('schedule.index'), ['flash_success' => 'Успешно удалено']);
        }

        return new RedirectResponse(route('schedule.index'), ['flash_error' => 'Не удалось удалить']);
    }
}
