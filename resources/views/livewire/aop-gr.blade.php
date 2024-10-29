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
                                        <th>Invoice</th>
                                        <th>SPB</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="2" class="text-center">No Data</td>
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Invoice</th>
                                        <th>SPB</th>
                                        <th>Total Qty</th>
                                        <th>Total Terima</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoiceAopHeader as $header)
                                        <tr>
                                            <td>{{ $header->invoiceAop }}</td>
                                            <td>{{ $header->SPB }}</td>
                                            <td>{{ $header->qty }}</td>
                                            <td>
                                                @php
                                                    $qtyTerima = App\Livewire\AopGr::getIntransitBySpb(
                                                        $header->SPB,
                                                        $header->invoiceAop,
                                                    );

                                                    echo $qtyTerima;
                                                @endphp
                                            </td>
                                            <td>
                                                <a href="{{ route('aop-gr.detail', ['invoiceAop' => $header->invoiceAop, 'spb' => $header->SPB]) }}"
                                                    class="btn btn-sm btn-primary">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
                <div class="card-footer">
                    {{ $invoiceAopHeader->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
