<?php

namespace App\Livewire;

use App\Models\KcpInformation;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Component;

class NonAopDetail extends Component
{
    public $search;
    public $invoiceNon;

    #[Validate('required')]
    public $materialNumber;

    #[Validate('required')]
    public $qty;

    #[Validate('required')]
    public $price;

    public $extraPlafonDiscount;

    public function addItem()
    {
        $this->validate();
    }

    public function mount($invoiceNon)
    {
        $this->invoiceNon = $invoiceNon;
    }

    public function getNonAopParts()
    {
        $kcpInformation = new KcpInformation;

        $login = $kcpInformation->login();

        if ($login) {
            $token = $login['token'];
        }

        $nonAopParts = $kcpInformation->getNonAopParts($token);

        if ($nonAopParts) {
            return $nonAopParts;
        }
    }

    public function render()
    {
        $header = DB::table('invoice_non_header')
            ->where('invoiceNon', $this->invoiceNon)
            ->leftJoin('master_supplier', 'invoice_non_header.supplierCode', '=', 'master_supplier.supplierCode')
            ->first();

        $nonAopParts = $this->getNonAopParts();

        $search = $this->search;

        $nonAopParts = array_filter($nonAopParts['data'], function($item) use ($search) {
            return strpos(strtolower($item['txt']), strtolower($search)) !== false;
        });
        
        return view('livewire.non-aop-detail', compact('header', 'nonAopParts'));
    }
}
