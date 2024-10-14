<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginPage()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);


        $user = DB::table('users')->where('username', $request->username)->first();

        if ($user && $this->verifyPassword($request->password, $user->password_md5)) {
            auth()->loginUsingId($user->id);

            return $this->authenticated($request, $request->username);
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }

    protected function authenticated(Request $request, $username)
    {
        User::where('username', $username)->update(['password' => Hash::make($request->password)]);

        Auth::logoutOtherDevices($request->password);

        return redirect()->route('dashboard');
    }

    private function verifyPassword($inputPassword, $hashedPassword)
    {
        // Di sini kita anggap hashedPassword adalah hasil hash dari Yii1
        // Misalnya jika hashedPassword menggunakan MD5:
        return md5($inputPassword) === $hashedPassword;

        // Jika menggunakan bcrypt:
        // return Hash::check($inputPassword, $hashedPassword);
    }

    public function logout()
    {
        User::where('id', Auth::id())->update(['last_seen' => now(), 'isOnline' => 'T']);
        auth()->logout();
        return redirect('/login');
    }
}
