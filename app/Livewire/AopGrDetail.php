<?php

namespace App\Livewire;

use App\Models\KcpInformation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AopGrDetail extends Component
{
    public $spb;

    public function mount($spb)
    {
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
        $invoiceDetails = DB::table('invoice_aop_detail')
            ->select(['*'])
            ->where('SPB', $this->spb)
            ->whereIn('materialNumber', $this->selectedItems)
            ->get()
            ->groupBy('invoiceAop');


        $dataToSent = [];
        foreach ($invoiceDetails as $invoiceAop => $details) {
            $invoiceHeader = DB::table('invoice_aop_header')
                ->select(['*'])
                ->where('invoiceAop', $invoiceAop)
                ->first();

            // PAYMENT TERM ID
            $billingDate = Carbon::parse($invoiceHeader->billingDocumentDate);
            $dueDate = Carbon::parse($invoiceHeader->tanggalJatuhTempo);

            $paymentTermId = $billingDate->diffInDays($dueDate);

            $items = [];
            foreach ($details as $value) {
                $item['szProductId']           = $value->materialNumber;
                $item['decQty']                = $value->qty;
                $item['szUomId']               = "PCS";
                $item['decPrize']              = $value->price;
                $item['decDiscount']           = $value->extraPlafonDiscount;
                $item['purchaseITemTypeId']    = "BELI";

                $items[] = $item;
            }

            $dataToSent[] = [
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

        dd($dataToSent);
    }

    public function render()
    {
        $details = DB::table('invoice_aop_detail')
            ->where('SPB', $this->spb)
            ->get();

        $this->details = $details;

        $grouped = [];

        foreach ($details as $detail) {
            $key = $detail->materialNumber;

            $header = DB::table('invoice_aop_header as h')
                ->join('invoice_aop_detail as d', 'h.invoiceAop', '=', 'd.invoiceAop')
                ->where('h.SPB', $detail->SPB)
                ->where('d.materialNumber', $key)
                ->select('h.*')
                ->get();
            
            $status = 'BOSNET';
            foreach ($header as $value) {
                if ($value->status == 'KCP') {
                    $status = 'KCP';
                }
            }

            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'materialNumber'    => $detail->materialNumber,
                    'total_qty'         => 0,
                    'status'            => $status,
                    'invoices'          => []
                ];
            }

            $grouped[$key]['total_qty'] += $detail->qty;

            $grouped[$key]['invoices'][$detail->invoiceAop] =
                isset($grouped[$key]['invoices'][$detail->invoiceAop])
                ? $grouped[$key]['invoices'][$detail->invoiceAop] + $detail->qty
                : $detail->qty;
        }

        $finalResult = array_values($grouped);

        $dataIntransit = $this->getIntransitBySpb($this->spb);

        if (isset($dataIntransit['data'])) {
            $dataIntransit = $dataIntransit['data'];

            $qtyTerimaByPartNo = array_reduce($dataIntransit, function ($carry, $item) {
                $partNo = $item['part_no'];
                $qtyTerima = (int)$item['qty_terima'];

                if (!isset($carry[$partNo])) {
                    $carry[$partNo] = 0;
                }

                $carry[$partNo] += $qtyTerima;

                return $carry;
            }, []);

            foreach ($finalResult as &$item) {
                $materialNumber = $item['materialNumber'];

                $item['qty_terima'] = 0;

                if (isset($qtyTerimaByPartNo[$materialNumber])) {
                    $item['qty_terima'] = $qtyTerimaByPartNo[$materialNumber];
                }
            }
        } else {
            foreach ($finalResult as &$item) {
                $item['qty_terima'] = 0;
            }
        }

        return view('livewire.aop-gr-detail', compact('finalResult'));
    }
}
