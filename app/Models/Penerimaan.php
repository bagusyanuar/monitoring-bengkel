<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penerimaan extends Model
{
    use HasFactory;

    protected $table = 'penerimaan';

    protected $fillable = [
        'user_id',
        'tanggal',
        'nama_barang',
        'plat',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
