<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DksExport implements WithMultipleSheets   
{
    public function sheets(): array
    {
        $sales = DB::table('trns_dks')->where('type', 'in')
            ->distinct()
            ->pluck('user_sales');

        $sheets = [];

        foreach ($sales as $user_sales) {
            $sheets[] = new SalesSheet($user_sales);
        }

        return $sheets;
    }
}
