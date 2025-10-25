@extends('admin.layouts.app')

@section('title', 'Cập nhật Thể Loại')

@section('content')
<div class="row">
    <div class="col-xl-8 col-lg-10 mx-auto">
        
        <div class="text-end mb-4">
            <a class="btn btn-secondary" href="{{ route('admin.theloai.index') }}">
                Quay lại
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Chỉnh sửa: {{ $theloai->TenTheLoai }}</h5>
            </div>
            <div class="card-body">
                <form id="editCategoryForm" action="{{ route('admin.theloai.update', $theloai->MaTheLoai) }}" method="POST">
                    @csrf
                    @method('PUT') {{-- Bắt buộc cho update --}}

                    {{-- Style form và error giống hệt phim/edit.blade.php --}}
                    <div class="form-group mb-3">
                        <label for="TenTheLoai">Tên Thể Loại</label>
                        <input type="text" class="form-control @error('TenTheLoai') is-invalid @enderror" 
                               id="TenTheLoai" name="TenTheLoai"
                               value="{{ old('TenTheLoai', $theloai->TenTheLoai) }}">
                        
                        @error('TenTheLoai')
                            <small class="text-danger m-2 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="TrangThai" name="TrangThai" value="1"
                                {{-- old() ưu tiên, nếu không có thì dùng $theloai->TrangThai --}}
                                {{ old('TrangThai', $theloai->TrangThai) == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="TrangThai">Kích hoạt (Hiển thị)</label>
                        </div>
                    </div>

                    {{-- Style button giống hệt phim/edit.blade.php --}}
                    <button type="submit" class="btn bg-gradient-info px-5 mt-3">Lưu thay đổi</button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection