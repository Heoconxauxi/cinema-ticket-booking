@extends('admin.layouts.app')

@section('title', 'Cập nhật Slider')

@section('content')
<div class="row">
    <div class="col-xl-10 col-lg-12 mx-auto">
        <div class="text-end mb-4">
            <a class="btn btn-secondary" href="{{ route('admin.slider.index') }}">
                Quay lại
            </a>
        </div>

        <form id="editSliderForm" action="{{ route('admin.slider.update', $slider->Id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header">
                    <h5>Chỉnh sửa: {{ $slider->TenSlider }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group mb-3">
                                <label for="TenSlider">Tên Slider</label>
                                <input type="text" class="form-control @error('TenSlider') is-invalid @enderror" id="TenSlider" name="TenSlider" value="{{ old('TenSlider', $slider->TenSlider) }}">
                                @error('TenSlider') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="Anh">URL Ảnh Slider (Nhập URL mới để thay)</label>
                                {{-- Đổi type, thêm oninput, onpaste --}}
                                <input type="url" class="form-control @error('Anh') is-invalid @enderror" id="Anh" name="Anh" value="{{ old('Anh', $slider->Anh) }}" placeholder="Để trống nếu không muốn đổi" oninput="previewUrlImage(this.value, 'preview')" onpaste="handlePaste(event, 'preview')">
                                @error('Anh') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror

                                <label class="text-xs mt-3">Ảnh hiện tại:</label>
                                {{-- Hiển thị URL cũ --}}
                                <img id="preview_old" src="{{ $slider->Anh ?: '#' }}" alt="Ảnh hiện tại" class="img-fluid mt-1 border" style="max-width: 100%; max-height: 20rem; border-radius: 5px; {{ $slider->Anh ? '' : 'display:none;' }}" onerror="this.style.display='none';">

                                <label class="text-xs mt-2 d-block">Xem trước URL mới:</label>
                                {{-- Sửa img preview --}}
                                <img id="preview" src="#" alt="Ảnh xem trước mới" class="img-fluid mt-1 border" style="display:none; max-width: 100%; max-height: 20rem; border-radius: 5px;" onerror="this.style.display='none';">
                            </div>
                            <div class="form-group mb-3">
                                <label for="MoTa">Mô Tả Ngắn (SEO)</label>
                                <input type="text" class="form-control @error('MoTa') is-invalid @enderror" id="MoTa" name="MoTa" value="{{ old('MoTa', $slider->MoTa) }}">
                                @error('MoTa') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="TuKhoa">Từ Khóa (SEO)</label>
                                <input type="text" class="form-control @error('TuKhoa') is-invalid @enderror" id="TuKhoa" name="TuKhoa" value="{{ old('TuKhoa', $slider->TuKhoa) }}">
                                @error('TuKhoa') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="ViTri">Vị Trí Hiển Thị</label>
                                <input list="viTriList" class="form-control @error('ViTri') is-invalid @enderror" id="ViTri" name="ViTri" value="{{ old('ViTri', $slider->ViTri) }}">
                                <datalist id="viTriList">
                                    @foreach($viTris as $viTri) <option value="{{ $viTri }}"> @endforeach
                                </datalist>
                                @error('ViTri') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="SapXep">Thứ Tự Sắp Xếp</label>
                                <input type="number" class="form-control @error('SapXep') is-invalid @enderror" id="SapXep" name="SapXep" value="{{ old('SapXep', $slider->SapXep) }}">
                                @error('SapXep') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="MaPhim">Phim Liên Kết (Nếu có)</label>
                                <select class="form-select @error('MaPhim') is-invalid @enderror" id="MaPhim" name="MaPhim">
                                    <option value="">-- Không chọn phim --</option>
                                    @foreach ($phims as $phim)
                                    <option value="{{ $phim->MaPhim }}" {{ old('MaPhim', $slider->MaPhim) == $phim->MaPhim ? 'selected' : '' }}>{{ $phim->TenPhim }}</option>
                                    @endforeach
                                </select>
                                @error('MaPhim') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="URL">URL Liên Kết (Nếu không chọn phim)</label>
                                <input type="url" class="form-control @error('URL') is-invalid @enderror" id="URL" name="URL" value="{{ old('URL', $slider->URL) }}">
                                @error('URL') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="TenChuDe">Tên Chủ Đề (Dư thừa?)</label>
                                <input type="text" class="form-control @error('TenChuDe') is-invalid @enderror" id="TenChuDe" name="TenChuDe" value="{{ old('TenChuDe', $slider->TenChuDe) }}">
                                @error('TenChuDe') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                            </div>
                            <div class="form-group mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="TrangThai" name="TrangThai" value="1" {{ old('TrangThai', $slider->TrangThai) == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="TrangThai">Kích hoạt</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn bg-gradient-info px-5 mt-3">Lưu thay đổi</button>
                </div>
            </div>
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
