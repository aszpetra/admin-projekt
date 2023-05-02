<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view("employee.home");
    }

    public function getSchedule(Request $request) {

        $current_user = Auth::id();
        $start = $request->query('start');
        $end = $request->query('end');

        $shifts = DB::table("shift_logs")
            ->leftJoin("shifts", "shifts.id", "=", "shift_logs.shift_id")
            ->leftJoin("shift_employees", "shift_employees.shift_id", "=", "shift_logs.id")
            ->select(DB::raw("shifts.name as title, shift_logs.start, shift_logs.end"))
            ->where("shift_employees.employee_id", "=", $current_user)
            ->whereBetween("start", [$start, $end])
            ->get();

        return response()->json($shifts);
    }

    public function holiday_index() {

        $current_user = Auth::id();

        $holidays = DB::table('holidays')
            ->select('id', 'type', 'reason', 'start_date', 'end_date', 'employee_id', 'approved')
            ->where('holidays.employee_id', '=', $current_user)
            ->orderBy('start_date', 'DESC')
            ->get();

        return view('employee.holidays.index', [
            'holidays' => $holidays
        ]);
    }

    public function holiday_create() {
        return view('employee.holidays.create');
    }

    public function holiday_store(Request $request) {

        $type = $request->type;
        $start_date = new DateTime($request->start_date);
        $end_date = new DateTime($request->end_date);
        $reason = $request->reason;

        $admin = DB::table('employees')
            ->select('admin_id')
            ->where('user_id', '=', Auth::id())
            ->first();

        $holiday = new Holiday();
        $holiday->type = $type;
        $holiday->employee_id = Auth::id();
        $holiday->start_date = $start_date->format('Y-m-d');
        $holiday->end_date = $end_date->format('Y-m-d');
        $holiday->reason = $reason;
        $holiday->admin_id = $admin->admin_id;
        $holiday->approved = false;
        $holiday->save();

        return redirect(route('holiday_list'));

    }

    public function holiday_edit(Request $request): View
    {
        $holiday = DB::table('holidays')
            ->select('id', 'type', 'reason', 'start_date', 'end_date', 'employee_id', 'approved')
            ->where('holidays.id', '=', $request->id)
            ->first();

        return view('employee.holidays.edit', [
            'holiday' => $holiday
        ]);
    }

    public function holiday_update(Request $request) {

        $attributes = [
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason
        ];

        $holiday = DB::table('holidays')
            ->where('id', '=', $request->id)
            ->update($attributes);

        return redirect(route('holiday_list'));

    }
}
