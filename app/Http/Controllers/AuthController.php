<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Resources\ApiResponse;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register new user",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","role"},
     *             @OA\Property(property="name", type="string", example="Diva Resti"),
     *             @OA\Property(property="email", type="string", example="diva@mail.com"),
     *             @OA\Property(property="password", type="string", example="123456"),
     *             @OA\Property(property="role", type="string", example="mahasiswa")
     *         )
     *     ),
     *     @OA\Response(response=201, description="User registered successfully"),
     *     @OA\Response(response=400, description="Validation Error")
     * )
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:191',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6',
            'role'      => ['required', Rule::in(['mahasiswa','dosen','admin_prodi','admin_poli'])]
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
        ]);

        return ApiResponse::success($user, 'User registered successfully', 201);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login and get JWT token",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", example="diva@mail.com"),
     *             @OA\Property(property="password", type="string", example="123456")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Login success"),
     *     @OA\Response(response=401, description="Invalid credentials")
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email','password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return ApiResponse::error("Invalid credentials", 401);
        }

        return ApiResponse::success([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60
        ], "Login successful");
    }

    /**
     * @OA\Get(
     *     path="/api/me",
     *     summary="Get logged in user",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Success")
     * )
     */
    public function me()
    {
        return ApiResponse::success(auth()->user(), "User data fetched");
    }

    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Get all users or filter by role",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="role",
     *         in="query",
     *         description="Filter by role",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Success get users data")
     * )
     */
    public function getUsers(Request $request)
    {
        $role = $request->query('role');

        $users = $role ? 
            User::where('role', $role)->get() :
            User::all();

        return ApiResponse::success($users, "Users fetched successfully");
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Get user by ID",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Success get user data"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function getUserById($id)
    {
        $user = User::find($id);

        if (!$user) {
            return ApiResponse::error("User not found", 404);
        }

        return ApiResponse::success($user, "User fetched successfully");
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Logout user and invalidate token",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Logout success")
     * )
     */
    public function logout()
    {
        auth()->logout();

        return ApiResponse::success(null, "Successfully logged out");
    }

    /**
     * @OA\Post(
     *     path="/api/refresh",
     *     summary="Refresh JWT token",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Refresh token success")
     * )
     */
    public function refresh()
    {
        return ApiResponse::success([
            'access_token' => auth()->refresh(),
            'token_type'   => 'bearer'
        ], "Token refreshed successfully");
    }
}
