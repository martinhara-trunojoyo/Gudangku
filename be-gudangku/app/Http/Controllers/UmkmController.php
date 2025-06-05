<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class UmkmController extends Controller
{
    /**
     * Check if user is admin
     */
    private function checkAdminRole()
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied. Only admin can perform this action.'
            ], 403);
        }
        return null;
    }

    public function store(Request $request)
    {
        // Check admin role
        $roleCheck = $this->checkAdminRole();
        if ($roleCheck) return $roleCheck;

        // Check if admin already has UMKM
        if (Auth::user()->umkm_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You already have a UMKM registered'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'nama_umkm' => 'required|string|max:255',
            'pemilik' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'kontak' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Create UMKM
            $umkm = Umkm::create([
                'nama_umkm' => $request->nama_umkm,
                'pemilik' => $request->pemilik,
                'alamat' => $request->alamat,
                'kontak' => $request->kontak
            ]);

            // Update user's umkm_id
            $userId = Auth::id();
            DB::table('users')->where('id', $userId)->update(['umkm_id' => $umkm->id]);
            
            // Get updated user data
            $user = User::find($userId);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'UMKM created successfully',
                'data' => [
                    'umkm' => $umkm,
                    'user' => $user
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create UMKM',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        // Check admin role
        $roleCheck = $this->checkAdminRole();
        if ($roleCheck) return $roleCheck;

        // Check if admin has UMKM
        if (!Auth::user()->umkm_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You do not have a UMKM to update'
            ], 400);
        }

        // Validate that at least one field is provided
        if (empty($request->all())) {
            return response()->json([
                'status' => 'error',
                'message' => 'No data provided for update'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'nama_umkm' => 'sometimes|required|string|max:255',
            'pemilik' => 'sometimes|required|string|max:255',
            'alamat' => 'sometimes|required|string|max:255',
            'kontak' => 'sometimes|required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $umkm = Umkm::findOrFail(Auth::user()->umkm_id);
            
            // Get only the fields that are present in the request
            $updateData = [];
            if ($request->has('nama_umkm') && !empty($request->nama_umkm)) {
                $updateData['nama_umkm'] = $request->nama_umkm;
            }
            if ($request->has('pemilik') && !empty($request->pemilik)) {
                $updateData['pemilik'] = $request->pemilik;
            }
            if ($request->has('alamat') && !empty($request->alamat)) {
                $updateData['alamat'] = $request->alamat;
            }
            if ($request->has('kontak') && !empty($request->kontak)) {
                $updateData['kontak'] = $request->kontak;
            }

            // Check if there's data to update
            if (empty($updateData)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No valid data provided for update'
                ], 422);
            }

            $umkm->update($updateData);
            
            // Refresh the model to get updated data
            $umkm->refresh();

            return response()->json([
                'status' => 'success',
                'message' => 'UMKM updated successfully',
                'data' => $umkm
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update UMKM',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show()
    {
        // Check admin role
        $roleCheck = $this->checkAdminRole();
        if ($roleCheck) return $roleCheck;

        // Check if admin has UMKM
        if (!Auth::user()->umkm_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You do not have a UMKM registered'
            ], 400);
        }

        try {
            $umkm = Umkm::findOrFail(Auth::user()->umkm_id);

            return response()->json([
                'status' => 'success',
                'data' => $umkm
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'UMKM not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}