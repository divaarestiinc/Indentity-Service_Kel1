<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Tag(
 *     name="Admin - User Management",
 *     description="API khusus Admin untuk mengelola data user"
 * )
 */
class UserManagementController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin/users",
     *     tags={"Admin - User Management"},
     *     summary="Get list users (pagination + search)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search by name or email",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Pagination page",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success get users"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $query = User::query();

        if ($search) {
            $query->where(function($q) use ($search){
                $q->where('name', 'LIKE', "%$search%")
                  ->orWhere('email', 'LIKE', "%$search%");
            });
        }

        $users = $query->paginate(10);

        return response()->json([
            'status' => true,
            'data' => $users
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/admin/users",
     *     tags={"Admin - User Management"},
     *     summary="Create new user (Admin Only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *              required={"name","email","password","role"},
     *              @OA\Property(property="name", type="string", example="Budi"),
     *              @OA\Property(property="email", type="string", example="budi@gmail.com"),
     *              @OA\Property(property="password", type="string", example="password123"),
     *              @OA\Property(property="role", type="string", example="user")
     *         )
     *     ),
     *     @OA\Response(response=201, description="User created"),
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,user'
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/admin/users/{id}",
     *     tags={"Admin - User Management"},
     *     summary="Get user by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $user
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/admin/users/{id}",
     *     tags={"Admin - User Management"},
     *     summary="Update user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example="Update Name"),
     *              @OA\Property(property="email", type="string", example="change@gmail.com"),
     *              @OA\Property(property="password", type="string", example="newpassword"),
     *              @OA\Property(property="role", type="string", example="admin")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Updated Successfully")
     * )
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if(!$user){
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        $user->update([
            'name'     => $request->name     ?? $user->name,
            'email'    => $request->email    ?? $user->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'role'     => $request->role     ?? $user->role,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User updated',
            'data' => $user
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/admin/users/{id}",
     *     tags={"Admin - User Management"},
     *     summary="Delete user by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Deleted Successfully")
     * )
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if(!$user){
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'User deleted'
        ]);
    }
}
