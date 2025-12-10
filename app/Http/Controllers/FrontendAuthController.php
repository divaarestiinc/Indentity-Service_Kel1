<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class FrontendAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $response = Http::post('http://localhost:8000/api/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            Session::put('token', $response['token']);
            return redirect('/profile');
        }

        return back()->withErrors(['login' => 'Email atau password salah']);
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

  public function register(Request $request)
{
    $url = env('API_URL') . '/register';

    $response = Http::post($url, [
        'name' => $request->name,
        'email' => $request->email,
        'password' => $request->password,
        'role' => $request->role,
    ]);

    if ($response->successful()) {
        return redirect('/login')->with('success', 'Registrasi berhasil, silakan login');
    }

    return back()->withErrors(['register' => 'Registrasi gagal']);
}


    public function logout()
    {
        Session::forget('token');
        return redirect('/login');
    }
}
