<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\ApiResponse;

/**
 * @OA\Tag(
 *     name="Admin Users",
 *     description="API untuk manajemen user oleh admin"
 * )
 */
class AdminUserController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/admin/users",
     *     summary="List users with pagination & search",
     *     tags={"Admin Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Search by name or email",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="User list retrieved successfully")
     * )
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $users = User::when($search, function($q) use ($search){
                    $q->where('name','like',"%$search%")
                      ->orWhere('email','like',"%$search%");
                })
                ->paginate(10);

        return response()->json($users);
    }

    /**
     * @OA\Post(
     *     path="/api/admin/users",
     *     summary="Create a new user (admin only)",
     *     tags={"Admin Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","role"},
     *             @OA\Property(property="name", type="string", example="Diva Resti"),
     *             @OA\Property(property="email", type="string", example="diva@mail.com"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *             @OA\Property(
     *                 property="role",
     *                 type="string",
     *                 example="admin_prodi",
     *                 enum={"mahasiswa","dosen","admin_prodi","admin_poli"}
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="User created successfully"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:mahasiswa,dosen,admin_prodi,admin_poli'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
            'role' => $request->role
        ]);

        return response()->json($user, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/admin/users/{id}",
     *     summary="Get a user by ID",
     *     tags={"Admin Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="User retrieved successfully"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function show($id)
    {
        $user = User::find($id);

        if (!$user)
            return response()->json(['message'=>'User not found'], 404);

        return response()->json($user);
    }

    /**
     * @OA\Put(
     *     path="/api/admin/users/{id}",
     *     summary="Update user",
     *     tags={"Admin Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Nama Baru"),
     *             @OA\Property(property="email", type="string", example="emailbaru@mail.com"),
     *             @OA\Property(property="password", type="string", example="passwordbaru"),
     *             @OA\Property(
     *                 property="role",
     *                 type="string",
     *                 example="admin_poli",
     *                 enum={"mahasiswa","dosen","admin_prodi","admin_poli"}
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="User updated successfully"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user)
            return response()->json(['message'=>'User not found'], 404);

        $user->update([
            'name' => $request->name ?? $user->name,
            'email'=> $request->email ?? $user->email,
            'role' => $request->role ?? $user->role,
        ]);

        if ($request->password) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return response()->json($user);
    }

    /**
     * @OA\Delete(
     *     path="/api/admin/users/{id}",
     *     summary="Delete user",
     *     tags={"Admin Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="User deleted successfully"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user)
            return response()->json(['message'=>'User not found'], 404);

        $user->delete();

        return response()->json(['message'=>'User deleted']);
    }
}
