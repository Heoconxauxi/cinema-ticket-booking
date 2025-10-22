<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietHoaDon extends Model
{
    use HasFactory;

    protected $table = 'chitiethoadon';
    protected $primaryKey = 'MaCTHD';

    public $timestamps = true;
    const CREATED_AT = 'NgayTao';
    const UPDATED_AT = 'NgayCapNhat';

    protected $fillable = [
        'MaHD',
        'MaSuatChieu',
        'MaGhe',
        'NguoiTao',
        'NguoiCapNhat',
        'TrangThai',
    ];

    public function hoaDon()
    {
        return $this->belongsTo(HoaDon::class, 'MaHD', 'MaHD');
    }

    public function suatChieu()
    {
        return $this->belongsTo(SuatChieu::class, 'MaSuatChieu', 'MaSuatChieu');
    }
    
    public function ghe()
    {
        return $this->belongsTo(Ghe::class, 'MaGhe', 'MaGhe');
    }
}
