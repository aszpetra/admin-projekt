<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use DateTime;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $current_user = Auth::id();
        $company_id = session('company_id');

        $holidays = DB::table('holidays')
            ->leftJoin('users', 'users.id', '=', 'holidays.employee_id')
            ->leftJoin('employees', 'holidays.employee_id', '=', 'employees.user_id')
            ->select('*', DB::raw('users.name as employee, holidays.id as hol_id'))
            ->where('employees.admin_id', '=', $current_user)
            ->where('employees.company_id', '=', $company_id)
            ->get();

        return view('holidays.index', [
            'holidays' => $holidays,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $current_user = Auth::id();
        $company_id = session('company_id');

        $employees = DB::table('users')
            ->leftJoin('employees', 'employees.user_id', '=', 'users.id')
            ->select('users.name', 'users.id')
            ->where('employees.company_id', '=', $company_id)
            ->where('employees.admin_id', "=", $current_user)
            ->get();

        return view('holidays.create', [
            'employees' => $employees,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $type = $request->type;
        $employee_id = $request->employee_id;
        $start_date = new DateTime($request->start_date);
        $end_date = new DateTime($request->end_date);
        $reason = $request->reason;

        $holiday = new Holiday();
        $holiday->type = $type;
        $holiday->employee_id = $employee_id;
        $holiday->start_date = $start_date->format('Y-m-d');
        $holiday->end_date = $end_date->format('Y-m-d');
        $holiday->reason = $reason;
        $holiday->admin_id = $request->user()->id;
        $holiday->save();

        return redirect(route('holidays.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function show(Holiday $holiday)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function edit(int $holiday_id): View
    {
        $current_user = Auth::id();
        $company_id = session('company_id');

        $holiday = DB::table('holidays')
            ->leftJoin('users', 'users.id', '=', 'holidays.employee_id')
            ->select('*', DB::raw('users.name as employee, holidays.id as hol_id'))
            ->where('holidays.id', '=', $holiday_id)
            ->get();

        $employees = DB::table('users')
            ->leftJoin('employees', 'employees.user_id', '=', 'user.id')
            ->select('name', 'id')
            ->where('employees.company_id', '=', $company_id)
            ->where('employees.admin_id', "=", $current_user)
            ->get();

        return view('holidays.edit', [
            'holiday' => $holiday,
            'employees' => $employees,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Holiday $holiday): RedirectResponse
    {
        $attributes = [
            'type' => $request->type,
            'employee_id' => $request->employee_id,
            'start_date' => new DateTime($request->start_date),
            'end_date' => new DateTime($request->end_date),
            'reason' => $request->reason,
        ];

        $holiday->update($attributes);

        return redirect(route('holidays.index'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function destroy(Holiday $holiday)
    {
        $holiday->delete();

        return redirect(route('holidays.index'));
    }
}
