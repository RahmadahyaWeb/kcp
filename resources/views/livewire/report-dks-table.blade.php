@php
    $days = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu',
    ];
@endphp

<div>
    <div class="mb-3">
        <form action="{{ route('report-dks.export') }}" method="POST">
            @csrf
            <div class="row g-2">
                <div class="col-md-6">
                    <label for="fromDate" class="form-label">Dari tanggal</label>
                    <input id="fromDate" type="date" class="form-control @error('fromDate') is-invalid @enderror"
                        wire:model.live="fromDate" name="fromDate">
                    @error('fromDate')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="toDate" class="form-label">Sampai tanggal</label>
                    <input id="toDate" type="date" class="form-control @error('toDate') is-invalid @enderror"
                        wire:model.live="toDate" name="toDate">
                    @error('toDate')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="user_sales" class="form-label">Sales</label>
                    <select name="user_sales" id="user_sales" class="form-select" wire:model.change="user_sales">
                        <option value="" selected>Pilih Sales</option>
                        @foreach ($sales as $user)
                            <option value="{{ $user->username }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="kd_toko" class="form-label">Nama Toko</label>
                    <select name="kd_toko" id="kd_toko" class="form-select" wire:model.change="kd_toko">
                        <option value="" selected>Pilih Toko</option>
                        @foreach ($dataToko as $toko)
                            <option value="{{ $toko->kd_toko }}">{{ $toko->nama_toko }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">
                        <i class="bx bxs-download me-2"></i>
                        Unduh
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div wire:loading.block wire:target="fromDate, toDate, user_sales, kd_toko" class="text-center">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="my-2">
            Loading
        </div>
    </div>

    <div wire:loading.class="d-none" class="table-responsive">
        <table class="table table-hover table-bordered table-sm">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Tgl. Kunjungan</th>
                    <th>Toko</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Lama Kunjungan</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @if ($items->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center">No data</td>
                    </tr>
                @else
                    @foreach ($items as $item)
                        @php
                            $carbonDate = \Carbon\Carbon::parse($item->tgl_kunjungan);
                            $formattedDate = $days[$carbonDate->format('l')] . ', ' . $carbonDate->format('d-m-Y');
                        @endphp
                        <tr>
                            <td>{{ $item->user_sales }}</td>
                            <td>{{ $formattedDate }}</td>
                            <td>{{ $item->nama_toko }}</td>
                            <td>{{ date('H:i:s', strtotime($item->waktu_cek_in)) }}</td>
                            <td>
                                @if ($item->waktu_cek_out)
                                    {{ date('H:i:s', strtotime($item->waktu_cek_out)) }}
                                @else
                                    Belum check out
                                @endif
                            </td>
                            <td>
                                @if ($item->lama_kunjungan != null)
                                    {{ $item->lama_kunjungan }} menit
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $item->keterangan }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $items->links() }}
    </div>
</div>
