<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phim extends Model
{
    use HasFactory;

    protected $table = 'phim';

    protected $primaryKey = 'MaPhim';

    public $timestamps = true;

    const CREATED_AT = 'NgayTao';

    const UPDATED_AT = 'NgayCapNhat';

    protected $fillable = [
        'TenPhim',
        'TenRutGon',
        'ThoiLuong',
        'Anh',
        'Banner',
        'DaoDien',
        'DienVien',
        'QuocGia',
        'NamPhatHanh',
        'PhanLoai',
        'MoTa',
        'NguoiTao',
        'NguoiCapNhat',
        'TrangThai',
    ];

    public function chuDes()
    {
        return $this->hasMany(ChuDe::class, 'MaPhim', 'MaPhim');
    }

    public function sliders()
    {
        return $this->hasMany(Slider::class, 'MaPhim', 'MaPhim');
    }

    public function theLoais()
    {
        return $this->belongsToMany(
            TheLoai::class,
            'theloai_film', 
            'MaPhim',
            'MaTheLoai',
            'MaPhim',
            'MaTheLoai'
        );
    }

    public function suatChieus()
    {
        return $this->hasMany(SuatChieu::class, 'MaPhim', 'MaPhim');
    }

    public function nguoiTao()
    {
        return $this->belongsTo(NguoiDung::class, 'NguoiTao', 'MaND');
    }

    public function nguoiCapNhat()
    {
        return $this->belongsTo(NguoiDung::class, 'NguoiCapNhat', 'MaND');
    }
}
