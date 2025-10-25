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
                                <label for="Anh">URL Ảnh Đại Diện</label>
                                {{-- Đổi type, thêm oninput, onpaste --}}
                                <input type="url" class="form-control @error('Anh') is-invalid @enderror" id="Anh" name="Anh" value="{{ old('Anh') }}" placeholder="https://..." oninput="previewUrlImage(this.value, 'preview')" onpaste="handlePaste(event, 'preview')">
                                @error('Anh') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                                {{-- Sửa img preview --}}
                                <img id="preview" src="#" alt="Ảnh xem trước" class="img-fluid mt-3 border" style="display:none; max-width: 100%; max-height: 15rem;" onerror="this.style.display='none';">
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
<script>
    function previewUrlImage(url, previewId) {
        const imgElement = document.getElementById(previewId);
        if (url && (url.startsWith('http://') || url.startsWith('https://'))) { // Basic URL check
            imgElement.src = url;
            imgElement.style.display = 'block';
        } else {
            imgElement.src = '#';
            imgElement.style.display = 'none';
        }
        // Handle image loading errors
        imgElement.onerror = function() {
            this.style.display = 'none';
            this.src = '#';
        };
    }

    // New function to handle pasting
    function handlePaste(event, previewId) {
        // Prevent default paste behavior if needed (usually not necessary here)
        // event.preventDefault(); 

        // Get pasted text using clipboardData API
        const pastedText = (event.clipboardData || window.clipboardData).getData('text');

        // Use setTimeout to allow the input value to update *before* previewing
        // A tiny delay (e.g., 10 milliseconds) is often enough
        setTimeout(() => {
            previewUrlImage(pastedText, previewId);
            // Optionally, explicitly set the input value if needed, though usually not required
            // event.target.value = pastedText; 
        }, 10);
    }

    // Trigger preview for old values on page load (keep this)
    document.addEventListener('DOMContentLoaded', function() {
        const anhInput = document.getElementById('Anh');
        const bannerInput = document.getElementById('Banner');
        if (anhInput && anhInput.value) {
            previewUrlImage(anhInput.value, 'preview');
        }
        if (bannerInput && bannerInput.value) {
            previewUrlImage(bannerInput.value, 'previewbanner');
        }
        // Also trigger for edit page's old image previews if they exist by ID
        const anhOld = document.getElementById('preview_old');
        const bannerOld = document.getElementById('previewbanner_old');
        if (anhOld && anhOld.src && anhOld.src !== '#') {
            anhOld.onerror = function() {
                this.style.display = 'none';
            }; // Add error handling too
        }
        if (bannerOld && bannerOld.src && bannerOld.src !== '#') {
            bannerOld.onerror = function() {
                this.style.display = 'none';
            }; // Add error handling too
        }
    });

</script>
@endpush
