@extends('layouts.app')

@section('title', $baiViet['TenBV'])

@section('content')
<div class="container mt-5 text-white">
    <h2 class="text-warning mb-3">{{ $baiViet['TenBV'] }}</h2>
    <p class="text-muted">Chủ đề: {{ $baiViet['chude']['TenChuDe'] ?? 'Không rõ' }}</p>
    @if($baiViet['Anh'])
        <div class="text-center">
            <img src="{{ asset($baiViet['Anh']) }}" class="img-fluid mb-3" alt="{{ $baiViet['TenBV'] }}">
        </div>
    @endif
    <p>{!! nl2br(e($baiViet['ChiTiet'])) !!}</p>
    <a href="{{ route('blog.index') }}" class="btn btn-outline-warning mt-4">← Quay lại danh sách</a>
</div>
@endsection
