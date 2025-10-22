@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')

    <h1>Chào mừng tới trang Quản trị, {{ Auth::user()->TenND }}!</h1>
    <p>Đây là trang dashboard, nơi bạn sẽ quản lý phim, suất chiếu, v.v.</p>

@endsection