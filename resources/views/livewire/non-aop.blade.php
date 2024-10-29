<div>
    @if (session('status'))
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <b>Data Non AOP</b>
                        </div>
                        <div class="col d-flex justify-content-end">
                            <a href="{{ route('non-aop.create') }}" class="btn btn-primary">
                                Tambah
                            </a>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="card-body">
                    <div class="row mb-3" wire:loading.class="d-none" wire:target="gotoPage">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Invoice Non</label>
                            <input type="text" class="form-control" wire:model.live.debounce.1000ms="invoiceNon"
                                placeholder="Invoice Non" wire:loading.attr="disabled">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Jatuh Tempo</label>
                            <input type="date" class="form-control" wire:model.change="tanggalJatuhTempo"
                                wire:loading.attr="disabled">
                        </div>
                    </div>

                    <div wire:loading.flex wire:target="gotoPage, invoiceNon, tanggalJatuhTempo"
                        class="text-center justify-content-center align-items-center" style="height: 200px;">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    @if ($invoiceNonAopHeader->isEmpty())
                        <div class="table-responsive" wire:loading.class="d-none"
                            wire:target="gotoPage, invoiceNon, tanggalJatuhTempo">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Invoice Non AOP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">No Data</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="table-responsive" wire:loading.class="d-none"
                            wire:target="gotoPage, invoiceNon, tanggalJatuhTempo">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Invoice Non AOP</th>
                                        <th>Customer To</th>
                                        <th>Supplier</th>
                                        <th>Tanggal Jatuh Tempo</th>
                                        <th>Total Harga</th>
                                        <th>Total Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoiceNonAopHeader as $value)
                                        <tr>
                                            <td>{{ $value->invoiceNon }}</td>
                                            <td>{{ $value->customerTo }}</td>
                                            <td>{{ $value->supplierCode }}</td>
                                            <td>{{ date('d-m-Y', strtotime($value->tanggalJatuhTempo)) }}</td>
                                            <td>{{ number_format($value->price, 0, ',', '.') }}</td>
                                            <td>{{ number_format($value->amount, 0, ',', '.') }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <span class="badge text-bg-primary" style="cursor: pointer"
                                                        wire:click="detailInvoiceNon('{{ $value->invoiceNon }}')">Detail</span>
                                                    <span wire:click="hapusInvoiceNon('{{ $value->invoiceNon }}')"
                                                        class="badge text-bg-danger"
                                                        wire:confirm="Yakin ingin hapus invoice: {{ $value->invoiceNon }}?"
                                                        style="cursor: pointer">
                                                        Hapus
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="card-footer" wire:loading.class="d-none"
                    wire:target="gotoPage, invoiceNon, tanggalJatuhTempo">
                    {{ $invoiceNonAopHeader->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
