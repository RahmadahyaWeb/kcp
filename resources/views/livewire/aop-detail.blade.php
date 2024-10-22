<div>
    <div class="row">
        {{-- CARD DETAIL --}}
        <div class="col-12 mb-3">
            <div class="card">
                <div class="card-header">
                    Detail Invoice Astra Otoparts (AOP): <b>{{ $header->invoiceAop }}</b>
                    <hr>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col col-4 col-md-4">
                                    <div>Invoice AOP</div>
                                </div>
                                <div class="col col-auto">
                                    :
                                </div>
                                <div class="col col-auto">
                                    <div>{{ $header->invoiceAop }}</div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col col-4 col-md-4">
                                    <div>Customer To</div>
                                </div>
                                <div class="col col-auto">
                                    :
                                </div>
                                <div class="col col-auto">
                                    <div>{{ $header->customerTo }}</div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col col-4 col-md-4">
                                    <div>No. SPB</div>
                                </div>
                                <div class="col col-auto">
                                    :
                                </div>
                                <div class="col col-auto">
                                    <div>{{ $header->SPB }}</div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col col-4 col-md-4">
                                    <div>Billing Document Date</div>
                                </div>
                                <div class="col col-auto">
                                    :
                                </div>
                                <div class="col col-auto">
                                    <div>
                                        @if ($header->billingDocumentDate != null)
                                            {{ $header->billingDocumentDate }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col col-4 col-md-4">
                                    <div>Tgl. Cetak Faktur</div>
                                </div>
                                <div class="col col-auto">
                                    :
                                </div>
                                <div class="col col-auto">
                                    <div>
                                        @if ($header->tanggalCetakFaktur != null)
                                            {{ $header->tanggalCetakFaktur }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col col-4 col-md-4">
                                    <div>Tgl. Jatuh Tempo</div>
                                </div>
                                <div class="col col-auto">
                                    :
                                </div>
                                <div class="col col-auto">
                                    <div>
                                        @if ($header->tanggalJatuhTempo != null)
                                            {{ $header->tanggalJatuhTempo }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col col-4 col-md-4">
                                    <div>Faktur Pajak</div>
                                </div>
                                <div class="col col-auto">
                                    :
                                </div>
                                <div class="col col-auto">
                                    <div>
                                        @if ($header->fakturPajak != null)
                                            <div class="d-inline text-primary" style="cursor: pointer"
                                                data-bs-toggle="modal" wire:click="openModal">
                                                {{ $header->fakturPajak }}
                                            </div>
                                        @else
                                            <div class="d-inline text-primary" style="cursor: pointer"
                                                data-bs-toggle="modal" wire:click="openModal">
                                                Belum ada
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col col-4 col-md-4">
                                    <div>Harga</div>
                                </div>
                                <div class="col col-auto">
                                    :
                                </div>
                                <div class="col col-auto">
                                    <div>Rp {{ number_format($header->price, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col col-4 col-md-4">
                                    <div>Additional Discount</div>
                                </div>
                                <div class="col col-auto">
                                    :
                                </div>
                                <div class="col col-auto">
                                    <div>Rp {{ number_format($header->addDiscount, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col col-4 col-md-4">
                                    <div>Extra Plafon Discount</div>
                                </div>
                                <div class="col col-auto">
                                    :
                                </div>
                                <div class="col col-auto">
                                    <div>Rp {{ number_format($header->extraPlafonDiscount, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col col-4 col-md-4">
                                    <div>Cash Discount</div>
                                </div>
                                <div class="col col-auto">
                                    :
                                </div>
                                <div class="col col-auto">
                                    <div>Rp {{ number_format($header->cashDiscount, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col col-4 col-md-4">
                                    <div>Net Sales</div>
                                </div>
                                <div class="col col-auto">
                                    :
                                </div>
                                <div class="col col-auto">
                                    <div>Rp {{ number_format($header->netSales, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col col-4 col-md-4">
                                    <div>Tax</div>
                                </div>
                                <div class="col col-auto">
                                    :
                                </div>
                                <div class="col col-auto">
                                    <div>Rp {{ number_format($header->tax, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col col-4 col-md-4">
                                    <div>Grand Total</div>
                                </div>
                                <div class="col col-auto">
                                    :
                                </div>
                                <div class="col col-auto">
                                    <div>Rp {{ number_format($header->grandTotal, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL EDIT FAKTUR PAJAK --}}
        <div class="modal fade" id="editFakturPajakModal" tabindex="-1" aria-labelledby="editFakturPajakModalLabel"
            aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editFakturPajakModalLabel">
                            Edit Faktur Pajak
                        </h1>
                    </div>
                    <div class="modal-body">
                        <form wire:submit="saveFakturPajak">
                            <label for="form-label">Faktur Pajak</label>
                            <input type="text" class="form-control" wire:model="fakturPajak">
                            <div class="d-flex justify-content-end mt-2 gap-2">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD EXTRA PLAFON DISCOUNT  --}}
        <div class="col-12 mb-3">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            Extra Plafon Discount (Disc Program)
                        </div>
                        <div class="col d-flex justify-content-end">
                            <button class="btn btn-primary">Tambah Program</button>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Discount</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>0</td>
                                <td>Disc Program</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- CARD DETAIL PART --}}
        <div class="col-12 mb-3">
            <div class="card">
                <div class="card-header">
                    Detail Material Astra Otoparts (AOP): <b>{{ $header->invoiceAop }}</b>
                    <hr>
                </div>
                <div class="card-body">
                    <table class="table table-bordered mb-3">
                        <tr>
                            <th>Total Qty</th>
                            <td><b>{{ $totalQty }}</b></td>
                        </tr>
                        <tr>
                            <th>
                                Total Amount
                            </th>
                            <td>
                                <b>Rp {{ number_format($totalAmount, 0, ',', '.') }}</b>
                            </td>
                        </tr>
                    </table>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Material Number</th>
                                    <th>Qty</th>
                                    <th>Amount (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($details as $item)
                                    <tr>
                                        <td>{{ $item->materialNumber }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>{{ number_format($item->amount, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
