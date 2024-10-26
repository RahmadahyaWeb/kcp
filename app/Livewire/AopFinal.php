<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class AopFinal extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $paginationTheme = 'bootstrap';

    public $invoiceAop;

    public function search()
    {
        $this->resetPage();
    }

    public function cancel($invoiceAop)
    {
        dd($invoiceAop);
    }

    public function render()
    {
        $invoiceAopHeader = DB::table('invoice_aop_header')
            ->select(['*'])
            ->where('invoiceAop', 'like', '%' . $this->invoiceAop . '%')
            ->where('flag_selesai', '!=', 'N')
            ->orderBy('billingDocumentDate', 'asc')
            ->paginate(20);

        return view('livewire.aop-final', compact('invoiceAopHeader'));
    }
}
