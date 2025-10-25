@extends('layouts.app')

@section('title', 'Tin tức & Blog')

@section('content')
<div class="container mt-5 text-white">
    <h2 class="mb-4 text-warning">📰 Bài viết mới nhất</h2>

    <div class="row">
        @forelse($baiVietList as $bv)
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white border-secondary shadow-sm h-100">
                    <img src="{{ $bv['Anh'] }}" class="card-img-top" alt="{{ $bv['TenBV'] }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $bv['TenBV'] }}</h5>
                        <p class="card-text text-muted small">{{ Str::limit($bv['MoTa'], 120) }}</p>
                        <a href="{{ route('blog.show', $bv['LienKet']) }}" class="btn btn-warning btn-sm">Đọc thêm</a>
                    </div>
                </div>
            </div>
        @empty
            <p>Không có bài viết nào được tìm thấy.</p>
        @endforelse
    </div>
</div>
@endsection
