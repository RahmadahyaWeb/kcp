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
                        @if (empty($items))
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
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr>
                                            <td>{{ $item['spb'] }}</td>
                                            <td>
                                                {!! implode('<br>', $item['invoices']) !!}
                                            </td>
                                            <td>{{ $item['totalQty'] }}</td>
                                            <td>{{ $item['totalQtyTerima'] }}</td>
                                            <td>
                                                @if ($item['totalQty'] == $item['totalQtyTerima'])
                                                    <span class="badge text-bg-success">Lengkap</span>
                                                @else
                                                    <span class="badge text-bg-danger">Belum Lengkap</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('aop-gr.detail', $item['spb']) }}"
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
