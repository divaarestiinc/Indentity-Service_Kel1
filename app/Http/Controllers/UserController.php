<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

class UserController extends Controller
{
        /**
     * @OA\Get(
     *     path="/api/user/profile",
     *     tags={"User"},
     *     summary="Get user profile",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User profile data"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    
    /**
     * GET /api/me
     */
    public function me()
    {
        return response()->json(['success' => true, 'data' => Auth::user()]);
    }

    /**
     * GET /api/users?role=...
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(20);

        return response()->json(['success'=>true,'data'=>$users]);
    }

    /**
     * GET /api/users/{id}
     */
    public function show($id)
    {
        $u = User::find($id);
        if (!$u) return response()->json(['success'=>false,'message'=>'User not found'],404);

        return response()->json(['success'=>true,'data'=>$u]);
    }

    /**
     * PUT /api/users/{id}
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['success'=>false,'message'=>'User not found'],404);
        $auth = Auth::user();
        if ($auth->role !== 'admin' && $auth->id !== $user->id) {
            return response()->json(['success'=>false,'message'=>'Forbidden'],403);
        }

        $v = Validator::make($request->all(), [
            'nama' => 'sometimes|string|max:100',
            'prodi' => 'nullable|string',
            'role' => 'sometimes|in:mahasiswa,dosen,admin',
            'password' => 'sometimes|min:6'
        ]);
        if ($v->fails()) return response()->json(['success'=>false,'errors'=>$v->errors()],422);

        $data = $request->only('nama', 'prodi', 'role');

        // Secure password hashing
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        // Prevent students and lecturers from changing role
        if ($auth->role !== 'admin') {
            unset($data['role']);
        }

        $user->update($data);

        return response()->json(['success'=>true,'message'=>'Updated','data'=>$user]);
    }

    /**
     * DELETE /api/users/{id}
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['success'=>false,'message'=>'User not found'],404);

        $user->delete();

        return response()->json(['success'=>true,'message'=>'Deleted']);
    }

    /**
     * POST /api/users/{id}/avatar
     */
    public function uploadAvatar(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['success'=>false,'message'=>'User not found'],404);

        $auth = Auth::user();
        if ($auth->id !== $user->id && $auth->role !== 'admin') {
            return response()->json(['success'=>false,'message'=>'Forbidden'],403);
        }

        $v = Validator::make($request->all(), [
            'avatar' => 'required|image|max:2048'
        ]);
        if ($v->fails()) return response()->json(['success'=>false,'errors'=>$v->errors()],422);

        $path = $request->file('avatar')->store('avatars', 'public');

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->avatar = $path;
        $user->save();

        return response()->json([
            'success'=>true,
            'message'=>'Avatar uploaded',
            'data'=>[
                'avatar_url' => asset('storage/'.$path)
            ]
        ]);
    }
}
