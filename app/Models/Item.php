<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    // Jangan sampai lupa image dan description!
    protected $fillable = ['category_id', 'name', 'stock', 'image', 'description'];

    // Barang ini masuk ke satu kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Satu barang bisa dipinjam berkali-kali (banyak transaksi)
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}