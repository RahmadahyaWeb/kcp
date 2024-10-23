<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class AopUploadTable extends Component
{
    use WithPagination, WithoutUrlPagination;

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


    public function placeholder()
    {
        return <<<'HTML'
        <div>
             Loading...
        </div>
        HTML;
    }

    public $invoiceAop;
    public function search()
    {
        $this->resetPage();
    }

    #[On('file-uploaded')]
    public function render()
    {
        $invoiceAopHeader = DB::table('invoice_aop_header')
            ->select(['*'])
            ->when($this->invoiceAop, function ($query) {
                return $query->where('invoiceAop', 'like', '%' . $this->invoiceAop . '%');
            })
            ->orderBy('billingDocumentDate', 'asc')
            ->paginate(20);

        return view('livewire.aop-upload-table', compact('invoiceAopHeader'));
    }
}
