<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    // send reset link (token)
    public function sendResetLink(Request $request)
    {
        $request->validate(['email'=>'required|email']);
        $email = $request->email;
        $user = User::where('email',$email)->first();
        if (! $user) {
            // not revealing existence
            return response()->json(['success'=>true,'message'=>'If the email exists you will receive instructions.']);
        }

        $plainToken = Str::random(60);
        DB::table('password_resets')->updateOrInsert(
            ['email'=>$email],
            ['token' => Hash::make($plainToken), 'created_at'=>Carbon::now()]
        );

        Mail::to($email)->send(new PasswordResetMail($plainToken, $email));

        return response()->json(['success'=>true,'message'=>'Reset email sent if account exists.']);
    }

    // reset password
    public function reset(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'token'=>'required|string',
            'password'=>'required|min:6|confirmed'
        ]);

        $record = DB::table('password_resets')->where('email',$request->email)->first();
        if (! $record) return response()->json(['success'=>false,'message'=>'Invalid token or email'],400);

        if (! Hash::check($request->token, $record->token))
            return response()->json(['success'=>false,'message'=>'Invalid token'],400);

        if (Carbon::parse($record->created_at)->addHour()->isPast())
            return response()->json(['success'=>false,'message'=>'Token expired'],400);

        $user = User::where('email',$request->email)->first();
        $user->password = $request->password; // will be hashed automatically
        $user->save();

        DB::table('password_resets')->where('email',$request->email)->delete();

        return response()->json(['success'=>true,'message'=>'Password updated']);
    }
}
