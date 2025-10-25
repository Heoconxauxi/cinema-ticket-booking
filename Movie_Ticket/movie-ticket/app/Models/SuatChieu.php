<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuatChieu extends Model
{
    use HasFactory;
    protected $table = 'suatchieu';
    protected $primaryKey = 'MaSuat';
    public $timestamps = false;

    protected $fillable = [
        'MaPhim', 'MaPhong', 'NgayChieu', 'GioChieu', 'GiaVe', 'TrangThai'
    ];

    public function phim()
    {
        return $this->belongsTo(Phim::class, 'MaPhim', 'MaPhim');
    }
}
