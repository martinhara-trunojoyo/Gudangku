<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    /**
     * Check if user has UMKM access (admin or petugas)
     */
    private function checkUmkmAccess()
    {
        if (!in_array(Auth::user()->role, ['admin', 'petugas'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied. Only admin or petugas can perform this action.'
            ], 403);
        }

        if (!Auth::user()->umkm_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You need to be associated with a UMKM to access categories.'
            ], 400);
        }

        return null;
    }

    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $check = $this->checkUmkmAccess();
        if ($check) return $check;

        try {
            $categories = Kategori::where('umkm_id', Auth::user()->umkm_id)
                                ->orderBy('created_at', 'desc')
                                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Categories retrieved successfully',
                'data' => $categories
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
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $check = $this->checkUmkmAccess();
        if ($check) return $check;

        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|string|max:100',
            'deskripsi' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if category name already exists for this UMKM
        $existingCategory = Kategori::where('umkm_id', Auth::user()->umkm_id)
                                  ->where('nama_kategori', $request->nama_kategori)
                                  ->first();

        if ($existingCategory) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category name already exists in your UMKM'
            ], 409);
        }

        try {
            $category = Kategori::create([
                'nama_kategori' => $request->nama_kategori,
                'deskripsi' => $request->deskripsi,
                'umkm_id' => Auth::user()->umkm_id
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Category created successfully',
                'data' => $category
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
     */
    public function show($id)
    {
        $check = $this->checkUmkmAccess();
        if ($check) return $check;

        try {
            $category = Kategori::where('kategori_id', $id)
                              ->where('umkm_id', Auth::user()->umkm_id)
                              ->first();

            if (!$category) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Category not found or not authorized to view'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Category retrieved successfully',
                'data' => $category
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
     * Update the specified category.
     */
    public function update(Request $request, $id)
    {
        $check = $this->checkUmkmAccess();
        if ($check) return $check;

        // Validate that at least one field is provided
        if (empty($request->all())) {
            return response()->json([
                'status' => 'error',
                'message' => 'No data provided for update'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'sometimes|required|string|max:100',
            'deskripsi' => 'sometimes|nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $category = Kategori::where('kategori_id', $id)
                              ->where('umkm_id', Auth::user()->umkm_id)
                              ->first();

            if (!$category) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Category not found or not authorized to update'
                ], 404);
            }

            // Check if new category name already exists (except current category)
            if ($request->has('nama_kategori')) {
                $existingCategory = Kategori::where('umkm_id', Auth::user()->umkm_id)
                                          ->where('nama_kategori', $request->nama_kategori)
                                          ->where('kategori_id', '!=', $id)
                                          ->first();

                if ($existingCategory) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Category name already exists in your UMKM'
                    ], 409);
                }
            }

            // Prepare update data
            $updateData = [];
            if ($request->has('nama_kategori') && !empty($request->nama_kategori)) {
                $updateData['nama_kategori'] = $request->nama_kategori;
            }
            if ($request->has('deskripsi')) {
                $updateData['deskripsi'] = $request->deskripsi;
            }

            if (empty($updateData)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No valid data provided for update'
                ], 422);
            }

            $category->update($updateData);
            $category->refresh();

            return response()->json([
                'status' => 'success',
                'message' => 'Category updated successfully',
                'data' => $category
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
     * Remove the specified category.
     */
    public function destroy($id)
    {
        $check = $this->checkUmkmAccess();
        if ($check) return $check;

        try {
            $category = Kategori::where('kategori_id', $id)
                              ->where('umkm_id', Auth::user()->umkm_id)
                              ->first();

            if (!$category) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Category not found or not authorized to delete'
                ], 404);
            }

            // Check if category has associated products (barang)
            $hasProducts = $category->barang()->exists();
            if ($hasProducts) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot delete category. It has associated products.'
                ], 409);
            }

            $category->delete();

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
