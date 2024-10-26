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
                <div wire:loading.class="d-none" wire:target="save, gotoPage, invoiceAop" class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Invoice AOP</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center" colspan="2">No Data</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <div wire:loading.class="d-none" wire:target="save, gotoPage, invoiceAop" class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Invoice AOP</th>
                                <th>Status</th>
                                <th>Action</th>
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
                                    <td>
                                        @if ($invoice->flag_selesai == 'Y')
                                            <span class="badge text-bg-success">Ready to be sent</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button wire:click="cancel({{ $invoice->invoiceAop }})"
                                            wire:confirm="Yakin ingin batal invoice?" type="submit"
                                            class="btn btn-sm btn-danger">
                                            Batal
                                        </button>
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
