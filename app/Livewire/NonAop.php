<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class NonAop extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $paginationTheme = 'bootstrap';

    public $invoiceNon;
    public $tanggalJatuhTempo;

    public function hapusInvoiceNon($invoiceNon)
    {
        DB::table('invoice_non_header')
            ->where('invoiceNon', $invoiceNon)
            ->delete();

        session()->flash('status', "Invoice: $invoiceNon berhasil dihapus.");
    }

    public function render()
    {
        $invoiceNonAopHeader = DB::table('invoice_non_header')
            ->select(['*'])
            ->where('flag_selesai', '!=', 'Y')
            ->where('invoiceNon', 'like', '%' . $this->invoiceNon . '%')
            ->when($this->tanggalJatuhTempo, function ($query) {
                return $query->where('tanggalJatuhTempo', $this->tanggalJatuhTempo);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('livewire.non-aop', compact('invoiceNonAopHeader'));
    }
}
