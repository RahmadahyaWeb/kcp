<div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <b>Data AOP</b>
                        </div>
                        {{-- <div class="col d-flex justify-content-end">
                            <button wire:click="processSelectedInvoices" class="btn btn-warning"
                                {{ count($selectedInvoices) == 0 ? 'disabled' : '' }}>
                                Kirim ke Bosnet <span
                                    class="badge text-bg-danger ms-3">{{ count($selectedInvoices) }}</span>
                            </button>
                        </div> --}}
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="invoiceAop">Invoice AOP</label>
                            <input type="text" class="form-control" wire:model.live="invoiceAop">
                        </div>
                    </div>

                    <div wire:loading.block wire:target="save, gotoPage" class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="my-2">
                            Loading
                        </div>
                    </div>

                    @if ($invoiceAopHeader->isEmpty())
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Invoice AOP</th>
                                        <th>Customer To</th>
                                        <th>Billing Document Date</th>
                                        <th>Tgl. Jatuh Tempo</th>
                                        <th>Harga (Rp)</th>
                                        <th>Add Discount (Rp)</th>
                                        <th>Amount (Rp)</th>
                                        <th>Cash Discount (Rp)</th>
                                        <th>Extra Plafon Discount (Rp)</th>
                                        <th>Net Sales (Rp)</th>
                                        <th>Tax (Rp)</th>
                                        <th>Grand Total (Rp)</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center" colspan="14">No Data</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div wire:loading.class="d-none" wire:target="save, gotoPage" class="table-responsive">
                            <table class="table table-bordered table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Invoice AOP</th>
                                        <th>Customer To</th>
                                        <th>Billing Document Date</th>
                                        <th>Tgl. Jatuh Tempo</th>
                                        <th>Harga (Rp)</th>
                                        <th>Add Discount (Rp)</th>
                                        <th>Amount (Rp)</th>
                                        <th>Cash Discount (Rp)</th>
                                        <th>Extra Plafon Discount (Rp)</th>
                                        <th>Net Sales (Rp)</th>
                                        <th>Tax (Rp)</th>
                                        <th>Grand Total (Rp)</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoiceAopHeader as $invoice)
                                        <tr>
                                            <td>
                                                <input type="checkbox" wire:model.live="selectedInvoices"
                                                    value="{{ $invoice->invoiceAop }}" />
                                            </td>
                                            <td>
                                                <a href="{{ route('aop-upload.detail', $invoice->invoiceAop) }}">
                                                    {{ $invoice->invoiceAop }}
                                                </a>
                                            </td>
                                            <td>{{ $invoice->customerTo }}</td>
                                            <td>{{ $invoice->billingDocumentDate }}</td>
                                            <td>{{ $invoice->tanggalJatuhTempo }}</td>
                                            <td>{{ number_format($invoice->price, 0, ',', '.') }}</td>
                                            <td>{{ number_format($invoice->addDiscount, 0, ',', '.') }}</td>
                                            <td>{{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                            <td>{{ number_format($invoice->cashDiscount, 0, ',', '.') }}</td>
                                            <td>{{ number_format($invoice->extraPlafonDiscount, 0, ',', '.') }}</td>
                                            <td>{{ number_format($invoice->netSales, 0, ',', '.') }}</td>
                                            <td>{{ number_format($invoice->tax, 0, ',', '.') }}</td>
                                            <td>{{ number_format($invoice->grandTotal, 0, ',', '.') }}</td>
                                            <td>
                                                <a href="{{ route('aop-upload.detail', $invoice->invoiceAop) }}">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div wire:loading.class="d-none" wire:target="save" class="card-footer">
                    {{ $invoiceAopHeader->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
