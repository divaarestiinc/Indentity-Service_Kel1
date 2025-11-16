<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller {

    public function show($id){
        return User::findOrFail($id);
    }

    public function filter(Request $request){
        $role = $request->role;
        return User::where('role',$role)->get();
    }
}
