<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    // Melihat semua kategori
    public function index()
    {
        $categories = Category::all();
        return response()->json([
            'message' => 'Data Kategori Berhasil Ditampilkan',
            'data' => $categories
        ], 200);
    }

    // Menambah kategori baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $category = Category::create([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => 'Kategori Berhasil Ditambahkan',
            'data' => $category
        ], 201);
    }
}