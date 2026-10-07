<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AdminController extends Controller
{

    /**
     * Display all users
     */
    public function index()
    {
        $users = User::with('roles')->latest()->get();
        return view('pages.user.index', compact('users'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $roles = Role::all();
        return view('pages.user.create', compact('roles'));
    }

    /**
     * Store new user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'password' => 'required|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role' => 'required|exists:roles,name'
        ]);

        try {
            DB::beginTransaction();

            $avatarPath = null;

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $image = $request->file('avatar');
                $manager = new ImageManager(new Driver());
                $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

                $img = $manager->read($image);
                $img->resize(300, 300);
                $img->save(public_path("uploads/users/$name_gen"));

                $avatarPath = "uploads/users/$name_gen";
            }

            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'status' => $validated['status'],
                'password' => Hash::make($validated['password']),
                'avatar' => $avatarPath
            ]);

            // Assign role
            $user->assignRole($validated['role']);

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'User created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create user: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();
        return view('pages.user.edit', compact('user', 'roles'));
    }

    /**
     * Update user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // dd($request->avatar);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'role' => 'required|exists:roles,name'
        ]);

        try {
            DB::beginTransaction();

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                // Delete old avatar
                if ($user->avatar && file_exists(public_path($user->avatar))) {
                    unlink(public_path($user->avatar));
                }

                $image = $request->file('avatar');
                $manager = new ImageManager(new Driver());
                $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

                $img = $manager->read($image);
                $img->resize(300, 300);
                $img->save(base_path("../public_html/uploads/users/$name_gen"));

                $validated['avatar'] = "uploads/users/$name_gen";
            }

            // Update password only if provided
            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            // Remove role from validated data
            $role = $validated['role'];
            unset($validated['role']);

            // Update user
            $user->update($validated);

            // Sync role (remove old, assign new)
            $user->syncRoles([$role]);

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'User updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update user: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show user details
     */
    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);

            return response()->json($user);
    }

    /**
     * Delete user
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->id === auth()->id()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You cannot delete yourself!'
                ], 403);
            }

            // Delete avatar if exists
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                unlink(public_path($user->avatar));
            }

            $user->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle user status
     */
    public function toggleStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive'
        ]);

        try {
            $user = User::findOrFail($id);
            $user->status = $validated['status'];
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'User status updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Change user role
     */
    public function changeRole(Request $request, $id)
    {
        $validated = $request->validate([
            'role' => 'required|exists:roles,name'
        ]);

        try {
            $user = User::findOrFail($id);

            // Sync to the new role (removes all old roles)
            $user->syncRoles([$validated['role']]);

            return response()->json([
                'status' => 'success',
                'message' => 'User role updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get users by role
     */
    public function getUsersByRole($roleName)
    {
        $users = User::role($roleName)->get();

        return response()->json([
            'status' => 'success',
            'users' => $users
        ]);
    }

}
