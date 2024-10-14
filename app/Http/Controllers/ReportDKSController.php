<?php

namespace App\Http\Controllers;

use App\Exports\DksExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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

    public function export(Request $request)
    {
        $this->guard();

        $request->validate([
            'fromDate'  => 'required',
            'toDate'    => 'required',
        ]);

        ini_set('max_execution_time',3600);

        return Excel::download(new DksExport($request->fromDate, $request->toDate), 'dks.xlsx');
    }
}
