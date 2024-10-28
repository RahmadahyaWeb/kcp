<?php

namespace App\Livewire;

use Livewire\Component;

class NonAopDetail extends Component
{

    public $invoiceNon;
    public function mount($invoiceNon)
    {
        $this->invoiceNon = $invoiceNon;
    }

    public function render()
    {
        return view('livewire.non-aop-detail');
    }
}
