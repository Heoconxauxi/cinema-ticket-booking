<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phim extends Model
{
    use HasFactory;
    protected $table = 'phim';
    protected $primaryKey = 'MaPhim';
    public $timestamps = false;

    protected $fillable = [
        'TenPhim', 'TheLoai', 'DaoDien', 'ThoiLuong', 'MoTa', 'AnhPhim', 'TrangThai'
    ];

    public function suatchieu()
    {
        return $this->hasMany(SuatChieu::class, 'MaPhim', 'MaPhim');
    }
}
