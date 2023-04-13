<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{

    public function index(): View
    {
        $current_user = Auth::id();
        $companies = DB::table('companies')
            ->select('id', 'name', 'admin_id')
            ->where('admin_id', '=', $current_user)
            ->get();

        return view('/welcome', [
            'companies' => $companies
        ]);
    }

    public function selected(Request $request): View
    {
        session(['company_id' => $request->company_id]);
        session(['company_name' => $request->company_name]);

        return view('dashboard');
    }
}
