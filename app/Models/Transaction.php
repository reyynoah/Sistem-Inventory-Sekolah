<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'item_id', 'borrow_date', 'return_date', 'status'];

    // Transaksi ini dicatat oleh satu user (petugas)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Transaksi ini meminjam satu barang
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}