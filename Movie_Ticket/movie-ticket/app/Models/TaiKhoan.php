<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaiKhoan extends Model
{
    protected $table = 'taikhoan';
    protected $primaryKey = 'MaND';
    public $timestamps = false;

    protected $fillable = [
        'TenDangNhap', 'MatKhau', 'TenND', 'Quyen'
    ];

    // Quan hệ 1-1 với bảng nguoidung
    public function nguoidung()
    {
        return $this->hasOne(NguoiDung::class, 'MaND', 'MaND');
    }
}
