<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu';
    protected $primaryKey = 'Id';

    public $timestamps = true;
    const CREATED_AT = 'NgayTao';
    const UPDATED_AT = 'NgayCapNhat';

    protected $fillable = [
        'TenMenu',
        'TableId',
        'KieuMenu',
        'ViTri',
        'LienKet',
        'Order',
        'NguoiTao',
        'NguoiCapNhat',
        'TrangThai',
    ];
}
