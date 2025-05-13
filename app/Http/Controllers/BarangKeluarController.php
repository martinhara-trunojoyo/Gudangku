<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\Umkm;
use App\Mail\StockReduced;
use App\Mail\LowStockAlert;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the outgoing stock records for the user's UMKM.
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
            
            $barangKeluar = BarangKeluar::with(['barang', 'user'])
                ->whereHas('barang', function($query) use ($user) {
                    $query->where('umkm_id', $user->umkm_id);
                })
                ->orderBy('tanggal_keluar', 'desc')
                ->get();
            
            return response()->json([
                'status' => 'success',
                'data' => $barangKeluar
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve outgoing stock records',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created outgoing stock record and update product stock.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jumlah_keluar' => 'required|numeric|min:1',
            'tujuan' => 'required|string|max:255',
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
            
            // Check if there's enough stock
            if ($barang->stok < $request->jumlah_keluar) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient stock. Available: ' . $barang->stok . ' ' . $barang->satuan,
                    'available_stock' => $barang->stok
                ], 422);
            }
            
            // Use a database transaction to ensure both operations succeed or fail together
            DB::beginTransaction();
            
            // Use current date and time for tanggal_keluar
            $currentDateTime = Carbon::now();
            
            // Create the outgoing stock record
            $barangKeluar = BarangKeluar::create([
                'jumlah_keluar' => $request->jumlah_keluar,
                'tanggal_keluar' => $currentDateTime,
                'tujuan' => $request->tujuan,
                'barang_id' => $request->barang_id,
                'user_id' => $user->user_id,
            ]);
            
            // Update the barang stock by reducing it
            $oldStock = $barang->stok;
            $barang->stok -= $request->jumlah_keluar;
            $barang->save();
            
            DB::commit();
            
            // Try to send email notification about stock reduction
            try {
                // Get all admin users in the same UMKM
                $adminUsers = \App\Models\User::where('umkm_id', $user->umkm_id)
                                            ->whereNotNull('email')
                                            ->get();
                
                // Get UMKM name
                $umkm = Umkm::find($user->umkm_id);
                $umkmName = $umkm ? $umkm->nama_umkm : 'Your UMKM';
                
                if ($adminUsers->isEmpty()) {
                    Log::info('No users with email addresses found for notification');
                } else {
                    // Check if stock is below or equal to minimum stock threshold
                    $isLowStock = $barang->stok <= $barang->batas_minimum;
                    
                    foreach ($adminUsers as $admin) {
                        try {
                            Mail::to($admin->email)->send(new StockReduced(
                                $barang->nama_barang,
                                $user->username ?? 'Staff',
                                $admin->username ?? 'UMKM Member',
                                $umkmName,
                                $request->jumlah_keluar, // Quantity removed
                                $barang->stok,
                                $barang->satuan ?? 'unit',
                                $request->tujuan ?? 'Unknown destination'
                            ));
                            Log::info('Stock reduction email sent to: ' . $admin->email);
                            
                            // Send additional low stock alert if stock is below minimum
                            if ($isLowStock) {
                                Mail::to($admin->email)->send(new LowStockAlert(
                                    $barang->nama_barang,
                                    $admin->username ?? 'UMKM Member',
                                    $umkmName,
                                    $barang->stok,
                                    $barang->batas_minimum,
                                    $barang->satuan ?? 'unit'
                                ));
                                Log::info('Low stock alert email sent to: ' . $admin->email);
                            }
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
                'message' => 'Stock released successfully',
                'data' => [
                    'barang_keluar' => $barangKeluar,
                    'barang' => [
                        'barang_id' => $barang->barang_id,
                        'nama_barang' => $barang->nama_barang,
                        'old_stock' => $oldStock,
                        'new_stock' => $barang->stok,
                        'satuan' => $barang->satuan,
                        'is_low_stock' => $barang->stok <= $barang->batas_minimum
                    ]
                ]
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to release stock',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified outgoing stock record.
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
            
            $barangKeluar = BarangKeluar::with(['barang', 'user'])
                ->find($id);
            
            if (!$barangKeluar) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Outgoing stock record not found'
                ], 404);
            }
            
            // Check if the barang belongs to the user's UMKM
            if ($barangKeluar->barang->umkm_id !== $user->umkm_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You do not have access to this record'
                ], 403);
            }
            
            return response()->json([
                'status' => 'success',
                'data' => $barangKeluar
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve outgoing stock record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified outgoing stock record and adjust product stock accordingly.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'jumlah_keluar' => 'sometimes|required|numeric|min:1',
            'tujuan' => 'sometimes|required|string|max:255',
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
            
            $barangKeluar = BarangKeluar::with('barang')
                ->find($id);
            
            if (!$barangKeluar) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Outgoing stock record not found'
                ], 404);
            }
            
            // Check if the barang belongs to the user's UMKM
            if ($barangKeluar->barang->umkm_id !== $user->umkm_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You do not have access to this record'
                ], 403);
            }
            
            // Use a database transaction to ensure both operations succeed or fail together
            DB::beginTransaction();
            
            $barang = $barangKeluar->barang;
            $oldQuantity = $barangKeluar->jumlah_keluar;
            $stockDifference = 0;
            $wasLowStock = $barang->stok <= $barang->batas_minimum;
            
            // If jumlah_keluar is changing, adjust the barang stock
            if ($request->has('jumlah_keluar') && $request->jumlah_keluar != $barangKeluar->jumlah_keluar) {
                // Calculate the stock difference
                $stockDifference = $barangKeluar->jumlah_keluar - $request->jumlah_keluar;
                
                // Add the difference to the current stock (positive difference means returning stock)
                $newStock = $barang->stok + $stockDifference;
                
                // Ensure stock doesn't go negative
                if ($newStock < 0) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Cannot increase outgoing quantity as it would result in negative stock',
                        'available_stock' => $barang->stok + $barangKeluar->jumlah_keluar
                    ], 422);
                }
                
                $barang->stok = $newStock;
                $barang->save();
            }
            
            // Update the record
            $updateData = [];
            
            if ($request->has('jumlah_keluar')) {
                $updateData['jumlah_keluar'] = $request->jumlah_keluar;
            }
            
            if ($request->has('tujuan')) {
                $updateData['tujuan'] = $request->tujuan;
            }
            
            $barangKeluar->update($updateData);
            
            DB::commit();
            
            // Send email notifications if quantity changed
            if ($stockDifference != 0) {
                try {
                    // Get all admin users in the same UMKM
                    $adminUsers = \App\Models\User::where('umkm_id', $user->umkm_id)
                                                ->whereNotNull('email')
                                                ->get();
                    
                    // Get UMKM name
                    $umkm = Umkm::find($user->umkm_id);
                    $umkmName = $umkm ? $umkm->nama_umkm : 'Your UMKM';
                    
                    // Check if stock is now below minimum threshold
                    $isNowLowStock = $barang->stok <= $barang->batas_minimum;
                    
                    if (!$adminUsers->isEmpty()) {
                        foreach ($adminUsers as $admin) {
                            try {
                                // Send stock adjustment email
                                Mail::to($admin->email)->send(new StockReduced(
                                    $barang->nama_barang,
                                    $user->username ?? 'Staff',
                                    $admin->username ?? 'UMKM Member',
                                    $umkmName,
                                    -$stockDifference, // Negative stockDifference means more stock removed
                                    $barang->stok,
                                    $barang->satuan ?? 'unit',
                                    $barangKeluar->tujuan ?? 'Unknown destination'
                                ));
                                Log::info('Stock adjustment email sent to: ' . $admin->email);
                                
                                // If stock has newly fallen below minimum threshold, send alert
                                if ($isNowLowStock && !$wasLowStock) {
                                    Mail::to($admin->email)->send(new LowStockAlert(
                                        $barang->nama_barang,
                                        $admin->username ?? 'UMKM Member',
                                        $umkmName,
                                        $barang->stok,
                                        $barang->batas_minimum,
                                        $barang->satuan ?? 'unit'
                                    ));
                                    Log::info('Low stock alert email sent to: ' . $admin->email);
                                }
                            } catch (\Exception $mailException) {
                                Log::error('Failed to send email to ' . $admin->email . ': ' . $mailException->getMessage());
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // Just log the error but don't fail the request
                    Log::error('Failed to process email notifications: ' . $e->getMessage());
                }
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Outgoing stock record updated successfully',
                'data' => [
                    'barang_keluar' => $barangKeluar->fresh(['barang', 'user']),
                    'is_low_stock' => $barang->stok <= $barang->batas_minimum
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update outgoing stock record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified outgoing stock record and adjust product stock.
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
            
            $barangKeluar = BarangKeluar::with('barang')
                ->find($id);
            
            if (!$barangKeluar) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Outgoing stock record not found'
                ], 404);
            }
            
            // Check if the barang belongs to the user's UMKM
            if ($barangKeluar->barang->umkm_id !== $user->umkm_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You do not have access to this record'
                ], 403);
            }
            
            // Use a database transaction to ensure both operations succeed or fail together
            DB::beginTransaction();
            
            // When deleting an outgoing record, return the stock to inventory
            $barang = $barangKeluar->barang;
            $returnedQuantity = $barangKeluar->jumlah_keluar;
            $oldStock = $barang->stok;
            
            $barang->stok += $returnedQuantity;
            $barang->save();
            
            // Delete the record
            $barangKeluar->delete();
            
            DB::commit();
            
            // Send email notification about returned stock
            try {
                // Get all admin users in the same UMKM
                $adminUsers = \App\Models\User::where('umkm_id', $user->umkm_id)
                                            ->whereNotNull('email')
                                            ->get();
                
                // Get UMKM name
                $umkm = Umkm::find($user->umkm_id);
                $umkmName = $umkm ? $umkm->nama_umkm : 'Your UMKM';
                
                if (!$adminUsers->isEmpty()) {
                    foreach ($adminUsers as $admin) {
                        try {
                            Mail::to($admin->email)->send(new StockReduced(
                                $barang->nama_barang,
                                $user->username ?? 'Staff',
                                $admin->username ?? 'UMKM Member',
                                $umkmName,
                                -$returnedQuantity, // Negative because we're returning stock (recording deletion)
                                $barang->stok,
                                $barang->satuan ?? 'unit',
                                'DELETED RECORD (previously: ' . ($barangKeluar->tujuan ?? 'Unknown') . ')'
                            ));
                            Log::info('Record deletion email sent to: ' . $admin->email);
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
                'message' => 'Outgoing stock record deleted successfully and stock returned to inventory'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete outgoing stock record',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
