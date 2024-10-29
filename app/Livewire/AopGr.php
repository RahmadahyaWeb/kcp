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

    public $invoiceAop;

    public static function getIntransitBySpb($spb, $invoiceAop)
    {
        $kcpInformation = new KcpInformation;

        $login = $kcpInformation->login();

        if ($login) {
            $token = $login['token'];
        }

        $intransitStock = $kcpInformation->getIntransitBySpb($token, $spb);

        $details = DB::table('invoice_aop_detail')
            ->where('invoiceAop', $invoiceAop)
            ->get();

        $dataIntransit = $intransitStock['data'];

        $materialNumbers = $details->pluck('materialNumber')->toArray();

        $filteredDataIntransit = array_filter($dataIntransit, function ($item) use ($materialNumbers) {
            return in_array($item['part_no'], $materialNumbers);
        });

        $groupedDataIntransit = array_reduce($filteredDataIntransit, function ($carry, $item) {
            $partNo = $item['part_no'];

            if (!isset($carry[$partNo])) {
                $carry[$partNo] = [
                    'part_no' => $partNo,
                    'qty_terima' => 0
                ];
            }

            $carry[$partNo]['qty_terima'] += $item['qty_terima']; // Jumlahkan qty

            return $carry;
        }, []);

        foreach ($details as $detail) {
            if (isset($groupedDataIntransit[$detail->materialNumber])) {
                $detail->qty_terima = $groupedDataIntransit[$detail->materialNumber]['qty_terima'];
            } else {
                $detail->qty_terima = 0;
            }
        }

        $totalQtyTerima = 0;
        foreach ($details as $detail) {
            $totalQtyTerima += $detail->qty_terima;
        }

       return $totalQtyTerima;
    }

    public function render()
    {
        $invoiceAopHeader = DB::table('invoice_aop_header')
            ->select(['*'])
            ->where('invoiceAop', 'like', '%' . $this->invoiceAop . '%')
            ->where('flag_selesai', 'Y')
            ->where('status', 'BOSNET')
            ->orderBy('billingDocumentDate', 'asc')
            ->paginate(20);

        return view('livewire.aop-gr', compact('invoiceAopHeader'));
    }
}
