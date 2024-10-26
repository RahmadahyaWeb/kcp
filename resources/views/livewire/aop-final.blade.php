<div>
    <div class="card">
        <div class="card-header">
            Data AOP Final
            <hr>
        </div>
        <div class="card-body">
            <div class="row mb-3" wire:loading.class="d-none" wire:target="save, gotoPage">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Invoice AOP</label>
                    <input type="text" class="form-control" wire:model.live.debounce.1000ms="invoiceAop"
                        placeholder="Invoice AOP" wire:loading.attr="disabled">
                </div>
            </div>

            <div wire:loading.flex wire:target="save, gotoPage, invoiceAop"
                class="text-center justify-content-center align-items-center" style="height: 200px;">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            @if ($invoiceAopHeader->isEmpty())
                <div wire:loading.class="d-none" wire:target="save, gotoPage, invoiceAop"
                    class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
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
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center" colspan="12">No Data</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <div wire:loading.class="d-none" wire:target="save, gotoPage, invoiceAop"
                    class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoiceAopHeader as $invoice)
                                <tr>
                                    <td>
                                        <a href="{{ route('aop-upload.detail', $invoice->invoiceAop) }}">
                                            {{ $invoice->invoiceAop }}
                                        </a>
                                    </td>
                                    <td>{{ $invoice->customerTo }}</td>
                                    <td>{{ date('d-m-Y', strtotime($invoice->billingDocumentDate)) }}
                                    </td>
                                    <td>{{ date('d-m-Y', strtotime($invoice->tanggalJatuhTempo)) }}
                                    </td>
                                    <td>{{ number_format($invoice->price, 0, ',', '.') }}</td>
                                    <td>{{ number_format($invoice->addDiscount, 0, ',', '.') }}
                                    </td>
                                    <td>{{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                    <td>{{ number_format($invoice->cashDiscount, 0, ',', '.') }}
                                    </td>
                                    <td>{{ number_format($invoice->extraPlafonDiscount, 0, ',', '.') }}
                                    </td>
                                    <td>{{ number_format($invoice->netSales, 0, ',', '.') }}
                                    </td>
                                    <td>{{ number_format($invoice->tax, 0, ',', '.') }}</td>
                                    <td>{{ number_format($invoice->grandTotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        <div wire:loading.class="d-none" wire:target="save, invoiceAop" class="card-footer">
            {{ $invoiceAopHeader->links() }}
        </div>
    </div>
</div>
