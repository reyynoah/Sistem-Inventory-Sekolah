<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    // Lihat semua barang (lengkap sama nama kategorinya)
    public function index()
    {
        // Pakai with('category') biar keliatan ini barang dari kategori apa
        $items = Item::with('category')->get(); 
        return response()->json([
            'message' => 'Data Barang Gudang',
            'data' => $items
        ], 200);
    }

    // Tambah barang baru (Bisa upload foto!)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id', // Harus kategori yang udah ada
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Cek apakah user ngirim foto
        $imagePath = null;
        if ($request->hasFile('image')) {
            // Langsung simpan ke folder storage/app/public/items
            $imagePath = $request->file('image')->store('items', 'public');
        }

        $item = Item::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'stock' => $request->stock,
            'image' => $imagePath,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Barang Berhasil Ditambahkan',
            'data' => $item
        ], 201);
    }
}