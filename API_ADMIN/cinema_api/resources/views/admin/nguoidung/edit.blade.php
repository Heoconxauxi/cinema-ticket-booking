@extends('admin.layouts.app')

@section('title', 'Cập nhật Người Dùng')

@section('content')
<div class="row">
    <div class="col-xl-10 col-lg-12 mx-auto">
        <div class="text-end mb-4"><a class="btn btn-secondary" href="{{ route('admin.nguoidung.index') }}">Quay lại</a></div>

        <form id="editNguoiDungForm" action="{{ route('admin.nguoidung.update', $nguoidung->MaND) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                {{-- Left Column: Account Info --}}
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header">
                            <h5>Thông tin Tài Khoản</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="TenDangNhap">Tên Đăng Nhập</label>
                                <input type="text" class="form-control @error('TenDangNhap') is-invalid @enderror" id="TenDangNhap" name="TenDangNhap" value="{{ old('TenDangNhap', $nguoidung->taiKhoan->TenDangNhap ?? '') }}" required>
                                @error('TenDangNhap') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                            </div>
                            {{-- Password fields are optional on update --}}
                            <div class="form-group mb-3">
                                <label for="MatKhau">Mật Khẩu Mới (Để trống nếu không đổi)</label>
                                <input type="password" class="form-control @error('MatKhau') is-invalid @enderror" id="MatKhau" name="MatKhau">
                                @error('MatKhau') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="MatKhau_confirmation">Xác nhận Mật Khẩu Mới</label>
                                <input type="password" class="form-control" id="MatKhau_confirmation" name="MatKhau_confirmation">
                            </div>
                            <div class="form-group mb-3">
                                <label for="Quyen">Quyền</label>
                                <select class="form-select @error('Quyen') is-invalid @enderror" id="Quyen" name="Quyen" required>
                                    @foreach($roles as $key => $value)
                                    <option value="{{ $key }}" {{ old('Quyen', $nguoidung->taiKhoan->Quyen ?? '') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('Quyen') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="form-group mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="TrangThai" name="TrangThai" value="1" {{ old('TrangThai', $nguoidung->TrangThai) == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="TrangThai">Kích hoạt tài khoản</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Right Column: Profile Info --}}
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header">
                            <h5>Thông tin Cá Nhân</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="TenND">Họ và Tên</label>
                                <input type="text" class="form-control @error('TenND') is-invalid @enderror" id="TenND" name="TenND" value="{{ old('TenND', $nguoidung->TenND) }}" required>
                                @error('TenND') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label for="Email">Email</label>
                                    <input type="email" class="form-control @error('Email') is-invalid @enderror" id="Email" name="Email" value="{{ old('Email', $nguoidung->Email) }}">
                                    @error('Email') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="SDT">Số Điện Thoại</label>
                                    <input type="tel" class="form-control @error('SDT') is-invalid @enderror" id="SDT" name="SDT" value="{{ old('SDT', $nguoidung->SDT) }}">
                                    @error('SDT') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label for="NgaySinh">Ngày Sinh</label>
                                    {{-- Format date for input type="date" --}}
                                    <input type="date" class="form-control @error('NgaySinh') is-invalid @enderror" id="NgaySinh" name="NgaySinh" value="{{ old('NgaySinh', $nguoidung->NgaySinh ? $nguoidung->NgaySinh->format('Y-m-d') : '') }}">
                                    @error('NgaySinh') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label>Giới Tính</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="GioiTinh" id="gioiTinhNam" value="1" {{ old('GioiTinh', $nguoidung->GioiTinh) == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="gioiTinhNam">Nam</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="GioiTinh" id="gioiTinhNu" value="0" {{ old('GioiTinh', $nguoidung->GioiTinh) === 0 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="gioiTinhNu">Nữ</label>
                                        </div>
                                    </div>
                                    @error('GioiTinh') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="Anh">URL Ảnh Đại Diện (Nhập URL mới để thay)</label>
                                {{-- Đổi type, thêm oninput, onpaste --}}
                                <input type="url" class="form-control @error('Anh') is-invalid @enderror" id="Anh" name="Anh" value="{{ old('Anh', $nguoidung->Anh) }}" placeholder="Để trống hoặc xóa nếu muốn bỏ ảnh" oninput="previewUrlImage(this.value, 'preview')" onpaste="handlePaste(event, 'preview')">
                                @error('Anh') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror

                                <label class="text-xs mt-3">Ảnh hiện tại:</label>
                                {{-- Hiển thị URL cũ --}}
                                <img id="preview_old" src="{{ $nguoidung->Anh ?: '#' }}" alt="Ảnh hiện tại" class="img-fluid mt-1 avatar avatar-xl border" style="{{ $nguoidung->Anh ? '' : 'display:none;' }}" onerror="this.style.display='none';">

                                <label class="text-xs mt-2 d-block">Xem trước URL mới:</label>
                                {{-- Sửa img preview --}}
                                <img id="preview" src="#" alt="Xem trước ảnh mới" class="img-fluid mt-1 avatar avatar-xl border" style="display:none;" onerror="this.style.display='none';">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn bg-gradient-info px-5 mt-3">Lưu Thay Đổi</button>
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
