<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChuDe extends Model
{
    use HasFactory;

    protected $table = 'chude';
    protected $primaryKey = 'Id';

    public $timestamps = true;
    const CREATED_AT = 'NgayTao';
    const UPDATED_AT = 'NgayCapNhat';

    protected $fillable = [
        'MaPhim',
        'TenRutGon',
        'MoTa',
        'TuKhoa',
        'TenChuDe',
        'NguoiTao',
        'NguoiCapNhat',
        'TrangThai',
    ];

    public function phim()
    {
        return $this->belongsTo(Phim::class, 'MaPhim', 'MaPhim');
    }

    public function baiViets()
    {
        return $this->hasMany(BaiViet::class, 'ChuDeBV', 'Id');
    }
}
