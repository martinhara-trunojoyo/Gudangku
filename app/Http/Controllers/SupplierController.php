<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    /**
     * Display a listing of the suppliers for the user's UMKM.
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
            
            $suppliers = Supplier::where('umkm_id', $user->umkm_id)->get();
            
            return response()->json([
                'status' => 'success',
                'data' => $suppliers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve suppliers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created supplier in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'nama_supplier' => 'required|string|max:255',
            'alamat_supplier' => 'required|string',
            'kontak_supplier' => 'required|string|max:20',
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
            
            $supplier = Supplier::create([
                'nama_supplier' => $request->nama_supplier,
                'alamat_supplier' => $request->alamat_supplier,
                'kontak_supplier' => $request->kontak_supplier,
                'umkm_id' => $user->umkm_id,
            ]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Supplier created successfully',
                'data' => $supplier
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create supplier',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified supplier.
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
            
            $supplier = Supplier::where('supplier_id', $id)
                                ->where('umkm_id', $user->umkm_id)
                                ->first();
            
            if (!$supplier) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Supplier not found or does not belong to your UMKM'
                ], 404);
            }
            
            return response()->json([
                'status' => 'success',
                'data' => $supplier
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve supplier',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified supplier in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'nama_supplier' => 'sometimes|required|string|max:255',
            'alamat_supplier' => 'sometimes|required|string',
            'kontak_supplier' => 'sometimes|required|string|max:20',
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
            
            $supplier = Supplier::where('supplier_id', $id)
                                ->where('umkm_id', $user->umkm_id)
                                ->first();
            
            if (!$supplier) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Supplier not found or does not belong to your UMKM'
                ], 404);
            }
            
            // Update only the fields that were provided
            $updateData = [];
            
            if ($request->has('nama_supplier')) {
                $updateData['nama_supplier'] = $request->nama_supplier;
            }
            
            if ($request->has('alamat_supplier')) {
                $updateData['alamat_supplier'] = $request->alamat_supplier;
            }
            
            if ($request->has('kontak_supplier')) {
                $updateData['kontak_supplier'] = $request->kontak_supplier;
            }
            
            $supplier->update($updateData);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Supplier updated successfully',
                'data' => $supplier
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update supplier',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified supplier from storage.
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
            
            $supplier = Supplier::where('supplier_id', $id)
                                ->where('umkm_id', $user->umkm_id)
                                ->first();
            
            if (!$supplier) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Supplier not found or does not belong to your UMKM'
                ], 404);
            }
            
            $supplier->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Supplier deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete supplier',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
