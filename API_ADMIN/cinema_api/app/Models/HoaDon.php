<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoaDon extends Model
{
    use HasFactory;

    protected $table = 'hoadon';
    protected $primaryKey = 'MaHD';

    public $timestamps = true;
    const CREATED_AT = 'NgayTao';
    const UPDATED_AT = 'NgayCapNhat';

    protected $fillable = [
        'MaND',
        'NgayLapHD',
        'TongTien',
        'NguoiTao',
        'NguoiCapNhat',
        'TrangThai',
    ];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'MaND', 'MaND');
    }

    public function chiTietHoaDons()
    {
        return $this->hasMany(ChiTietHoaDon::class, 'MaHD', 'MaHD');
    }
}
