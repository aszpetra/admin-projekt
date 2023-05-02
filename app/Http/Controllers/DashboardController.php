<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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

        return view('admin.welcome', [
            'companies' => $companies
        ]);
    }

    public function dashboard(): View{
        return view('admin.dashboard');
    }

    public function selected(Request $request): View
    {
        session(['company_id' => $request->company_id]);
        session(['company_name' => $request->company_name]);

        return view('admin.dashboard');
    }
}
