<?php

namespace App\Livewire;

use App\Models\KcpInformation;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class AopGr extends Component
{
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';

    public $invoiceAop;

    public static function getTotalQty($spb)
    {
        return DB::table('invoice_aop_header')
            ->where('SPB', $spb)
            ->sum('qty');
    }

    public static function getInvoices($spb)
    {
        return DB::table('invoice_aop_header')
            ->select(['invoiceAop', 'status'])
            ->where('SPB', $spb)
            ->get();
    }


    public static function getIntransitBySpb($spb)
    {
        $kcpInformation = new KcpInformation;

        $login = $kcpInformation->login();

        if ($login) {
            $token = $login['token'];
        }

        $intransitStock = $kcpInformation->getIntransitBySpb($token, $spb);

        $totalQtyTerima = 0;

        if (isset($intransitStock['data'])) {
            foreach ($intransitStock as $items) {
                foreach ($items as $item) {
                    $totalQtyTerima += $item['qty_terima'];
                }
            }
        } 

        return $totalQtyTerima;
    }

    public function render()
    {
        $invoiceAopHeader = DB::table('invoice_aop_header')
            ->select('SPB')
            ->groupBy('spb')
            ->get();

        return view('livewire.aop-gr', compact('invoiceAopHeader'));
    }
}
