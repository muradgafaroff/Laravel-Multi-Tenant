<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Bütün userləri gətir
     */
    public function index()
    {
        return response()->json(User::with('roles')->get(), 200);
    }

    /**
     * Yeni user yarat
     */
    public function store(Request $request)
    {
        // Yalnız admin user yarada bilər
        if (!auth()->user()->hasRole('admin')) {
            return response()->json(['message' => 'Səlahiyyət yoxdur'], 403);
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'nullable|string'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        // Role təyin olunur
        $role = $validated['role'] ?? 'employee';
        $user->assignRole($role);

        $token = $user->createToken('API Token')->plainTextToken;


            return response()->json([
            "message" => "User uğurla yaradıldı.",
            "user" => $user->load('roles'),
            "token" => $token
        ], 201);
    }

    /**
     * Tək user məlumatı
     */
    public function show($id)
    {
        return response()->json(User::with('roles')->findOrFail($id), 200);
    }

    /**
     * User yenilə
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $auth = auth()->user();

        $validated = $request->validate([
            'name'     => 'sometimes|string|max:255',
            'email'    => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|min:6',
            'role'     => 'nullable|string'
        ]);

        /** -------------------------------------
         *  RBAC TƏHLÜKƏSİZLİK QAYDALARI
         *  ------------------------------------*/

        // 1) Employee və manager admin edə bilməz
        if (!$auth->hasRole('admin') && !empty($validated['role'])) {
            return response()->json(['message' => 'Səlahiyyət yoxdur'], 403);
        }

        // 2) Öz rolunu aşağı sala bilməz (öz admin rolunu manager/employee edə bilməz)
        if ($user->id == $auth->id && !empty($validated['role']) && $validated['role'] !== 'admin') {
            return response()->json([
                'message' => 'Öz rolunuzu aşağı sala bilməzsiniz.'
            ], 403);
        }

        // 3) Sistemdə son adminin rolu dəyişdirilə bilməz
        if (!empty($validated['role']) && $validated['role'] !== 'admin') {

            $adminCount = User::role('admin')->count();

            if ($user->hasRole('admin') && $adminCount == 1) {
                return response()->json([
                    'message' => 'Sistemdə ən az 1 admin olmalıdır.'
                ], 403);
            }
        }

        // Parol yenilənirsə hash edirik
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        // User məlumatı yenilənir
        $user->update($validated);

        // Rol göndərilibsə dəyişdiririk
        if (!empty($validated['role'])) {
            $user->syncRoles([$validated['role']]);
        }

        return response()->json($user->load('roles'), 200);
    }

    /**
     * User sil
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $auth = auth()->user();

        // Admin olmayan user silə bilməz
        if (!$auth->hasRole('admin')) {
            return response()->json(['message' => 'Səlahiyyət yoxdur'], 403);
        }

        // Son admin silinə bilməz
        if ($user->hasRole('admin') && User::role('admin')->count() == 1) {
            return response()->json([
                'message' => 'Sistemdə ən az 1 admin olmalıdır.'
            ], 403);
        }

        $user->delete();

        return response()->json(['message' => 'User silindi'], 200);
    }
}
