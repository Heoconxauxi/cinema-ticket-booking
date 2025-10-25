<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $table = 'slider';
    protected $primaryKey = 'Id';

    public $timestamps = true;
    const CREATED_AT = 'NgayTao';
    const UPDATED_AT = 'NgayCapNhat';

    protected $fillable = [
        'TenSlider',
        'URL',
        'MaPhim',
        'Anh',
        'SapXep',
        'ViTri',
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
}
