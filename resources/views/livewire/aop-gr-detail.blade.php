<div>
    <div class="card">
        <div class="card-header">
            Detail Good Receipt: <b>{{ $invoiceAop }}</b>
            <hr>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-3">
                <button class="btn btn-warning" wire:click="sendToBosnet">Kirim ke Bosnet</button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" wire:model="selectAll" wire:click="toggleSelectAll">
                            </th>
                            <th>SPB</th>
                            <th>Part No</th>
                            <th>Qty Invoice</th>
                            <th>Qty Terima</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($details as $item)
                            <tr>
                                <td>
                                    <input type="checkbox" wire:model.change="selectedItems"
                                        value="{{ $item->materialNumber }}">
                                </td>
                                <td>{{ $item->SPB }}</td>
                                <td>{{ $item->materialNumber }}</td>
                                <td>{{ $item->qty }}</td>
                                <td>{{ $item->qty_terima }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
