<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TheLoai extends Model
{
    use HasFactory;

    protected $table = 'theloai';
    protected $primaryKey = 'MaTheLoai';

    public $timestamps = true;
    const CREATED_AT = 'NgayTao';
    const UPDATED_AT = 'NgayCapNhat';

    protected $fillable = [
        'TenTheLoai',
        'NguoiTao',
        'NguoiCapNhat',
        'TrangThai',
    ];

    public function phims()
    {
        return $this->belongsToMany(
            Phim::class,
            'theloai_film',
            'MaTheLoai',
            'MaPhim',
            'MaTheLoai',
            'MaPhim'
        );
    }
}
