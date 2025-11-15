<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use OpenApi\Annotations as OA;


class AuthController extends Controller
{
        /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     tags={"Authentication"},
     *     summary="Register new user",
     *     description="Create new user account",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     */
    // Register User
    public function register(Request $request)
    {
        $v = Validator::make($request->all(), [
            'nama'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role'     => 'required|in:mahasiswa,dosen,admin',
            'prodi'    => 'nullable|string'
        ]);

        if ($v->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $v->errors()
            ], 422);
        }

        // hash password !!!
        $request['password'] = Hash::make($request->password);

        $user = User::create($request->only('nama', 'email', 'password', 'role', 'prodi'));

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data'    => $user
        ], 201);
    }
        /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     tags={"Authentication"},
     *     summary="User login",
     *     description="Authenticate user and return JWT token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful, token returned"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */

    // Login
    public function login(Request $request)
    {
        $credentials = $request->only('email','password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password'
            ], 401);
        }

        return $this->respondWithToken($token);
    }

        /**
     * @OA\Post(
     *     path="/api/auth/refresh",
     *     tags={"Authentication"},
     *     summary="Refresh JWT token",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Token refreshed"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */

    // Me (Profile)
    public function me()
    {
        return response()->json([
            'success' => true,
            'data'    => auth()->user()
        ]);
    }

    // Logout
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['success'=>true,'message'=>'Logged out']);
        } catch (JWTException $e) {
            return response()->json([
                'success'=>false,
                'message'=>'Failed to logout'
            ], 500);
        }
    }

    // Refresh Token
    public function refresh()
    {
        try {
            $newToken = auth()->refresh();
            return $this->respondWithToken($newToken);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not refresh token'
            ], 401);
        }
    }

    // Format token response
    protected function respondWithToken($token)
    {
        return response()->json([
            'success'      => true,
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
            'user'         => auth()->user()
        ]);
    }

    public function requestReset(Request $request)
{
    $request->validate(['email' => 'required|email']);

    $token = Str::random(60);

    DB::table('password_resets')->updateOrInsert(
        ['email' => $request->email],
        ['token' => $token, 'created_at' => now()]
    );

    // Mock email (bisa pakai queue & real mail server)
    Mail::raw("Token reset Anda: $token", function($msg) use ($request){
        $msg->to($request->email)->subject("Reset Password");
    });

    return response()->json(['success'=>true,'message'=>'Email sent']);
}

public function resetPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'token' => 'required',
        'password' => 'required|min:6'
    ]);

    $row = DB::table('password_resets')
        ->where(['email'=>$request->email,'token'=>$request->token])
        ->first();

    if (!$row) return response()->json(['success'=>false,'message'=>'Invalid token'],400);

    User::where('email', $request->email)->update([
        'password' => bcrypt($request->password)
    ]);

    DB::table('password_resets')->where('email', $request->email)->delete();

    return response()->json(['success'=>true,'message'=>'Password updated']);
}
}
