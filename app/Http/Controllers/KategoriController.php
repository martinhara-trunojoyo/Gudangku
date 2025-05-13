<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    /**
     * Display a listing of the categories for the user's UMKM.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            
            if (!$user->umkm_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User is not associated with any UMKM'
                ], 404);
            }
            
            $kategori = Kategori::where('umkm_id', $user->umkm_id)->get();
            
            return response()->json([
                'status' => 'success',
                'data' => $kategori
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created category in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            
            if (!$user->umkm_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User is not associated with any UMKM'
                ], 404);
            }
            
            $kategori = Kategori::create([
                'nama_kategori' => $request->nama_kategori,
                'umkm_id' => $user->umkm_id,
            ]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Category created successfully',
                'data' => $kategori
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified category.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        try {
            $user = $request->user();
            
            if (!$user->umkm_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User is not associated with any UMKM'
                ], 404);
            }
            
            $kategori = Kategori::where('kategori_id', $id)
                                ->where('umkm_id', $user->umkm_id)
                                ->first();
            
            if (!$kategori) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Category not found or does not belong to your UMKM'
                ], 404);
            }
            
            return response()->json([
                'status' => 'success',
                'data' => $kategori
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified category in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            
            if (!$user->umkm_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User is not associated with any UMKM'
                ], 404);
            }
            
            $kategori = Kategori::where('kategori_id', $id)
                                ->where('umkm_id', $user->umkm_id)
                                ->first();
            
            if (!$kategori) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Category not found or does not belong to your UMKM'
                ], 404);
            }
            
            // Update only the fields that were provided
            $updateData = [];
            
            if ($request->has('nama_kategori')) {
                $updateData['nama_kategori'] = $request->nama_kategori;
            }
            
            
            
            $kategori->update($updateData);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Category updated successfully',
                'data' => $kategori
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified category from storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        try {
            $user = $request->user();
            
            if (!$user->umkm_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User is not associated with any UMKM'
                ], 404);
            }
            
            $kategori = Kategori::where('kategori_id', $id)
                                ->where('umkm_id', $user->umkm_id)
                                ->first();
            
            if (!$kategori) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Category not found or does not belong to your UMKM'
                ], 404);
            }
            
            // Check if category has associated products
            $barangCount = $kategori->barang()->count();
            if ($barangCount > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot delete category because it has ' . $barangCount . ' associated products'
                ], 400);
            }
            
            $kategori->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Category deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete category',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
