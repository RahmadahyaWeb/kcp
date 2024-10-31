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

    $tokoAbsen = ['6B', '6C', '6D', '6F', '6H', 'TX'];
@endphp

<div>
    <div class="table-responsive">
        <table class="table table-hover table-sm">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Tgl. Kunjungan</th>
                    <th>Toko</th>
                    <th>Check In</th>
                    <th>Katalog</th>
                    <th>Check Out</th>
                    <th>Lama Kunjungan</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @if ($items->isEmpty())
                    <tr>
                        <td colspan="8" class="text-center">No data</td>
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
                                @if ($item->katalog_at)
                                    {{ date('H:i:s', strtotime($item->katalog_at)) }}
                                @else
                                    Belum scan katalog
                                @endif
                            </td>
                            @if (in_array($item->kd_toko, $tokoAbsen))
                                <td>
                                    @if ($item->waktu_cek_out)
                                        {{ date('H:i:s', strtotime($item->waktu_cek_out)) }}
                                    @else
                                        Belum check out
                                    @endif
                                </td>
                                <td>-</td>
                                <td>Absen Toko</td>
                            @else
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
                            @endif
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
