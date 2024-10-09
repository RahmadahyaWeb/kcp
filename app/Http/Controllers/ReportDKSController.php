<?php

namespace App\Http\Controllers;

use App\Livewire\ReportDksTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportDKSController extends Controller
{
    public function guard()
    {
        if (Auth::user()->role != 'ADMIN' && Auth::user()->role != 'SUPERVISOR-AREA' && Auth::user()->role != 'HEAD-MARKETING') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
    }


    public function index()
    {
        $this->guard();
        
        return view('report-dks.index');
    }
}
