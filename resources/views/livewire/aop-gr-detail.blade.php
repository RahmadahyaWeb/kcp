<div>
    <div class="card">
        <div class="card-header">
            Detail Good Receipt: <b>{{$spb}}</b>
            <hr>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-3">
                <button class="btn btn-warning" wire:click="sendToBosnet" @disabled(count($selectedItems) < 1)>
                    Kirim ke Bosnet
                </button>                
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>
                                {{-- <input type="checkbox" wire:model="selectAll" wire:click="toggleSelectAll"> --}}
                            </th>
                            <th>Part No</th>
                            <th>Total Qty</th>
                            <th>Total Qty Terima</th>
                            <th>Data From</th>
                            <th>Status</th> 
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($finalResult as $item)
                            <tr>
                                    <td>
                                        <input type="checkbox" wire:model.change="selectedItems"
                                            value="{{ $item['materialNumber'] }}"
                                            @if ($item['total_qty'] != $item['qty_terima'] || $item['status'] == 'KCP') disabled @endif>
                                    </td>
                                <td>{{ $item['materialNumber'] }}</td>
                                <td>{{ $item['total_qty'] }}</td>
                                <td>{{ isset($item['qty_terima']) ? $item['qty_terima'] : 0 }}</td>
                                <td>
                                    @foreach ($item['invoices'] as $invoice => $qty)
                                        <div>
                                            <span>{{ $invoice }}: {{ $qty }}</span>
                                        </div>
                                    @endforeach
                                </td>
                                <td>
                                    @if ($item['total_qty'] == $item['qty_terima'])
                                        <span class="badge text-bg-success">Lengkap</span>
                                    @else
                                        <span class="badge text-bg-danger">Belum Lengkap</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
