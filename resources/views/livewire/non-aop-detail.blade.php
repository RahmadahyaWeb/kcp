<div>
    <div class="row">
        <div class="col-12 mb-3">
            <div class="card">
                <div class="card-header">
                    Detail Invoice Non AOP: <b>{{ $invoiceNon }}</b>
                    <hr>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col col-4 col-md-4">
                                    <div>Invoice Non</div>
                                </div>
                                <div class="col col-auto">
                                    :
                                </div>
                                <div class="col col-auto">
                                    <div>{{ $invoiceNon }}</div>
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
                                    <div>Supplier</div>
                                </div>
                                <div class="col col-auto">
                                    :
                                </div>
                                <div class="col col-auto">
                                    <div>{{ $header->supplierName }}</div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col col-4 col-md-4">
                                    <div>Tanggal Nota</div>
                                </div>
                                <div class="col col-auto">
                                    :
                                </div>
                                <div class="col col-auto">
                                    <div>{{ date('d-m-Y', strtotime($header->billingDocumentDate)) }}</div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col col-4 col-md-4">
                                    <div>Tanggal Jatuh Tempo</div>
                                </div>
                                <div class="col col-auto">
                                    :
                                </div>
                                <div class="col col-auto">
                                    <div>{{ date('d-m-Y', strtotime($header->tanggalJatuhTempo)) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mb-3">
            <div class="card">
                <div class="card-header">
                    <b>Form Tambah Item</b>
                    <hr>
                </div>
                <div class="card-body">
                    <form wire:submit="addItem">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">No Part | Nama Part</label>
                                <input type="text" class="form-control mb-2" wire:model.live="search"
                                    placeholder="Cari Part">
                                <select class="form-select @error('materialNumber') is-invalid @enderror"
                                    wire:model.live ="materialNumber">
                                    <option value="" selected>Pilih Part</option>
                                    @foreach ($nonAopParts as $part)
                                        <option value="{{ $part['part_no'] }}">{{ $part['txt'] }}</option>
                                    @endforeach
                                </select>

                                @error('materialNumber')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">QTY</label>
                                <input type="number" class="form-control @error('qty') is-invalid @enderror"
                                    wire:model.live="qty">

                                @error('qty')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Harga</label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror"
                                    wire:model.live="price">

                                @error('price')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Discount</label>
                                <input type="number" class="form-control" wire:model.live="extraPlafonDiscount">
                            </div>
                            <div class="col-12 mb-3 d-grid">
                                <button type="submit" class="btn btn-success">Tambah Item</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 mb-3">
            <div class="card">
                <div class="card-header">
                    <b>Detail Item</b>
                    <hr>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No Part | Nama Part</th>
                                    <th>Qty</th>
                                    <th>Harga</th>
                                    <th>Discount</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
