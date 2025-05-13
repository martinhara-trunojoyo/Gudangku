<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\Supplier;
use App\Mail\StockReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BarangMasukController extends Controller
{
    /**
     * Display a listing of the incoming stock records for the user's UMKM.
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
            
            $barangMasuk = BarangMasuk::with(['barang', 'supplier', 'user'])
                ->whereHas('barang', function($query) use ($user) {
                    $query->where('umkm_id', $user->umkm_id);
                })
                ->orderBy('tanggal_masuk', 'desc')
                ->get();
            
            return response()->json([
                'status' => 'success',
                'data' => $barangMasuk
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve incoming stock records',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created incoming stock record and update product stock.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jumlah_masuk' => 'required|numeric|min:1',
            'supplier_id' => 'required|exists:supplier,supplier_id',
            'barang_id' => 'required|exists:barang,barang_id',
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
            
            // Verify the barang belongs to the user's UMKM
            $barang = Barang::where('barang_id', $request->barang_id)
                            ->where('umkm_id', $user->umkm_id)
                            ->first();
            
            if (!$barang) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The product does not belong to your UMKM'
                ], 422);
            }
            
            // Verify the supplier belongs to the user's UMKM
            $supplier = Supplier::where('supplier_id', $request->supplier_id)
                                ->where('umkm_id', $user->umkm_id)
                                ->first();
            
            if (!$supplier) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The supplier does not belong to your UMKM'
                ], 422);
            }
            
            // Use a database transaction to ensure both operations succeed or fail together
            DB::beginTransaction();
            
            // Use current date and time for tanggal_masuk
            $currentDateTime = Carbon::now();
            
            // Create the incoming stock record
            $barangMasuk = BarangMasuk::create([
                'jumlah_masuk' => $request->jumlah_masuk,
                'tanggal_masuk' => $currentDateTime,
                'supplier_id' => $request->supplier_id,
                'barang_id' => $request->barang_id,
                'user_id' => $user->user_id,
            ]);
            
            // Update the barang stock
            $oldStock = $barang->stok;
            $barang->stok += $request->jumlah_masuk;
            $barang->save();
            
            DB::commit();
            
            // Try to send email notification about stock update
            try {
                // Get all admin users in the same UMKM
                $adminUsers = \App\Models\User::where('umkm_id', $user->umkm_id)
                                            ->whereNotNull('email')
                                            ->get();
                
                // Get UMKM name
                $umkm = \App\Models\Umkm::find($user->umkm_id);
                $umkmName = $umkm ? $umkm->nama_umkm : 'Your UMKM';
                
                if ($adminUsers->isEmpty()) {
                    Log::info('No users with email addresses found for notification');
                } else {
                    foreach ($adminUsers as $admin) {
                        try {
                            Mail::to($admin->email)->send(new \App\Mail\ProductAdded(
                                $barang->nama_barang,
                                $user->username ?? 'Administrator',
                                $admin->username ?? 'UMKM Member',
                                $umkmName,
                                $request->jumlah_masuk,
                                $barang->stok,
                                $barang->satuan ?? 'unit',
                                $supplier->nama_supplier ?? 'Unknown supplier'
                            ));
                            Log::info('Stock notification email sent to: ' . $admin->email);
                        } catch (\Exception $mailException) {
                            Log::error('Failed to send email to ' . $admin->email . ': ' . $mailException->getMessage());
                        }
                    }
                }
            } catch (\Exception $e) {
                // Just log the error but don't fail the request
                Log::error('Failed to process email notifications: ' . $e->getMessage());
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Stock added successfully',
                'data' => [
                    'barang_masuk' => $barangMasuk,
                    'barang' => [
                        'barang_id' => $barang->barang_id,
                        'nama_barang' => $barang->nama_barang,
                        'old_stock' => $oldStock,
                        'new_stock' => $barang->stok,
                        'satuan' => $barang->satuan
                    ]
                ]
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to add incoming stock',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified incoming stock record.
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
            
            $barangMasuk = BarangMasuk::with(['barang', 'supplier', 'user'])
                ->find($id);
            
            if (!$barangMasuk) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Incoming stock record not found'
                ], 404);
            }
            
            // Check if the barang belongs to the user's UMKM
            if ($barangMasuk->barang->umkm_id !== $user->umkm_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You do not have access to this record'
                ], 403);
            }
            
            return response()->json([
                'status' => 'success',
                'data' => $barangMasuk
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve incoming stock record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified incoming stock record and adjust product stock accordingly.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'jumlah_masuk' => 'sometimes|required|numeric|min:1',
            'supplier_id' => 'sometimes|required|exists:suppliers,supplier_id',
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
            
            $barangMasuk = BarangMasuk::with('barang')
                ->find($id);
            
            if (!$barangMasuk) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Incoming stock record not found'
                ], 404);
            }
            
            // Check if the barang belongs to the user's UMKM
            if ($barangMasuk->barang->umkm_id !== $user->umkm_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You do not have access to this record'
                ], 403);
            }
            
            // If supplier_id is changing, verify it belongs to the user's UMKM
            if ($request->has('supplier_id') && $request->supplier_id != $barangMasuk->supplier_id) {
                $supplier = Supplier::where('supplier_id', $request->supplier_id)
                                   ->where('umkm_id', $user->umkm_id)
                                   ->first();
                
                if (!$supplier) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'The supplier does not belong to your UMKM'
                    ], 422);
                }
            }
            
            // Use a database transaction to ensure both operations succeed or fail together
            DB::beginTransaction();
            
            // If jumlah_masuk is changing, adjust the barang stock
            if ($request->has('jumlah_masuk') && $request->jumlah_masuk != $barangMasuk->jumlah_masuk) {
                $barang = $barangMasuk->barang;
                
                // Remove the old quantity and add the new one
                $barang->stok = $barang->stok - $barangMasuk->jumlah_masuk + $request->jumlah_masuk;
                
                // Ensure stock doesn't go negative
                if ($barang->stok < 0) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Cannot reduce incoming quantity as it would result in negative stock'
                    ], 422);
                }
                
                $barang->save();
            }
            
            // Update the record
            $updateData = [];
            
            if ($request->has('jumlah_masuk')) {
                $updateData['jumlah_masuk'] = $request->jumlah_masuk;
            }
            
            if ($request->has('supplier_id')) {
                $updateData['supplier_id'] = $request->supplier_id;
            }
            
            $barangMasuk->update($updateData);
            
            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Incoming stock record updated successfully',
                'data' => [
                    'barang_masuk' => $barangMasuk->fresh(['barang', 'supplier', 'user'])
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update incoming stock record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified incoming stock record and adjust product stock.
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
            
            $barangMasuk = BarangMasuk::with('barang')
                ->find($id);
            
            if (!$barangMasuk) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Incoming stock record not found'
                ], 404);
            }
            
            // Check if the barang belongs to the user's UMKM
            if ($barangMasuk->barang->umkm_id !== $user->umkm_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You do not have access to this record'
                ], 403);
            }
            
            // Use a database transaction to ensure both operations succeed or fail together
            DB::beginTransaction();
            
            // Adjust the barang stock
            $barang = $barangMasuk->barang;
            $barang->stok -= $barangMasuk->jumlah_masuk;
            
            // Ensure stock doesn't go negative
            if ($barang->stok < 0) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot delete this record as it would result in negative stock'
                ], 422);
            }
            
            $barang->save();
            
            // Delete the record
            $barangMasuk->delete();
            
            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Incoming stock record deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete incoming stock record',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
