<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{
    /**
     * Handle user registration.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'sometimes|string|in:admin,user',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'user',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ]);
    }
    /**
     * Handle user login.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'username' => 'required_without:email',
            'email' => 'required_without:username|email',
            'password' => 'required',
        ]);

        $user = $request->filled('email')
            ? User::where('email', $request->email)->first()
            : User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'The provided credentials are incorrect',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ]);
    }
    /**
     * Handle user logout.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    /**
     * Get the authenticated user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $request->user(),
        ]);
    }

    /**
     * Send a reset password link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Check if the user exists
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User with this email does not exist',
            ], 404);
        }

        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Password reset link has been sent to your email',
                ]);
            } else {
                // Log the error
                    Log::error('Failed to send password reset email', [
                    'email' => $request->email,
                    'status' => $status
                ]);
                
                return response()->json([
                    'status' => 'error',
                    'message' => trans($status),
                    'debug_info' => 'Check your Laravel logs for more details',
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Exception while sending password reset email', [
                'email' => $request->email,
                'exception' => $e->getMessage()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send password reset email',
                'debug_info' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reset the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);

                $user->setRememberToken(Str::random(60));
                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'status' => 'success',
                'message' => 'Password has been reset successfully',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => trans($status),
        ], 400);
    }

    /**
     * Update the specified user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();
        
        $validator = Validator::make($request->all(), [
            'username' => 'sometimes|string|max:255|unique:users,username,' . $user->user_id . ',user_id',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->user_id . ',user_id',
            'password' => 'sometimes|string|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $dataToUpdate = [];
        
        if ($request->has('username')) {
            $dataToUpdate['username'] = $request->username;
        }
        
        if ($request->has('email')) {
            $dataToUpdate['email'] = $request->email;
        }
        
        if ($request->has('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }
        
        // Update user
        $user->update($dataToUpdate);
        
        return response()->json([
            'status' => 'success',
            'message' => 'User information updated successfully',
            'data' => [
                'user' => $user,
            ],
        ]);
    }

    /**
     * Register a new Staff member (petugas).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerStaff(Request $request)
    {
        // only admin can create staff account
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only admins can create staff accounts.',
            ], 403);
        }
        
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        // Get umkm_id from the admin user
        $validated['umkm_id'] = $request->user()->umkm_id;

        $staff = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'petugas', 
            'umkm_id' => $validated['umkm_id'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Staff member registered successfully',
            'data' => [
                'user' => $staff,
            ],
        ], 201);
    }

    /**
     * Delete a staff member (petugas).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteStaff(Request $request, $id)
    {
        // Only admin can delete staff accounts
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only admins can delete staff accounts.',
            ], 403);
        }
        
        // Find the staff member
        $staff = User::find($id);
        
        // Check if staff exists
        if (!$staff) {
            return response()->json([
                'status' => 'error',
                'message' => 'Staff member not found.',
            ], 404);
        }
        
        // Verify the user is a staff member
        if ($staff->role !== 'petugas') {
            return response()->json([
            'status' => 'error',
            'message' => 'User is not a staff member.',
            ], 400);
        }
        
        // Verify staff belongs to the same UMKM as the admin
        if ($staff->umkm_id !== $request->user()->umkm_id) {
            return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized. You can only delete staff members from your own UMKM.',
            ], 403);
        }
        
        // Delete the staff member
        $staff->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Staff member deleted successfully',
        ]);
    }
}

