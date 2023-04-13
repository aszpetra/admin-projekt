<?php

namespace App\Http\Controllers;

use App\Models\Shift_employee;
use App\Models\Shift_log;
use DateTime;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ShiftEmployeeController extends Controller
{
    //$current_user = Auth::id();

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(): View
    {
        $company_id = session('company_id');
        $current_user = Auth::id();

        $shifts = DB::table('shift_logs')
            ->leftJoin('shifts', 'shifts.id', '=','shift_logs.shift_id')
            ->select('shift_logs.id', 'people', 'time', DB::raw('shifts.name, shifts.company_id'))
            ->where('shifts.company_id', '=', $company_id)
            ->where('shift_logs.admin_id', '=', $current_user)
            ->get();



        return view('shift_employee.index', [
            'shifts' => $shifts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return View
     */
    public function create(): View
    {
        $shift_id = $_GET['shift_id'];
        $current_user = Auth::id();
        $company_id = session('company_id');

        $users = DB::table('users')
            ->leftJoin('employees', 'employees.user_id', '=', 'users.id')
            ->select('users.id', 'name')
            ->where('is_admin', "=", false)
            ->where('employees.company_id', '=', $company_id)
            ->get();

        $shift = DB::table('shifts')
            ->select('id', 'name')
            ->where('id', '=', $shift_id)
            ->first();

        return view('shift_employee.create', [
            'shift' => $shift,
            'users' => $users,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $date = new DateTime($request->date);

        $shift_log = new Shift_log();
        $shift_log->shift_id = $_GET['shift'];
        $shift_log->people = $request->people;
        $shift_log->time = $date->format('Y-m-d h:m');
        $shift_log->admin_id = Auth::id();
        $shift_log->save();

        $size = count($request->employees);
        $employees = $request->employees;

        for ($i = 0; $i < $size; $i++) {
            $shift_employee = new Shift_employee();
            $shift_employee->shift_id = $shift_log->id;
            $shift_employee->employee_id = $employees[$i];
            $shift_employee->admin_id = Auth::id();
            $shift_employee->save();
        }

        return redirect(route('shift_employee.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Shift_employee $shift_employee
     * @return \Illuminate\Http\Response
     */
    public function show(Shift_employee $shift_employee): View
    {
        return view('shift_employee.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Shift_employee $shift_employee
     * @return \Illuminate\Http\Response
     */
    public function edit(int $shift_log_id): View
    {
        $shift_log = DB::table('shift_logs')
            ->leftJoin('shifts', 'shifts.id', "=", 'shift_logs.shift_id')
            ->select('shifts.name', DB::raw('shift_logs.id as log_id'), 'people', 'time')
            ->where('shift_logs.id', '=', $shift_log_id)
            ->first();

        $employees = DB::table('shift_employees')
            ->leftJoin('users', 'users.id', '=', 'shift_employees.employee_id')
            ->select('employee_id', 'users.name')
            ->where('is_admin', "=", false)
            ->get();

        return view('shift_employee.edit', [
            'shift' => $shift_log,
            'employees' => $employees
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Shift_employee $shift_employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shift_log $shift_employee): RedirectResponse
    {
        $time = new DateTime($request->time);

        $attributes = [
            'people' => $request->people,
            'time' => $time
        ];

        $shift_employee->update($attributes);

        return redirect(route('shift_employee.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Shift_employee $shift_employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shift_employee $shift_employee)
    {

    }
}
