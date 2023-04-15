<?php

namespace App\Http\Controllers;

use App\Models\Shift_log;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftLogController extends Controller
{
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
        $current_user = Auth::id();
        $company_id = session('company_id');
        $date = new DateTime($request->date);

        $shift_log = new Shift_log();
        $shift_log->shift_id = $_GET['shift'];
        $shift_log->people = $request->people;
        $shift_log->time = $date->format('Y-m-d h:m');
        $shift_log->admin_id = Auth::id();
        $shift_log->save();

        $users = DB::table('users')
            ->leftJoin('employees', 'employees.user_id', '=', 'users.id')
            ->select('users.id', 'name')
            ->where('is_admin', "=", false)
            ->where('employees.company_id', '=', $company_id)
            ->get();

        return view('shift_employee.employees', [
            'shift_log' => $shift_log,
            'users' => $users,
        ]);
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
        $shift_log->delete();

        return redirect(route('shift_employee.index'))->with('success','Törölve');
    }
}
