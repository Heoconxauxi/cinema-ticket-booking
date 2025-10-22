@extends('admin.layouts.app')

@section('title', 'Thêm mới Bài Viết')

@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-12 mx-auto">
        <div class="text-end mb-4">
            <a class="btn btn-secondary" href="{{ route('admin.baiviet.index') }}">
                Quay lại
            </a>
        </div>

        <form id="addBaiVietForm" action="{{ route('admin.baiviet.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                {{-- CỘT BÊN TRÁI --}}
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>Nội dung Bài Viết</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="TenBV">Tên Bài Viết (Tiêu đề)</label>
                                <input type="text" class="form-control @error('TenBV') is-invalid @enderror" id="TenBV" name="TenBV" value="{{ old('TenBV') }}" placeholder="Nhập tiêu đề bài viết">
                                @error('TenBV')
                                <small class="text-danger m-2 text-xs">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="MoTa">Mô Tả Ngắn (SEO)</label>
                                <textarea class="form-control @error('MoTa') is-invalid @enderror" id="MoTa" name="MoTa" rows="3" placeholder="Mô tả ngắn hiển thị trên Google, trang danh sách...">{{ old('MoTa') }}</textarea>
                                @error('MoTa')
                                <small class="text-danger m-2 text-xs">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="ChiTiet">Chi Tiết Bài Viết</label>
                                <textarea class="form-control @error('ChiTiet') is-invalid @enderror" id="ChiTiet" name="ChiTiet" rows="15" placeholder="Nhập nội dung đầy đủ của bài viết...">{{ old('ChiTiet') }}</textarea>
                                @error('ChiTiet')
                                <small class="text-danger m-2 text-xs">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CỘT BÊN PHẢI --}}
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Thông tin chung</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="ChuDeBV">Chủ Đề</label>
                                <select class="form-select @error('ChuDeBV') is-invalid @enderror" id="ChuDeBV" name="ChuDeBV">
                                    <option value="">-- Vui lòng chọn chủ đề --</option>
                                    @foreach ($chudes as $chude)
                                        <option value="{{ $chude->Id }}" {{ old('ChuDeBV') == $chude->Id ? 'selected' : '' }}>
                                            {{ $chude->TenChuDe }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ChuDeBV')
                                    <small class="text-danger m-2 text-xs">{{ $message }}</small>
                                @enderror
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="KieuBV">Kiểu Bài Viết</label>
                                <input type="text" class="form-control @error('KieuBV') is-invalid @enderror" id="KieuBV" name="KieuBV" value="{{ old('KieuBV', 'blog') }}" placeholder="Ví dụ: blog, review, khuyenmai...">
                                @error('KieuBV')
                                <small class="text-danger m-2 text-xs">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="TuKhoa">Từ Khóa (SEO)</label>
                                <input type="text" class="form-control @error('TuKhoa') is-invalid @enderror" id="TuKhoa" name="TuKhoa" value="{{ old('TuKhoa') }}" placeholder="Cách nhau bằng dấu phẩy">
                                @error('TuKhoa')
                                <small class="text-danger m-2 text-xs">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="Anh">Ảnh Đại Diện</label>
                                <input type="file" class="form-control @error('Anh') is-invalid @enderror" id="Anh" name="Anh" accept="image/*" onchange="previewImage(event, 'preview')">
                                @error('Anh')
                                <small class="text-danger m-2 text-xs">{{ $message }}</small>
                                @enderror
                                <img id="preview" src="#" alt="Ảnh xem trước" class="img-fluid mt-3" style="display:none; max-width: 100%; max-height: 15rem;" />
                            </div>

                            <div class="form-group mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="TrangThai" name="TrangThai" value="1" checked>
                                    <label class="form-check-label" for="TrangThai">Kích hoạt (Hiển thị)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn bg-gradient-info px-5 mt-3">Lưu Bài Viết</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
{{-- Giữ lại hàm preview ảnh --}}
<script>
    function previewImage(event, previewId) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById(previewId);
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endpush