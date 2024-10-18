<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DksController extends Controller
{
    public function guard()
    {
        // ADMIN,SALESMAN,HEAD-MARKETING
        if (Auth::user()->role != 'ADMIN' && Auth::user()->role != 'SALESMAN') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
    }

    public function index($kd_toko = null)
    {
        $this->guard();

        if ($kd_toko) {
            $kd_toko = base64_decode($kd_toko);

            $toko = DB::table('master_toko')
                ->select([
                    'kd_toko',
                    'nama_toko',
                    'latitude',
                    'longitude'
                ])
                ->where('kd_toko', $kd_toko)
                ->first();

            // JIKA TOKO PUNYA TOKO KEDUA DENGAN KODE YANG SAMA
            // LIST TOKO YANG PUNYA DUA TOKO => TQ.
            $tokoKedua = [];
            if ($kd_toko == 'TQ') {
                $tokoKedua = DB::table('master_toko')
                    ->select([
                        'kd_toko',
                        'nama_toko',
                        'latitude',
                        'longitude'
                    ])
                    ->where('kd_toko', 'like', "%$kd_toko%")
                    ->where('kd_toko', '!=', $kd_toko)
                    ->first();
            }

            if ($toko) {
                return view('dks.submit', compact('toko', 'tokoKedua'));
            } else {
                return redirect()->route('dks.scan')->with('error', "Toko dengan kode $kd_toko tidak ditemukan.");
            }
        } else {
            return view('dks.index');
        }
    }

    public function store(Request $request, $kd_toko)
    {
        $this->guard();

        /**
         * VALIDASI LATITUDE DAN LONGITUDE
         * VALIDASI MAX 2X SCAN PER TOKO DALAM SATU HARI
         * VALIDASI JIKA BELUM CHECKIN DI TOKO TERSEBUT HARI INI MAKA TYPE = IN, 
         * VALIDASI JIKA SUDAH CHECKIN DI TOKO TERSEBUT HARI INI MAKA TYPE = OUT
         * VALIDASI JIKA KETERANGAN = 'IST/ist' MAKA TYPE = OUT
         * VALIDASI TOKO YANG AKTIF
         * VALIDASI CEK OUT 
         */

        // DATA USER
        $latitude   = $request->latitude;
        $longitude  = $request->longitude;
        $keterangan = strtolower($request->keterangan);
        $user       = Auth::user()->username;

        // JARAK ANTARA USER DENGAN TOKO DALAM METER
        $distance = $request->distance;

        // VALIDASI JARAK ANTARA USER DENGAN TOKO
        if ($distance > 50) {
            return redirect()->back()->with('error', 'Anda berada di luar radius toko!');
        }

        // VALIDASI CHECK IN / CHECK OUT
        $type = '';

        $check = DB::table('trns_dks')
            ->where('kd_toko', $kd_toko)
            ->where('user_sales', $user)
            ->whereDate('tgl_kunjungan', '=', now()->toDateString())
            ->count();

        if ($check == 0) {
            $type = 'in';
        } else if ($check == 2) {
            return redirect()->back()->with('error', 'Anda sudah melakukan check out!');
        } else if ($keterangan == 'ist') {
            $type = 'out';
        } else {
            $type = 'out';
        }

        $provinsiToko = DB::table('master_toko')
            ->select(['*'])
            ->where('kd_toko', $kd_toko)
            ->where('status', 'active')
            ->first();

        if ($provinsiToko == null) {
            return back()->with('error', "Toko dengan kode $kd_toko tidak aktif!");
        }

        if ($provinsiToko->kd_provinsi == 2) {
            $waktu_kunjungan = now()->modify('-1 hour');
        } else {
            $waktu_kunjungan = now();
        }

        // Ambil semua cek in aktif untuk user pada hari ini
        // $cekInAktif = DB::table('trns_dks as in_data')
        //     ->select(['in_data.kd_toko'])
        //     ->where('in_data.user_sales', $user)
        //     ->where('in_data.type', 'in')
        //     ->whereDate('in_data.tgl_kunjungan', '=', now()->toDateString())
        //     ->leftJoin('trns_dks as out_data', function ($join) {
        //         $join->on('in_data.user_sales', '=', 'out_data.user_sales')
        //             ->whereColumn('out_data.kd_toko', 'in_data.kd_toko')
        //             ->where('out_data.type', '=', 'out')
        //             ->where('out_data.created_at', '>', 'in_data.created_at'); 
        //     })
        //     ->whereNull('out_data.id') 
        //     ->pluck('in_data.kd_toko');

        // // Jika yang di cek out adalah toko lain
        // if ($kd_toko != $cekInAktif->last()) {
        //     return redirect()->back()->with('error', 'Tidak dapat melakukan check out di toko sebelumnya!');
        // }

        if ($latitude && $longitude) {
            DB::table('trns_dks')
                ->insert(
                    [
                        'tgl_kunjungan'     => now(),
                        'user_sales'        => $user,
                        'kd_toko'           => $kd_toko,
                        'waktu_kunjungan'   => $waktu_kunjungan,
                        'type'              => $type,
                        'latitude'          => $latitude,
                        'longitude'         => $longitude,
                        'keterangan'        => $keterangan,
                        'created_by'        => $user,
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ]
                );

            return redirect()->route('dks.scan')->with('success', "Berhasil melakukan check $type");
        } else {
            return redirect()->back()->with('error', 'Lokasi tidak ditemukan!');
        }
    }
}
