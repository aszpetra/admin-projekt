<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use DateTime;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $employees = DB::table('employees')
            ->leftJoin('companies', 'employees.company_id', '=', 'companies.id')
            ->leftJoin('users', 'employees.user_id', '=', 'users.id')
            ->select('employees.*',  DB::raw('companies.name as company, users.name, users.email'))
            ->orderBy('employees.id', 'asc')
            ->get();

        return view('employees.index', [
            'employees' => $employees,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $companies = DB::table('companies')
            ->select('id', 'name')
            ->get();

        return view('employees.newEmployee', [
            'companies' => $companies,
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
        $name = $request->name;
        $email = $request->email;
        $city = $request->city;
        $address = $request->address;
        $phone = $request->phone;
        $born_date = new DateTime($request->born_date);
        $company_id = $request->company_id;

        if( $request->is_efo == 'efo') {
            $is_efo = true;
        } else {
            $is_efo = false;
        }

        $user = new User();
        $user->email = $email;
        $user->name = $name;
        $user->password = 'test1234';
        $user->is_admin = false;
        $user->save();

        $employee = new Employee();
        $employee->city = $city;
        $employee->address = $address;
        $employee->phone = $phone;
        $employee->born_date = $born_date->format('Y-m-d');
        $employee->is_efo = $is_efo;
        $employee->company_id = $company_id;
        $employee->user_id = $user->id;
        $employee->admin_id = $request->user()->id;
        $employee->is_active = true;
        $employee->save();


        return redirect(route('employees.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $employee_id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $employee_id): View
    {
        $employee = DB::table('employees')
            ->leftJoin('users', 'employees.user_id', '=', 'users.id')
            ->select('employees.*', DB::raw('users.email, users.name'))
            ->where('employees.id', '=', $employee_id)
            ->get();

        $companies = DB::table('companies')
            ->select('id', 'name')
            ->get();

        return view('employees.edit', [
            'employee' => $employee,
            'companies' => $companies,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee): RedirectResponse
    {
        if( $request->is_efo == 'efo') {
            $is_efo = true;
        } else {
            $is_efo = false;
        }

        $born_date = new DateTime($request->born_date);


        $email = $request->email;
        $name = $request->name;


        $attributes = [
            'company_id' => $request->company_id,
            'born_date' => $born_date->format('Y-m-d'),
            'city' => $request->city,
            'address' => $request->address,
            'is_efo' => $is_efo
        ];

        $employee->update($attributes);

        DB::table('users')
            ->where('users.id', '=', $employee->user_id)
            ->update(['email' => $email, 'name' => $name]);

        return redirect(route('employees.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee): RedirectResponse
    {
        DB::table('users')
            ->select('*')
            ->where('users.id', '=', $employee->user_id)
            ->delete();

        $employee->delete();

        return redirect(route('employees.index'));
    }
}
