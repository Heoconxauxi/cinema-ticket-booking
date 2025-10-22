<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThamSo extends Model
{
    use HasFactory;

    protected $table = 'thamso';
    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'TenThamSo',
        'DonViTinh',
        'GiaTri',
        'TrangThai',
    ];
}
