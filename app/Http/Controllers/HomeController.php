<?php

namespace App\Http\Controllers;

use App\Models\Cashflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    // Halaman utama
    public function index()
    {
        $auth = Auth::user();
        $cashflows = Cashflow::where('user_id', $auth->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.app.home', compact('auth', 'cashflows'));
    }

    // Halaman detail cashflow
    public function cashflowDetail($cashflow_id)
    {
        $auth = Auth::user();
        $cashflow = Cashflow::where('id', $cashflow_id)
            ->where('user_id', $auth->id)
            ->first();

        if (!$cashflow) {
            return redirect()->route('app.home');
        }

        return view('pages.app.cashflow.detail', compact('auth', 'cashflow'));
    }
}