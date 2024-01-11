<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;
    protected $table = 'histori';
    protected $fillable = ['user_id','kategori','nominal','tanggal','keterangan'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
