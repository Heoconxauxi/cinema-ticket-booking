<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NguoiDung extends Model
{
    use HasFactory;

    protected $table = 'nguoidung';
    protected $primaryKey = 'MaND';

    public $timestamps = true;
    const CREATED_AT = 'NgayTao';
    const UPDATED_AT = 'NgayCapNhat';

    protected $fillable = [
        'MaND',
        'TenND',
        'NgaySinh',
        'GioiTinh',
        'SDT',
        'Anh',
        'Email',
        'NguoiTao',
        'NguoiCapNhat',
        'TrangThai',
    ];

    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'MaND', 'MaND');
    }

    public function hoaDons()
    {
        return $this->hasMany(HoaDon::class, 'MaND', 'MaND');
    }
}
