<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ghe extends Model
{
    use HasFactory;

    protected $table = 'ghe';
    protected $primaryKey = 'MaGhe';

    public $timestamps = true;
    const CREATED_AT = 'NgayTao';
    const UPDATED_AT = 'NgayCapNhat';

    protected $fillable = [
        'TenGhe',
        'MaPhong',
        'LoaiGhe',
        'GiaGhe',
        'NguoiTao',
        'NguoiCapNhat',
        'TrangThai',
    ];

    public function phong()
    {
        return $this->belongsTo(Phong::class, 'MaPhong', 'MaPhong');
    }

    public function chiTietHoaDons()
    {
        return $this->hasMany(ChiTietHoaDon::class, 'MaGhe', 'MaGhe');
    }
}
