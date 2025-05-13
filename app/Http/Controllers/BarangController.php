<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\User;
use App\Models\Umkm;
use App\Models\NotificationSetting;
use App\Mail\ProductAdded;
use App\Notifications\NewBarangNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    /**
     * Display a listing of the barang for the user's UMKM.
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
            
            $barang = Barang::where('umkm_id', $user->umkm_id)->get();
            
            return response()->json([
                'status' => 'success',
                'data' => $barang
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve barang',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created barang in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $user = $request->user();
            
            if (!$user->umkm_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User is not associated with any UMKM'
                ], 404);
            }
            
            // Validate the request
            $validator = Validator::make($request->all(), [
                'nama_barang' => 'required|string|max:255',
                'stok' => 'required|numeric|min:0',
                'kategori_id' => 'required|exists:kategori,kategori_id',
                'satuan' => 'required|string|max:50',
                'lokasi_gudang' => 'required|string|max:255',
                'batas_minimum' => 'required|numeric|min:0',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation Error',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Check if kategori belongs to the same UMKM
            $kategori = Kategori::where('kategori_id', $request->kategori_id)
                                ->where('umkm_id', $user->umkm_id)
                                ->first();
            
            if (!$kategori) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The selected category does not belong to your UMKM'
                ], 422);
            }
            
            $barang = Barang::create([
                'nama_barang' => $request->nama_barang,
                'stok' => $request->stok,
                'kategori_id' => $request->kategori_id,
                'satuan' => $request->satuan,
                'lokasi_gudang' => $request->lokasi_gudang,
                'batas_minimum' => $request->batas_minimum,
                'umkm_id' => $user->umkm_id,
            ]);
            
            // Send email notifications to all users in the same UMKM
            try {
                // Get fresh user data with all relationships to ensure we have the latest data
                $currentUser = User::find($user->id);
                
                // Get UMKM name directly from the database
                $umkm = Umkm::find($user->umkm_id);
                
                if (!$umkm) {
                    throw new \Exception('UMKM not found');
                }
                
                $umkmName = $umkm->nama_umkm ?? $umkm->name ?? 'Your UMKM';
                $addedByName = $currentUser->name ?? 'UMKM Administrator';
                
                $usersInUMKM = User::where('umkm_id', $user->umkm_id)->get();
                
                foreach ($usersInUMKM as $recipient) {
                    if ($recipient->email) {
                        Mail::to($recipient->email)->send(new ProductAdded(
                            $barang,
                            $addedByName,                      // Name from database
                            $recipient->name ?? 'UMKM Member', 
                            $umkmName                          // UMKM name from database
                        ));
                    }
                }
            } catch (\Exception $emailError) {
                // Log the error but continue with the response
                Log::error('Failed to send email notification: ' . $emailError->getMessage());
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Barang created successfully',
                'data' => $barang
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create barang',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    
    /**
     * Display the specified barang.
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
            
            $barang = Barang::where('barang_id', $id)
                            ->where('umkm_id', $user->umkm_id)
                            ->first();
            
            if (!$barang) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Barang not found or does not belong to your UMKM'
                ], 404);
            }
            
            return response()->json([
                'status' => 'success',
                'data' => $barang
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve barang',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified barang in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $user = $request->user();
            
            if (!$user->umkm_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User is not associated with any UMKM'
                ], 404);
            }
            
            $barang = Barang::where('barang_id', $id)
                            ->where('umkm_id', $user->umkm_id)
                            ->first();
            
            if (!$barang) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Barang not found or does not belong to your UMKM'
                ], 404);
            }
            
            // Validate the request
            $validator = Validator::make($request->all(), [
                'nama_barang' => 'sometimes|required|string|max:255',
                'stok' => 'sometimes|required|numeric|min:0',
                'kategori_id' => 'sometimes|required|exists:kategori,kategori_id',
                'satuan' => 'sometimes|required|string|max:50',
                'lokasi_gudang' => 'sometimes|required|string|max:255',
                'batas_minimum' => 'sometimes|required|numeric|min:0',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation Error',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Update only the fields that were provided
            $updateData = [];
            
            if ($request->has('nama_barang')) {
                $updateData['nama_barang'] = $request->nama_barang;
            }
            
            if ($request->has('stok')) {
                $updateData['stok'] = $request->stok;
            }
            
            if ($request->has('kategori_id')) {
                // Check if the selected kategori belongs to the same UMKM
                $kategori = Kategori::where('kategori_id', $request->kategori_id)
                                    ->where('umkm_id', $user->umkm_id)
                                    ->first();
                
                if (!$kategori) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'The selected category does not belong to your UMKM'
                    ], 422);
                }
                
                $updateData['kategori_id'] = $request->kategori_id;
            }
            
            if ($request->has('satuan')) {
                $updateData['satuan'] = $request->satuan;
            }
            
            if ($request->has('lokasi_gudang')) {
                $updateData['lokasi_gudang'] = $request->lokasi_gudang;
            }

            if ($request->has('batas_minimum')) {
                $updateData['batas_minimum'] = $request->batas_minimum;
            }
            
            $barang->update($updateData);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Barang updated successfully',
                'data' => $barang
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update barang',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified barang from storage.
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
            
            $barang = Barang::where('barang_id', $id)
                            ->where('umkm_id', $user->umkm_id)
                            ->first();
            
            if (!$barang) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Barang not found or does not belong to your UMKM'
                ], 404);
            }
            
            $barang->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Barang deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete barang',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
