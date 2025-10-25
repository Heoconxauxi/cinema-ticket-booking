@extends('layouts.app')

@section('title', $baiViet['TenBV'])

@section('content')
<div class="container mt-5 text-white">
    <h2 class="text-warning mb-3">{{ $baiViet['TenBV'] }}</h2>
    <p class="text-muted">Chủ đề: {{ $baiViet['chude']['TenChuDe'] ?? 'Không rõ' }}</p>
    <img src="{{ asset('uploads/' . $baiViet['Anh']) }}" class="img-fluid rounded mb-4" alt="{{ $baiViet['TenBV'] }}">
    <p>{!! nl2br(e($baiViet['ChiTiet'])) !!}</p>
    <a href="{{ route('blog.index') }}" class="btn btn-outline-warning mt-4">← Quay lại danh sách</a>
</div>
@endsection
