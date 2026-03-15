<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    // Fitur Pinjam Barang
    public function borrow(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'required|exists:items,id', // Barang harus ada di database
            'borrow_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // 1. Cari barang yang mau dipinjam
        $item = Item::find($request->item_id);

        // 2. Cek apakah stoknya masih ada?
        if ($item->stock < 1) {
            return response()->json([
                'message' => 'Maaf, stok barang sedang kosong!'
            ], 400); // 400 Bad Request
        }

        // 3. Catat di buku transaksi
        $transaction = Transaction::create([
            'user_id' => Auth::id(), // Otomatis ngambil ID dari Token Admin Guanteng
            'item_id' => $request->item_id,
            'borrow_date' => $request->borrow_date,
            'status' => 'dipinjam' // Status default
        ]);

        // 4. Kurangi stok barang di gudang
        $item->stock -= 1;
        $item->save();

        return response()->json([
            'message' => 'Barang berhasil dipinjam',
            'data' => $transaction
        ], 201);
    }
    // Fitur Mengembalikan Barang
    public function returnItem(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'return_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // 1. Cari data transaksinya berdasarkan ID
        $transaction = Transaction::find($id);

        // Cek apakah transaksi ada dan statusnya masih "dipinjam"
        if (!$transaction || $transaction->status == 'dikembalikan') {
            return response()->json([
                'message' => 'Transaksi tidak ditemukan atau barang sudah dikembalikan!'
            ], 400);
        }

        // 2. Ubah status dan catat tanggal kembali
        $transaction->status = 'dikembalikan';
        $transaction->return_date = $request->return_date;
        $transaction->save();

        // 3. Tambahkan kembali stok barang di gudang
        $item = Item::find($transaction->item_id);
        $item->stock += 1;
        $item->save();

        return response()->json([
            'message' => 'Barang berhasil dikembalikan, stok telah bertambah!',
            'data' => $transaction
        ], 200);
    }
    // Fitur Riwayat Peminjaman
    public function history()
    {
        // Ambil semua transaksi, lengkapi dengan data Barang dan User (Petugas)
        $transactions = Transaction::with(['item', 'user'])->get();
        
        return response()->json([
            'message' => 'Data Riwayat Transaksi',
            'data' => $transactions
        ], 200);
    }
}