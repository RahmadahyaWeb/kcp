<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class KcpInformation extends Model
{
    use HasFactory;

    public function login()
    {
        try {
            $response = Http::timeout(10)->asForm()->post('http://36.91.145.235/kcpapi/auth/login', [
                'username' => 'rahmatt',
                'password' => 'dedeikusyg24',
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }


    public function getNonAopParts($token)
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer $token",
        ])->get('http://36.91.145.235/kcpapi/api/master-part/non-aop');

        if ($response->successful()) {
            return $response->json();
        }

        return false;
    }

    public function getIntransitBySpb($token, $spb)
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer $token",
        ])->get("http://36.91.145.235/kcpapi/api/intransit/$spb");

        if ($response->successful()) {
            return $response->json();
        }

        return false;
    }
}
