<div>
    <div class="row mb-3">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <b>Data Good Receipt AOP</b>
                    <hr>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        @if ($invoiceAopHeader->isEmpty())
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>SPB</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="1" class="text-center">No Data</td>
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>SPB</th>
                                        <th>Invoice</th>
                                        <th>Qty Invoice</th>
                                        <th>Qty Terima</th>
                                        <th>Status Qty</th>
                                        <th>Status Data</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoiceAopHeader as $header)
                                        <tr>
                                            <td>{{ $header->SPB }}</td>
                                            <td>
                                                @php
                                                    $invoices = App\Livewire\AopGr::getInvoices($header->SPB);

                                                    $status = 0;
                                                    $statusBosnet = 0;
                                                    $statusKcp = 0;

                                                    $invoiceArray = [];
                                                    foreach ($invoices as $invoice) {
                                                        $invoiceArray[] = $invoice->invoiceAop;

                                                        if ($invoice->status == 'KCP') {
                                                            $statusKcp += 1;
                                                        }

                                                        if ($invoice->status == 'BOSNET') {
                                                            $statusBosnet += 1;
                                                        }
                                                    }

                                                    $invoiceTxt = implode('<br>', $invoiceArray);

                                                    echo $invoiceTxt;
                                                @endphp
                                            </td>
                                            <td>
                                                @php
                                                    $totalQty = App\Livewire\AopGr::getTotalQty($header->SPB);

                                                    echo $totalQty;
                                                @endphp
                                            </td>
                                            <td>
                                                @php
                                                    $totalQtyTerima = App\Livewire\AopGr::getIntransitBySpb(
                                                        $header->SPB,
                                                    );

                                                    echo $totalQtyTerima;
                                                @endphp
                                            </td>
                                            <td>
                                                @if ($totalQty == $totalQtyTerima)
                                                    <span class="badge text-bg-success">Lengkap</span>
                                                @else
                                                    <span class="badge text-bg-danger">Belum Lengkap</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($statusKcp == count($invoiceArray))
                                                    KCP
                                                @elseif ($statusBosnet == count($invoiceArray))
                                                    BOSNET
                                                @else
                                                    Beberapa invoice telah dikirim ke Bosnet
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('aop-gr.detail', $header->SPB) }}"
                                                    class="btn btn-sm btn-primary">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
                {{-- <div class="card-footer">
                    {{ $invoiceAopHeader->links() }}
                </div> --}}
            </div>
        </div>
    </div>
</div>
