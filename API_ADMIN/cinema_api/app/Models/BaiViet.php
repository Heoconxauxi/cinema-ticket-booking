<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaiViet extends Model
{
    use HasFactory;

    protected $table = 'baiviet';
    protected $primaryKey = 'Id';

    public $timestamps = true;
    const CREATED_AT = 'NgayTao';
    const UPDATED_AT = 'NgayCapNhat';

    protected $fillable = [
        'ChuDeBV',
        'TenBV',
        'LienKet',
        'ChiTiet',
        'Anh',
        'KieuBV',
        'MoTa',
        'TuKhoa',
        'NguoiTao',
        'NguoiCapNhat',
        'TrangThai',
    ];

    public function chude()
    {
        return $this->belongsTo(ChuDe::class, 'ChuDeBV', 'Id'); 
    }
}
