<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AopDetail extends Component
{
    public function placeholder()
    {
        return <<<'HTML'
        <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        HTML;
    }

    public $fakturPajak;
    public $editingFakturPajak;

    public $class;
    public $style;

    public function openModal()
    {
        $this->class = "show";
        $this->style = "display: block;";

        $invoice = DB::table('invoice_aop_header')
            ->select(['*'])
            ->where('invoiceAop', $this->invoiceAop)
            ->first();

        $this->fakturPajak = $invoice->fakturPajak;
    }

    public function closeModalProgram()
    {
        $this->resetValidation(['potonganProgram', 'keteranganProgram']);
    }

    #[Validate('required')]
    public $potonganProgram = '';

    #[Validate('required')]
    public $keteranganProgram = '';

    public $customerTo;
    public $tanggalInvoice;

    public function saveProgram()
    {
        $this->class = "show";
        $this->style = "display: block;";

        $validated = $this->validate();

        $validated['customerTo'] = $this->customerTo;
        $validated['invoiceAop'] = $this->invoiceAop;
        $validated['tanggalInvoice'] = $this->tanggalInvoice;

        DB::table('program_aop')
            ->insert($validated);

        $this->dispatch('programSaved');

        $this->class = "";
        $this->style = "";

        $this->reset('potonganProgram');
        $this->reset('keteranganProgram');
    }

    public function destroyProgram($id)
    {
        DB::table('program_aop')
            ->where('id', $id)
            ->delete();
    }

    public function saveFakturPajak()
    {
        DB::table('invoice_aop_header')
            ->where('invoiceAop', $this->invoiceAop)
            ->update([
                'fakturPajak' => $this->fakturPajak
            ]);

        $this->dispatch('fakturPajakUpdate');
    }

    public $invoiceAop;
    public $totalAmount;
    public $totalQty;

    public function mount($invoiceAop)
    {
        $this->invoiceAop = $invoiceAop;
    }

    public function render()
    {
        $header = DB::table('invoice_aop_header')
            ->select(['*'])
            ->where('invoiceAop', $this->invoiceAop)
            ->first();

        $details = DB::table('invoice_aop_detail')
            ->select(['*'])
            ->where('invoiceAop', $this->invoiceAop)
            ->get();

        $totalAmount = DB::table('invoice_aop_detail')
            ->where('invoiceAop', $this->invoiceAop)
            ->sum('amount');

        $totalQty = DB::table('invoice_aop_detail')
            ->where('invoiceAop', $this->invoiceAop)
            ->sum('qty');

        $this->totalAmount = $totalAmount;
        $this->totalQty = $totalQty;

        $this->fakturPajak = $header->fakturPajak;
        $this->tanggalInvoice = $header->billingDocumentDate;
        $this->customerTo = $header->customerTo;

        $programAop = DB::table('program_aop')
            ->select(['*'])
            ->where('invoiceAop', $this->invoiceAop)
            ->get();

        return view('livewire.aop-detail', compact(
            'header',
            'details',
            'programAop'
        ));
    }
}
