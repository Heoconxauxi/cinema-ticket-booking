<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phong extends Model
{
    use HasFactory;

    protected $table = 'phong';
    protected $primaryKey = 'MaPhong';

    public $timestamps = true;
    const CREATED_AT = 'NgayTao';
    const UPDATED_AT = 'NgayCapNhat';

    protected $fillable = [
        'TenPhong',
        'NguoiTao',
        'NguoiCapNhat',
        'TrangThai',
    ];

    public function suatChieus()
    {
        return $this->hasMany(SuatChieu::class, 'MaPhong', 'MaPhong');
    }

    public function ghes()
    {
        return $this->hasMany(Ghe::class, 'MaPhong', 'MaPhong');
    }
}
