@extends('admin.layouts.app')

@section('title', 'Thêm mới Thể Loại')

@section('content')
<div class="row">
    {{-- 
      Vì form thể loại đơn giản, chúng ta dùng col-xl-8
      giống style trang admin (thay vì col-xl-12 như form phim) 
    --}}
    <div class="col-xl-8 col-lg-10 mx-auto">
        
        <div class="text-end mb-4">
            <a class="btn btn-secondary" href="{{ route('admin.theloai.index') }}">
                Quay lại
            </a>
        </div>
        
        {{-- Thêm 1 card bọc ngoài cho đẹp, giống trang phim/show.blade.php --}}
        <div class="card">
            <div class="card-header">
                <h5>Thêm Thể Loại Mới</h5>
            </div>
            <div class="card-body">
                <form id="addCategoryForm" action="{{ route('admin.theloai.store') }}" method="POST">
                    @csrf

                    {{-- Dùng form-group mb-3 và style error giống phim/create.blade.php --}}
                    <div class="form-group mb-3">
                        <label for="TenTheLoai">Tên Thể Loại</label>
                        <input type="text" class="form-control @error('TenTheLoai') is-invalid @enderror" 
                               id="TenTheLoai" name="TenTheLoai"
                               value="{{ old('TenTheLoai') }}" placeholder="Nhập tên thể loại (ví dụ: Hành Động)">
                        
                        @error('TenTheLoai')
                            <small class="text-danger m-2 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="TrangThai" name="TrangThai" value="1" checked>
                            <label class="form-check-label" for="TrangThai">Kích hoạt (Hiển thị)</label>
                        </div>
                    </div>

                    {{-- Dùng class button bg-gradient-info giống phim/create.blade.php --}}
                    <button type="submit" class="btn bg-gradient-info px-5 mt-3">Lưu</button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection