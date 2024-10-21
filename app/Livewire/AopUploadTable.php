<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class AopUploadTable extends Component
{
    use WithPagination;

    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $selectedInvoices = [];
    public function processSelectedInvoices()
    {
        dd($this->selectedInvoices);
    }

    public function updatedSelectedInvoices($value)
    {
        $selectedInvoices[] = $value;
    }

    #[On('file-uploaded')]
    public function render()
    {
        $invoiceAopHeader = DB::table('invoice_aop_header')
            ->select(['*'])
            ->orderBy('billingDocumentDate', 'asc')
            ->paginate(20);

        return view('livewire.aop-upload-table', compact('invoiceAopHeader'));
    }
}
