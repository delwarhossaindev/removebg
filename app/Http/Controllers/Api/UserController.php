<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required',
            'staff_id' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'roles' => 'required',
        ]);

        if ($validated->fails()) {
            return response([
                'message' => $validated->errors()->first(), // First validation error message
                'errors' => $validated->errors(),
                'status' => 'error',
            ], 200);
        }

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->name,
                'staff_id' => $request->staff_id,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole($request->roles);

            $token = $user->createToken($request->email)->plainTextToken;

            DB::commit();

            return response([
                'token' => $token,
                'message' => 'Registration Successful',
                'status' => 'success',
            ], 201);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();

            // Handle unique constraint errors for staff_id or email
            if (str_contains($e->getMessage(), 'users_staff_id_unique')) {
                $errorMessage = 'Staff ID already exists';
            } elseif (str_contains($e->getMessage(), 'users_email_unique')) {
                $errorMessage = 'Email already exists';
            } else {
                $errorMessage = $e->getMessage();
            }

            return response([
                "message" => $errorMessage,
                "status" => "error",
            ], 403);
        } catch (\Exception $e) {
            DB::rollback();

            return response([
                "message" => $e->getMessage(),
                "status" => "error",
            ], 403);
        }
    }

    public function sendResetLinkEmail(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Send the reset password link
        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Password reset link sent successfully.',
                'status' => 'success',
            ], 200);
        }

        return response()->json([
            'message' => 'Failed to send password reset link.',
            'status' => 'error',
        ], 500);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'password_confirmation' => 'required',
        ]);
        $loggeduser = auth()->user();
        $loggeduser->password = Hash::make($request->password_confirmation);
        $loggeduser->update();
        return response([
            'message' => 'Password Changed Successfully',
            'status' => 'success',
        ], 200);
    }

    public function change_password(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);
        $loggeduser = auth()->user();
        $loggeduser->password = Hash::make($request->password);
        $loggeduser->update();
        return response([
            'message' => 'Password Changed Successfully',
            'status' => 'success',
        ], 200);
    }

    public function login(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'password' => 'required',
            'email' => 'required_without:staff_id|email',
            'staff_id' => 'required_without:email',
        ]);

        if ($validated->fails()) {
            return response([
                'message' => $validated->errors(),
                'status' => 'error',
            ], 200);
        }

        $user = null;
        if ($request->has('email')) {
            $user = User::where('email', $request->email)->first();
        } elseif ($request->has('staff_id')) {
            $user = User::where('staff_id', $request->staff_id)->first();
        }

        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken($user->email ?? $user->staff_id)->plainTextToken;

            return response([
                'token' => $token,
                'name' => $user->name,
                'email' => $user->email,
                'staff_id' => $user->staff_id,
                'message' => 'Login Success',
                'status' => 'success',
            ], 200);
        }

        return response([
            'message' => 'The Provided Credentials are incorrect',
            'status' => 'failed',
        ], 401);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response([
            'message' => 'Logout Success',
            'status' => 'success',
        ], 200);
    }

    public function logged_user()
    {

        try {
            $role = null;
            $permissions = null;
            $loggeduser = auth()->user();
            $extraPermission = collect(auth()->user()->permissions)->pluck(
                "name"
            );

            if (isset(auth()->user()->roles[0])) {
                $roleID = auth()->user()->roles[0]->id;
                $role = Role::find($roleID);

                $rolePermissions = Permission::join(
                    "role_has_permissions",
                    "role_has_permissions.permission_id",
                    "=",
                    "permissions.id"
                )
                    ->whereIn(
                        "role_has_permissions.role_id",
                        auth()
                            ->user()
                            ->roles->pluck("id")
                    )
                    ->get();

                $permissionsList = collect($rolePermissions)->pluck("name");
                $permissionsList = $permissionsList
                    ->merge($extraPermission)
                    ->unique();
            }
            return response(
                [
                    "user" => $loggeduser,
                    "Permissions List" => $permissionsList,
                    "message" => "Logged User Data",
                    "status" => "success",
                ],
                200
            );
        } catch (\Exception $e) {
            return response(
                [
                    "message" => $e->getMessage(),
                    "status" => "error",
                ],
                403
            );
        }
    }



    public function role_list()
    {
        $roles = Role::pluck('name', 'id')->all();
        return response([
            'roles' => $roles,
            'message' => 'All Role List',
            'status' => 'success',
        ], 200);
    }

    public function role_wise_user()
    {
        $data = DB::table('users as user')
            ->select('user.id as user_id', 'user.email', 'user.name', 'r.name as role_name')
            ->join('model_has_roles as mhr', 'user.id', '=', 'mhr.model_id')
            ->join('roles as r', 'mhr.role_id', '=', 'r.id')
            ->get();

        $roles = collect($data)->groupBy('role_name');

        return response([
            'roles' => $roles,
            'message' => 'Role Wise User List',
            'status' => 'success',
        ], 200);
    }

    public function permission_list()
    {
        $permission = Permission::select('name', 'id')->get();
        return response([
            'permission' => $permission,
            'message' => 'All Permission List',
            'status' => 'success',
        ], 200);
    }

    public function user_list()
    {
        $userList = User::paginate(10);
        $userList->getCollection()->transform(function ($data) {
            $data->roles = $data->roles;
            $data->permissions = $data->permissions;
            return $data;
        });

        return response([
            'users' => $userList,
            'message' => 'All User List',
            'status' => 'success',
        ], 200);
    }

    public function user_edit(String $id)
    {
        try {
            $userList = User::find($id);
            $userList->roles;
            $userList->permissions;
            return response([
                'user_info' => $userList,
                'message' => 'User Edit Info',
                'status' => 'success',
            ], 200);
        } catch (\Exception $e) {
            return response(
                [
                    "message" => $e->getMessage(),
                    "status" => "error",
                ],
                403
            );
        }
    }

    public function user_update(Request $request, String $id)
    {
        $validated = Validator::make($request->all(), [
            "name" => "nullable|string", // Make name optional and a string if provided
            "email" => "nullable|email|unique:users,email," . $id, // Make email optional but validate if provided
        ]);

        if ($validated->fails()) {
            return response(
                [
                    "message" => $validated->errors(),
                    "status" => "error",
                ],
                403
            );
        }

        DB::beginTransaction();
        try {
            $user = User::find($id);
            if ($user) {
                // Update only the fields that are present in the request
                $user->update([
                    "name" => $request->name ?? $user->name, // If name is not provided, keep the existing value
                    "email" => $request->email ?? $user->email, // If email is not provided, keep the existing value
                    "status" => $request->status ?? $user->status, // Update status only if provided, else keep the existing one
                ]);

                $user->syncRoles($request->roles);
                DB::commit();
                return response(
                    [
                        "user" => $user,
                        "message" => "User updated successfully",
                        "status" => "success",
                    ],
                    200
                );
            }
            return response(
                [
                    "message" => "User not found",
                    "status" => "error",
                ],
                403
            );
        } catch (\Exception $e) {
            DB::rollback();
            return response(
                [
                    "message" => $e->getMessage(),
                    "status" => "error",
                ],
                403
            );
        }
    }

    public function assign_permission(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            "permissions" => "required",
        ]);

        if ($validated->fails()) {
            return response(
                [
                    "message" => $validated->errors(),
                    "status" => "error",
                ],
                403
            );
        }

        DB::beginTransaction();
        try {
            $user = User::find($id);
            if ($user) {

                $user->permissions()->detach();

                $user->givePermissionTo($request->permissions);

                DB::commit();
                return response(
                    [
                        "permissions" => $request->permissions,
                        "message" => "Assign Permission Successfully",
                        "status" => "success",
                    ],
                    201
                );
            }
            return response(
                [
                    "message" => "User Not Found!",
                    "status" => "success",
                ],
                201
            );
        } catch (\Exception $e) {
            DB::rollback();
            return response(
                [
                    "message" => $e->getMessage(),
                    "status" => "error",
                ],
                403
            );
        }
    }

    public function show(string $id)
    {

        try {
            $result = User::find($id);
            $data['result'] = $result;
            $data['status'] = 'success';
            $data['message'] = "Data Get Successfully";

            return response()->json($data, 200);
        } catch (\Exception $e) {
            $data = [];
            $data['status'] = 'error';
            $data['message'] = $e->getMessage();
            return response()->json($data, 400);
        }
    }

    public function user_delete(String $id)
    {

        try {
            $user = User::find($id);
            if ($user) {
                $user->delete();
                return response([
                    'message' => 'User deleted successfully',
                    'status' => 'Success',
                ], 200);
            }
            return response([
                'message' => 'User Not Found!',
                'status' => 'Success',
            ], 200);
        } catch (\Exception $e) {
            return response(
                [
                    "message" => $e->getMessage(),
                    "status" => "error",
                ],
                403
            );
        }
    }
}
