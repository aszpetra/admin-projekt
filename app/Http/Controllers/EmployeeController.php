<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use DateTime;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class EmployeeController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $company_id = session('company_id');
        $current_user = Auth::id();

        $employees = DB::table('employees')
            ->leftJoin('companies', 'employees.company_id', '=', 'companies.id')
            ->leftJoin('users', 'employees.user_id', '=', 'users.id')
            ->select('employees.*',  DB::raw('companies.name as company, users.name, users.email'))
            ->where('employees.company_id', "=", $company_id)
            ->where('employees.admin_id', '=', $current_user)
            ->orderBy('employees.id', 'asc')
            ->get();

        return view('admin.employees.index', [
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
        return view('admin.employees.newEmployee');
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
        $company_id = session('company_id');
        $type = $request->type;

        $user = new User();
        $user->email = $email;
        $user->name = $name;
        $user->password = Hash::make(Str::random(20)); // generate a random password
        $user->is_admin = false;
        $user->save();

        $employee = new Employee();
        $employee->city = $city;
        $employee->address = $address;
        $employee->phone = $phone;
        $employee->born_date = $born_date->format('Y-m-d');
        $employee->casual_days = 0;
        $employee->seasonal_days = 0;
        $employee->type = $type;
        $employee->company_id = $company_id;
        $employee->user_id = $user->id;
        $employee->admin_id = $request->user()->id;
        $employee->is_active = true;
        $employee->save();

        $token = Password::createToken($user);
        $user->sendPasswordResetNotification($token);

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
        $current_user = Auth::id();

        $employees = DB::table('employees')
            ->leftJoin('companies', 'employees.company_id', '=', 'companies.id')
            ->leftJoin('users', 'employees.user_id', '=', 'users.id')
            ->select('employees.*',  DB::raw('companies.name as company, users.name'))
            ->where('employees.admin_id', '=', $current_user)
            ->orderBy('employees.id', 'asc')
            ->get();

        return view('admin.employees/all_employee', [
            'employees' => $employees,
        ]);
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

        return view('admin.employees.edit', [
            'employee' => $employee,
            'companies' => $companies,
        ]);
    }

    public function general_edit(Request $request): View
    {
        $employee = DB::table('employees')
            ->leftJoin('users', 'employees.user_id', '=', 'users.id')
            ->select(DB::raw('employees.id, users.name, employees.is_active, employees.company_id'))
            ->where('employees.id', '=', $request->id)
            ->get();

        $companies = DB::table('companies')
            ->select('id', 'name')
            ->get();

        return view('admin.employees.general_edit', [
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

        $born_date = new DateTime($request->born_date);

        $email = $request->email;
        $name = $request->name;

        $attributes = [
            'company_id' => $request->company_id,
            'born_date' => $born_date->format('Y-m-d'),
            'city' => $request->city,
            'address' => $request->address,
            'type' => $request->type
        ];

        $employee->update($attributes);

        DB::table('users')
            ->where('users.id', '=', $employee->user_id)
            ->update(['email' => $email, 'name' => $name]);

        return redirect(route('employees.index'));
    }

    public function general_update(Request $request): RedirectResponse
    {

        if($request->is_active == "yes"){
            $is_active = true;
        }else {
            $is_active = false;
        }

        $attributes = [
            'company_id' => $request->company_id,
            'is_active' => $is_active,
        ];

        try {
            DB::table('employees')
                ->where('id', '=', $request->id)
                ->update(['is_active' => $is_active, 'company_id' => $request->company_id]);
        } catch (\Exception $e) {
            Log::error('Failed to update employee data: ' . $e->getMessage());
        }


        return redirect(url('all_employees'));
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
