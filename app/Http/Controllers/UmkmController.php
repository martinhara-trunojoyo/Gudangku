<?php

namespace App\Http\Controllers;

use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UmkmController extends Controller
{
    /**
     * Create a new UMKM and associate it with the authenticated user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'nama_umkm' => 'required|string|max:255',
            'pemilik' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kontak' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Get authenticated user
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Check if user already has an UMKM
            if ($user->umkm_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User already has an associated UMKM'
                ], 400);
            }

            // Create new UMKM with user_id
            $umkm = Umkm::create([
                'nama_umkm' => $request->nama_umkm,
                'pemilik' => $request->pemilik,
                'alamat' => $request->alamat,
                'kontak' => $request->kontak,
                'user_id' => $user->user_id,
            ]);

            // Update user's umkm_id
            $user->umkm_id = $umkm->umkm_id;
            $user->save();

            // Set user role to admin if not already
            if ($user->role !== 'admin') {
                $user->role = 'admin';
                $user->save();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'UMKM created and associated with user successfully',
                'data' => [
                    'umkm' => $umkm,
                    'user' => $user
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create UMKM',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get UMKM details for the authenticated user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $user = $request->user();
        
        if (!$user->umkm_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'User does not have an associated UMKM'
            ], 404);
        }
        
        $umkm = Umkm::find($user->umkm_id);
        
        if (!$umkm) {
            return response()->json([
                'status' => 'error',
                'message' => 'UMKM not found'
            ], 404);
        }
        
        return response()->json([
            'status' => 'success',
            'data' => $umkm
        ]);
    }

    /**
     * Update the UMKM information for the authenticated user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'nama_umkm' => 'sometimes|required|string|max:255',
            'pemilik' => 'sometimes|required|string|max:255',
            'alamat' => 'sometimes|required|string',
            'kontak' => 'sometimes|required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Get authenticated user
            $user = $request->user();
            
            if (!$user->umkm_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User does not have an associated UMKM'
                ], 404);
            }
            
            // Find the UMKM
            $umkm = Umkm::find($user->umkm_id);
            
            if (!$umkm) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'UMKM not found'
                ], 404);
            }
            
            // Check if user is authorized to update this UMKM
            if ($umkm->user_id !== $user->user_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized to update this UMKM'
                ], 403);
            }
            
            // Update UMKM with the provided fields
            $updateData = [];
            
            if ($request->has('nama_umkm')) {
                $updateData['nama_umkm'] = $request->nama_umkm;
            }
            
            if ($request->has('pemilik')) {
                $updateData['pemilik'] = $request->pemilik;
            }
            
            if ($request->has('alamat')) {
                $updateData['alamat'] = $request->alamat;
            }
            
            if ($request->has('kontak')) {
                $updateData['kontak'] = $request->kontak;
            }
            
            $umkm->update($updateData);

            return response()->json([
                'status' => 'success',
                'message' => 'UMKM information updated successfully',
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
}