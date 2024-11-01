<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DksController extends Controller
{
    public function guard()
    {
        $userRoles = explode(',', Auth::user()->role);

        $allowedRoles = ['ADMIN', 'SALESMAN'];

        if (empty(array_intersect($allowedRoles, $userRoles))) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
    }

    public function index(Request $request, $kd_toko = null)
    {
        $this->guard();

        if ($kd_toko) {
            $kd_toko = base64_decode($kd_toko);

            $toko = DB::table('master_toko')
                ->select([
                    'kd_toko',
                    'nama_toko',
                    'latitude',
                    'longitude',
                ])
                ->where('kd_toko', $kd_toko)
                ->first();

            $katalog = $request->get('katalog');

            if ($toko) {
                return view('dks.submit', compact('toko', 'katalog'));
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
        $katalog    = $request->get('katalog');

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
            ->where('type', '!=', 'katalog')
            ->whereDate('tgl_kunjungan', '=', now()->toDateString())
            ->count();

        if ($check == 0) {
            $type = 'in';

            if ($katalog[6] == 'Y') {
                return redirect()->back()->with('error', 'Tidak dapat scan katalog. Anda belum melakukan check in!');
            }
        } else if ($check == 2) {
            if ($katalog[6] == 'Y') {
                return redirect()->back()->with('error', 'Tidak dapat scan katalog. Anda sudah melakukan check out!');
            }

            return redirect()->back()->with('error', 'Anda sudah melakukan check out!');
        } else if ($keterangan == 'ist') {
            $type = 'out';

            if ($katalog[6] == 'Y') {
                return redirect()->back()->with('error', 'Anda sudah melakukan scan katalog!');
            }
        } else {
            $type = 'out';
        }

        // VALIDASI KATALOG
        if ($katalog[6] == 'Y') {
            $checkKatalog = DB::table('trns_dks')
                ->where('kd_toko', $kd_toko)
                ->where('user_sales', $user)
                ->where('type', '=', 'katalog')
                ->whereDate('tgl_kunjungan', '=', now()->toDateString())
                ->count();

            if ($checkKatalog > 0) {
                return redirect()->back()->with('error', 'Anda sudah melakukan scan katalog!');
            }
        }

        // VALIDASI TOKO AKTIF
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

        // JIKA ADA PARAMETER KATALOG
        if ($katalog[6] == 'Y') {
            DB::table('trns_dks')
                ->insert(
                    [
                        'tgl_kunjungan'     => now(),
                        'user_sales'        => $user,
                        'kd_toko'           => $kd_toko,
                        'waktu_kunjungan'   => $waktu_kunjungan,
                        'type'              => 'katalog',
                        'latitude'          => $latitude,
                        'longitude'         => $longitude,
                        'keterangan'        => $keterangan,
                        'created_by'        => $user,
                        'created_at'        => now(),
                        'updated_at'        => now(),
                        'katalog'           => 'Y',
                        'katalog_at'        => now()
                    ]
                );

            return redirect()->route('dks.scan')->with('success', "Berhasil scan katalog");
        } else {
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
}
