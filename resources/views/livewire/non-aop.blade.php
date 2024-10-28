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
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Invoice Non AOP</th>
                                    <th>Customer To</th>
                                    <th>Supplier</th>
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
                                        <td>{{ $value->price }}</td>
                                        <td>{{ $value->amount }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <span class="text-primary" style="cursor: pointer">
                                                    <i class='bx bxs-detail'></i>
                                                </span>
                                                <span wire:click="hapusInvoiceNon('{{ $value->invoiceNon }}')"
                                                    class="text-danger"
                                                    wire:confirm="Yakin ingin hapus invoice: {{ $value->invoiceNon }}?"
                                                    style="cursor: pointer">
                                                    <i class='bx bxs-trash'></i>
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $invoiceNonAopHeader->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
