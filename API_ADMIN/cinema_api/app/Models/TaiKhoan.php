<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class TaiKhoan extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'taikhoan';

    protected $primaryKey = 'MaND';

    public $timestamps = false;

    protected $fillable = [
        'TenDangNhap',
        'MatKhau',
        'TenND',
        'Quyen',
    ];

    protected $hidden = [
        //'MatKhau',
    ];

    public function getAuthPasswordName()
    {
        return 'MatKhau';
    }

    public function nguoiDung()
    {
        // hasOne(Model, 'foreign_key', 'local_key')
        return $this->hasOne(NguoiDung::class, 'MaND', 'MaND');
    }
}
