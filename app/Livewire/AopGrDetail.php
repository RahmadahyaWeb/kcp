<?php

namespace App\Livewire;

use App\Models\KcpInformation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AopGrDetail extends Component
{
    public $invoiceAop;
    public $spb;

    public function mount($invoiceAop, $spb)
    {
        $this->invoiceAop = $invoiceAop;
        $this->spb = $spb;
    }

    public function getIntransitBySpb($spb)
    {
        $kcpInformation = new KcpInformation;

        $login = $kcpInformation->login();

        if ($login) {
            $token = $login['token'];
        }

        $intransitStock = $kcpInformation->getIntransitBySpb($token, $spb);

        if ($intransitStock) {
            return $intransitStock;
        }
    }

    public $selectedItems = [];
    public $details = [];
    public $selectAll = false;

    public function updatedSelectedItems($value)
    {
        $selectedItems[] = $value;
        $this->selectAll = false;
    }

    public function toggleSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedItems = collect($this->details)->pluck('materialNumber')->toArray();
        } else {
            $this->selectedItems = [];
        }
    }

    public function sendToBosnet()
    {
        dd($this->sendToBosnetAPI($this->invoiceAop));
    }

    public function sendToBosnetAPI($invoiceAop)
    {
        $invoiceHeader = DB::table('invoice_aop_header')
            ->select(['*'])
            ->where('invoiceAop', $invoiceAop)
            ->first();

        $invoiceDetails = DB::table('invoice_aop_detail')
            ->select(['*'])
            ->where('invoiceAop', $invoiceAop)
            ->whereIn('materialNumber', $this->selectedItems) // $materialNumbers harus berupa array
            ->get();

        // ITEMS
        $items = [];
        foreach ($invoiceDetails as $value) {
            $item = [];
            $item['szProductId']           = $value->materialNumber;
            $item['decQty']                = $value->qty;
            $item['szUomId']               = "PCS";
            $item['decPrize']              = $value->price;
            $item['decDiscount']           = $value->extraPlafonDiscount;
            $item['purchaseITemTypeId']    = "BELI";

            $items[] = $item;
        }

        // PAYMENT TERM ID
        $billingDate = Carbon::parse($invoiceHeader->billingDocumentDate);
        $dueDate = Carbon::parse($invoiceHeader->tanggalJatuhTempo);

        $paymentTermId = $billingDate->diffInDays($dueDate);

        return [
            'szFpoId'                   => $invoiceHeader->invoiceAop,
            'dtmPO'                     => date('Y-m-d H:i:s', strtotime($invoiceHeader->billingDocumentDate)),
            'dtmReceipt'                => "",
            'bReturn'                   => 0,
            'szRefDn'                   => $invoiceHeader->SPB,
            'szWarehouseId'             => "",
            'szStockTypeId'             => "Good Stock",
            'szSupplierId'              => "",
            'paymentTermId'             => $paymentTermId . " HARI",
            'szPOReceiptIdForReturn'    => "",
            'szWorkplaceId'             => "",
            'szCarrierId'               => "",
            'szVehicleId'               => "",
            'szDriverId'                => "",
            'szVehicleNumber'           => "",
            'szDriverNm'                => "",
            'szDescription'             => "",
            'items'                     => $items
        ];
    }

    public function render()
    {
        $details = DB::table('invoice_aop_detail')
            ->where('invoiceAop', $this->invoiceAop)
            ->get();

        $dataIntransit = $this->getIntransitBySpb($this->spb);

        $dataIntransit = $dataIntransit['data'];

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

        $this->details = $details;

        return view('livewire.aop-gr-detail', compact('details', 'groupedDataIntransit'));
    }
}
