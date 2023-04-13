<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public int $selected_id = 0;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        $current_user = Auth::id();
        $companies = DB::table('companies')
            ->select('id', 'name', 'admin_id')
            ->where('admin_id', '=', $current_user)
            ->get();

        return view('companies.index', [
            'companies' => $companies,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        return view('companies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string',
        ]);

        $name = $validated['name'];

        $company = new Company;
        $company->name = $name;
        $company->admin_id = Auth::id();
        $company->save();

        return redirect(route('welcome'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(int $company_id): View
    {
        $company = DB::table('companies')
            ->leftJoin('users', 'users.id', '=', 'companies.admin_id')
            ->select( DB::raw('users.name as admin, companies.name as name, companies.id'))
            ->where('companies.id', '=', $company_id)
            ->first();

        return view('companies.edit', [
            'company' => $company,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company): RedirectResponse
    {
        $attributes = [
            'name' => $request->name,
        ];

        $company->update($attributes);

        return redirect(route('companies.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company): RedirectResponse
    {
        $company->delete();

        return redirect(route('companies.index'));
    }
}
