<?php

namespace App\Http\Controllers;

use App\Models\Shift_log;
use Carbon\Carbon;
use DateTime;
use http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftLogController extends Controller
{

    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function store(Request $request): View
    {
        $shift = DB::table('shifts')
            ->select('*')
            ->where('id', '=', $_GET['shift'])
            ->first();

        $date = Carbon::create($request->date);
        $end_date = Carbon::create($date);
        $shift_log = new Shift_log();
        $shift_log->shift_id = $_GET['shift'];
        $shift_log->people = $request->people;
        $shift_log->start = $date->toDateTime();
        $shift_log->end = $end_date->addHours($shift->work_hours);
        $shift_log->admin_id = Auth::id();
        $shift_log->save();

        $available_emp = $this->available_employees($shift_log->id);

        return view('admin.shift_employee.employees', [
            'shift_log' => $shift_log,
            'users' => $available_emp,
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
                            if($employee->type == "seasonal"){
                                if ($employee->seasonal_days <= 120){
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
                            }else {
                                if($employee->casual_days <= 90) {
                                    if (!empty($next_shift->first())) {
                                        $this_end = Carbon::create($shift_log->end);
                                        $next_start = Carbon::create($next_shift[0]->start);
                                        $subtract_next = $next_start->diffInHours($this_end);
                                    }
                                    if (empty($next_shift->first()) || $subtract_next > 8) {
                                        $now = Carbon::today();
                                        $month_start = $now->startOfMonth();
                                        $month_end = $now->endOfMonth();

                                        $days_worked = DB::table('shift_logs')
                                            ->leftJoin('shift_employees', 'shift_employees.shift_id', '=', 'shift_logs.id')
                                            ->where('shift_employees.employee_id', '=', $employee->user_id)
                                            ->whereBetween('start', [$month_start, $month_end])
                                            ->count();
                                        $today = Carbon::today();
                                        $five_days_ago = $today->subDays(5);
                                        $days_in_row = DB::table('shift_logs')
                                            ->leftJoin('shift_employees', 'shift_employees.shift_id', '=', 'shift_logs.id')
                                            ->where('shift_employees.employee_id', '=', $employee->user_id)
                                            ->whereBetween('start', [$five_days_ago, $today])
                                            ->count();

                                        if ($days_worked < 15) {
                                            if ($days_in_row < 5) {
                                                array_push($available_emp, $user);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if(empty($prev_shift->first())) {
                            array_push($available_emp, $user);
                        }
                    }
                }
            }
        }

        return $available_emp;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shift_log  $shift_log
     * @return \Illuminate\Http\Response
     */
    public function show(Shift_log $shift_log)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shift_log  $shift_log
     * @return \Illuminate\Http\Response
     */
    public function edit(Shift_log $shift_log)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shift_log  $shift_log
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shift_log $shift_log)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shift_log  $shift_log
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shift_log $shift_log)
    {
        $shift_employees = DB::table('shift_employees')
            ->select('*')
            ->where('shift_id', '=', $shift_log->id)
            ->get();

        if($shift_employees != null){
            foreach($shift_employees as $shift_employee)
                $employee = DB::table('employees')
                    ->where('user_id', '=', $shift_employee->employee_id)
                    ->first();

            if (isset($shift_employee)) {
                if (isset($employee)) {
                    if($employee->type == "seasonal"){
                        DB::table('employees')
                            ->where('user_id', '=', $shift_employee->employee_id)
                            ->update(['seasonal_days' => $employee->seasonal_days - 1]);
                    }else {
                        DB::table('employees')
                            ->where('user_id', '=', $shift_employee->employee_id)
                            ->update(['casual_days' => $employee->casual_days - 1]);
                    }

                }

                DB::table('shift_employees')
                    ->where('id', '=', $shift_employee->id)
                    ->delete();
            }
        }

        $shift_log->delete();

        return redirect(route('shift_employee.index'))->with('success','Törölve');
    }

    public function calendarEvents(Request $request): JsonResponse
    {
        $company_id = session('company_id');
        $current_user = Auth::id();
        $start = $request->query('start');
        $end = $request->query('end');

        $shifts = DB::table("shift_logs")
            ->leftJoin("shifts", "shifts.id", "=", "shift_logs.shift_id")
            ->select(DB::raw("shifts.name as title, shift_logs.start, shift_logs.end"))
            ->where("shifts.company_id", "=", $company_id)
            ->where("shift_logs.admin_id", "=", $current_user)
            ->whereBetween("start", [$start, $end])
            ->get();

        return response()->json($shifts);
    }
}
