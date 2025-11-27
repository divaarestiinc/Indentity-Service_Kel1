<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class FrontendUserController extends Controller
{
    public function profile()
    {
        $response = Http::withToken(Session::get('token'))->get('http://localhost:8000/api/user');

        if ($response->successful()) {
            return view('user.profile', ['user' => $response->json()]);
        }

        return redirect('/login');
    }
}
