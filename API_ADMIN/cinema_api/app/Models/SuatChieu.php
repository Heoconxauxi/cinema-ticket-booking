<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuatChieu extends Model
{
    use HasFactory;

    protected $table = 'suatchieu';

    protected $primaryKey = 'MaSuatChieu';

    public $timestamps = true;

    const CREATED_AT = 'NgayTao';

    const UPDATED_AT = 'NgayCapNhat';

    protected $fillable = [
        'MaPhim',
        'MaPhong',
        'GioChieu',
        'NguoiTao',
        'NguoiCapNhat',
        'TrangThai',
    ];

    protected $casts = [
        'GioChieu' => 'datetime',
    ];
    
    public function phim()
    {
        return $this->belongsTo(Phim::class, 'MaPhim', 'MaPhim');
    }

    public function phong()
    {
        return $this->belongsTo(Phong::class, 'MaPhong', 'MaPhong');
    }

    public function chiTietHoaDons()
    {
        return $this->hasMany(ChiTietHoaDon::class, 'MaSuatChieu', 'MaSuatChieu');
    }
}
