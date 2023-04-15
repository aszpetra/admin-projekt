<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        $company_id = session('company_id');

        $shifts = DB::table('shifts')
            ->leftJoin('companies', 'shifts.company_id', '=', 'companies.id')
            ->select('shifts.*',  DB::raw('companies.name as company'))
            ->where('shifts.company_id', '=', $company_id)
            ->orderBy('shifts.id', 'asc')
            ->get();

        return view('shifts.index', [
            'shifts' => $shifts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        return view('shifts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $company_id = session('company_id');
        $name = $request->name;
        $work_hours =  $request->work_hours;

        $shift = new Shift;
        $shift->company_id = $company_id;
        $shift->name = $name;
        $shift->work_hours = $work_hours;
        $shift->admin_id = $request->user()->id;
        $shift->save();

        return redirect(route('shifts.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shift  $shift
     * @return \Illuminate\Http\Response
     */
    public function show(Shift $shift)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $shift_id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $shift_id): View
    {
        $shift = DB::table('shifts')
            ->select('*')
            ->where('id', '=', $shift_id)
            ->get();

        $companies = DB::table('companies')
            ->select('id', 'name')
            ->get();

        return view('shifts.edit', [
            'shift' => $shift,
            'companies' => $companies,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shift  $shift
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shift $shift): RedirectResponse
    {
        $attributes = [
            'company_id' => $request->company_id,
            'name' => $request->name,
            'work_hours' => $request->work_hours,
        ];
        $shift->update($attributes);

        return redirect(route('shifts.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shift  $shift
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shift $shift): RedirectResponse
    {
        $shift->delete();

        return redirect(route('shifts.index'));
    }
}
