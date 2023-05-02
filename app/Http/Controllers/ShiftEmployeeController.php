<?php

namespace App\Http\Controllers;

use App\Models\Shift_employee;
use App\Models\Shift_log;
use App\Http\Controllers\ShiftLogController;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ShiftEmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(): View
    {
        $company_id = session('company_id');
        $current_user = Auth::id();

        $today = Carbon::today();

        $shifts_past = DB::table('shift_logs')
            ->leftJoin('shifts', 'shifts.id', '=','shift_logs.shift_id')
            ->select('shift_logs.id', 'people', 'start', 'end', DB::raw('shifts.name, shifts.company_id'))
            ->where('shifts.company_id', '=', $company_id)
            ->where('shift_logs.admin_id', '=', $current_user)
            ->where('shift_logs.start', '<', $today)
            ->orderBy('start', 'Desc')
            ->get();

        $shifts_future = DB::table('shift_logs')
            ->leftJoin('shifts', 'shifts.id', '=','shift_logs.shift_id')
            ->select('shift_logs.id', 'people', 'start', 'end', DB::raw('shifts.name, shifts.company_id'))
            ->where('shifts.company_id', '=', $company_id)
            ->where('shift_logs.admin_id', '=', $current_user)
            ->where('shift_logs.start', '>=', $today)
            ->orderBy('start', 'Desc')
            ->get();

        return view('admin.shift_employee.index', [
            'shifts_past' => $shifts_past,
            'shifts_future' => $shifts_future
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return View
     */
    public function create(): View
    {
        $shift_id = $_GET['shift_id'];

        $shift = DB::table('shifts')
            ->select('id', 'name')
            ->where('id', '=', $shift_id)
            ->first();

        return view('admin.shift_employee.create', [
            'shift' => $shift,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function store(Request $request): RedirectResponse
    {
        $size = count($request->employees);
        $employees = $request->employees;

        for ($i = 0; $i < $size; $i++) {
            $shift_employee = new Shift_employee();
            $shift_employee->shift_id = $request->shift;
            $shift_employee->employee_id = $employees[$i];
            $shift_employee->admin_id = Auth::id();
            $shift_employee->save();

            $employee = DB::table('employees')
                ->where('user_id', '=', $employees[$i])
                ->first();

            DB::table('employees')
                ->where('user_id', '=', $employees[$i])
                ->update(['days' => $employee->days + 1]);
        }

        return redirect(route('shift_employee.index'))->with('success', 'Sikeresen létrehozva!');
    }

    /**
     * Display the specified resource.
     *
     * @param integer $shift_id
     * @return \Illuminate\Http\Response
     */
    public function show(int $shift_id): View
    {
        $employees = DB::table('shift_employees')
            ->leftJoin('users', 'users.id', '=', 'shift_employees.employee_id')
            ->select('shift_id', 'employee_id', 'admin_id', 'users.name')
            ->where('admin_id', '=', Auth::id())
            ->where('shift_id', '=', $shift_id)
            ->get();

        return view('admin.shift_employee.show', [
            'employees' => $employees
        ]);
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
            ->select('shifts.name', DB::raw('shift_logs.id as log_id'), 'people', 'start')
            ->where('shift_logs.id', '=', $shift_log_id)
            ->first();

        $checked_employees = DB::table('shift_employees')
            ->leftJoin('users', 'users.id', '=', 'shift_employees.employee_id')
            ->leftJoin('shift_logs', 'shift_employees.shift_id', '=', 'shift_logs.id')
            ->select('employee_id as id', 'users.name')
            ->where('is_admin', "=", false)
            ->where('shift_logs.id', '=', $shift_log_id)
            ->get();

        $available = $this->available_employees($shift_log_id);

        return view('admin.shift_employee.edit', [
            'shift' => $shift_log,
            'checked_employees' => $checked_employees,
            'employees' => $available
        ]);
    }

    public function available_employees($shift_log_id)
    {
        $company_id = session('company_id');
        $available_emp = [];

        $shift_log = DB::table('shift_logs')
            ->select('*')
            ->where('id', '=', $shift_log_id)
            ->first();

        $users = DB::table('users')
            ->leftJoin('employees', 'employees.user_id', '=', 'users.id')
            ->select('users.id', 'name')
            ->where('is_admin', "=", false)
            ->where('employees.company_id', '=', $company_id)
            ->orderBy('days', 'ASC')
            ->get();

        foreach ($users as $user) {

            $employee = DB::table('employees')
                ->where('user_id', '=', $user->id)
                ->first();

            if($employee->is_active){

                $prev_shift = DB::table('shift_logs')
                    ->leftJoin('shift_employees', 'shift_employees.shift_id', '=', 'shift_logs.id')
                    ->select('end', 'start')
                    ->where('shift_employees.employee_id', '=', $user->id)
                    ->where('shift_logs.start', '<=', $shift_log->start)
                    ->orderBy('end', 'DESC')
                    ->get();

                $next_shift = DB::table('shift_logs')
                    ->leftJoin('shift_employees', 'shift_employees.shift_id', '=', 'shift_logs.id')
                    ->select('*')
                    ->where('shift_employees.employee_id', '=', $user->id)
                    ->where('shift_logs.start', '>=', $shift_log->start)
                    ->orderBy('end', 'ASC')
                    ->get();
                $holiday = DB::table('holidays')
                    ->select('start_date', 'end_date')
                    ->where('employee_id', '=', $user->id)
                    ->where('start_date', '<=', $shift_log->start)
                    ->where('end_date', '>=', $shift_log->start)
                    ->first();

                if(empty($holiday)){

                    if(!empty($prev_shift->first() && $prev_shift->first()->start != $shift_log->start)){
                        $this_start = Carbon::create($shift_log->start);
                        $last_end = Carbon::create($prev_shift[0]->end);
                        $subtract_prev = $last_end->diffInHours($this_start);

                        if($subtract_prev > 8 ){
                            if ($employee->days < 15){
                                if(!empty($next_shift->first())){
                                    $this_end =  Carbon::create($shift_log->end);
                                    $next_start = Carbon::create($next_shift[0]->start);
                                    $subtract_next = $next_start->diffInHours($this_end);
                                    if($subtract_next > 8){
                                        array_push($available_emp, $user);
                                    }
                                }else {
                                    array_push($available_emp, $user);
                                }
                            }
                        }
                    } else {
                        if($prev_shift->first()->start != $shift_log->start){
                            array_push($available_emp, $user);
                        }
                    }
                }
            }
        }

        return $available_emp;
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
        $time = Carbon::create($request->time);


        $attributes = [
            'people' => $request->people,
            'start' => $time->toDateTime(),
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
    public function destroy(Shift_employee $shift_employee): RedirectResponse
    {

        $shift_employee->delete();

    }
}
