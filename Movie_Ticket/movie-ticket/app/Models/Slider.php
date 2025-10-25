<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;
    protected $table = 'slider';
    protected $primaryKey = 'MaSlider';
    public $timestamps = false;

    protected $fillable = [
        'TieuDe', 'HinhAnh', 'LienKet', 'ThuTu', 'TrangThai'
    ];
}
