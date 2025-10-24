@extends('admin.layouts.app')

@section('title', 'Thêm mới phim')

@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-12 mx-auto">
        <div class="text-end mb-4">
            <a class="btn btn-secondary" href="{{ route('admin.phim.index') }}">
                Quay lại
            </a>
        </div>

        <form id="addFilmForm" action="{{ route('admin.phim.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                {{-- CỘT BÊN TRÁI --}}
                <div class="col-md-6">
                    {{-- Tên Phim --}}
                    <div class="form-group mb-3">
                        <label for="TenPhim">Tên phim</label>
                        <input type="text" class="form-control @error('TenPhim') is-invalid @enderror" id="TenPhim" name="TenPhim" value="{{ old('TenPhim') }}" placeholder="Nhập tên phim">
                        @error('TenPhim') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                    </div>
                    {{-- Phân Loại --}}
                    <div class="form-group mb-3">
                        <label for="PhanLoai">Phân loại</label>
                        <select class="form-select @error('PhanLoai') is-invalid @enderror" id="PhanLoai" name="PhanLoai">
                            <option value="P" {{ old('PhanLoai') == 'P' ? 'selected' : '' }}>P - Phổ thông</option>
                            <option value="T13" {{ old('PhanLoai') == 'T13' ? 'selected' : '' }}>T13 - Cấm trẻ dưới 13</option>
                            <option value="T16" {{ old('PhanLoai') == 'T16' ? 'selected' : '' }}>T16 - Cấm trẻ dưới 16</option>
                            <option value="T18" {{ old('PhanLoai') == 'T18' ? 'selected' : '' }}>T18 - Cấm trẻ dưới 18</option>
                        </select>
                        @error('PhanLoai') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                    </div>
                    {{-- Đạo diễn, Diễn viên --}}
                    <div class="form-group mb-3"> <label for="DaoDien">Đạo diễn</label> <input type="text" class="form-control @error('DaoDien') is-invalid @enderror" id="DaoDien" name="DaoDien" value="{{ old('DaoDien') }}"> @error('DaoDien') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror </div>
                    <div class="form-group mb-3"> <label for="DienVien">Diễn viên</label> <input type="text" class="form-control @error('DienVien') is-invalid @enderror" id="DienVien" name="DienVien" value="{{ old('DienVien') }}"> @error('DienVien') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror </div>
                    {{-- Quốc Gia --}}
                    <div class="form-group mb-3">
                        <label>Quốc gia</label>
                        <div class="d-flex flex-wrap">
                            @php $selected_nations = old('quoc_gia') ?? []; @endphp
                            @foreach ($defined_nations as $nation)
                            <div class="form-check me-3 mb-2">
                                <input class="form-check-input" type="checkbox" name="quoc_gia[]" value="{{ $nation }}" id="nation_{{ $loop->index }}" @if(in_array($nation, $selected_nations)) checked @endif>
                                <label class="form-check-label" for="nation_{{ $loop->index }}">{{ $nation }}</label>
                            </div>
                            @endforeach
                            <div class="d-flex align-items-center mt-1">
                                <label for="other_nation" class="me-2 text-nowrap">Khác: </label>
                                <input class="form-control" style="width: 250px;" type="text" name="other_nation" value="{{ old('other_nation') }}" placeholder="Nhập khác... (cách nhau bằng dấu phẩy)">
                            </div>
                        </div>
                        @error('quoc_gia') <small class="text-danger m-2 text-xs d-block">{{ $message }}</small> @enderror
                        @error('other_nation') <small class="text-danger m-2 text-xs d-block">{{ $message }}</small> @enderror
                    </div>
                    {{-- Mô Tả --}}
                    <div class="form-group mb-3"> <label for="MoTa">Mô tả phim</label> <textarea class="form-control @error('MoTa') is-invalid @enderror" id="MoTa" name="MoTa" rows="10">{{ old('MoTa') }}</textarea> @error('MoTa') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror </div>
                </div>

                {{-- CỘT BÊN PHẢI --}}
                <div class="col-md-6">
                    {{-- Thể Loại --}}
                    <div class="form-group mb-3"> <label>Thể loại</label>
                        <div class="d-flex flex-wrap border rounded p-2" style="max-height: 150px; overflow-y: auto;"> @foreach ($the_loais as $genre) <div class="form-check me-3 mb-1 w-45"> <input class="form-check-input" type="checkbox" name="the_loai[]" value="{{ $genre->MaTheLoai }}" id="genre_{{ $genre->MaTheLoai }}" {{ (is_array(old('the_loai')) && in_array($genre->MaTheLoai, old('the_loai'))) ? 'checked' : '' }}> <label class="form-check-label" for="genre_{{ $genre->MaTheLoai }}">{{ $genre->TenTheLoai }}</label> </div> @endforeach </div> @error('the_loai') <small class="text-danger m-2 text-xs d-block">{{ $message }}</small> @enderror
                    </div>
                    {{-- Năm Phát Hành, Thời Lượng --}}
                    <div class="row">
                        <div class="col-md-6 form-group mb-3"> <label for="NamPhatHanh">Năm phát hành</label> <input type="number" class="form-control @error('NamPhatHanh') is-invalid @enderror" id="NamPhatHanh" name="NamPhatHanh" value="{{ old('NamPhatHanh') }}"> @error('NamPhatHanh') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror </div>
                        <div class="col-md-6 form-group mb-3"> <label for="ThoiLuong">Thời lượng (phút)</label> <input type="number" class="form-control @error('ThoiLuong') is-invalid @enderror" id="ThoiLuong" name="ThoiLuong" value="{{ old('ThoiLuong') }}"> @error('ThoiLuong') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror </div>
                    </div>
                    {{-- Trạng Thái --}}
                    <div class="form-group mb-3"> <label for="TrangThai">Trạng thái</label> <select class="form-select @error('TrangThai') is-invalid @enderror" id="TrangThai" name="TrangThai">
                            <option value="1" {{ old('TrangThai', 1) == 1 ? 'selected' : '' }}>Online</option>
                            <option value="0" {{ old('TrangThai') === '0' ? 'selected' : '' }}>Offline</option>
                            <option value="2" {{ old('TrangThai') == 2 ? 'selected' : '' }}>Coming soon</option>
                        </select> @error('TrangThai') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror </div>
                    {{-- Ảnh Poster, Banner --}}
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="Anh">URL Ảnh Poster (*)</label>
                            {{-- Đổi type="file" thành type="url" --}}
                            <input type="url" class="form-control @error('Anh') is-invalid @enderror" id="Anh" name="Anh" value="{{ old('Anh', $phim->Anh) }}" {{-- Giữ URL cũ --}} placeholder="Nhập URL mới để thay thế" required {{-- Keep oninput for typing --}} oninput="previewUrlImage(this.value, 'preview')" {{-- Add onpaste to handle pasting --}} onpaste="handlePaste(event, 'preview')">
                            @error('Anh') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                            {{-- Thẻ img để xem trước URL --}}
                            <img id="preview" src="#" alt="Xem trước Poster" class="img-fluid mt-2 border" style="display:none; max-height: 10rem;" onerror="this.style.display='none';"> {{-- Ẩn nếu URL lỗi --}}
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="Banner">URL Ảnh Banner</label>
                            {{-- Đổi type="file" thành type="url" --}}
                            <input type="url" class="form-control @error('Banner') is-invalid @enderror" id="Banner" name="Banner" value="{{ old('Banner', $phim->Banner) }}" {{-- Giữ URL cũ --}} placeholder="Nhập URL mới để thay thế" oninput="previewUrlImage(this.value, 'previewbanner')" onpaste="handlePaste(event, 'previewbanner')">
                            @error('Banner') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                            {{-- Thẻ img để xem trước URL --}}
                            <img id="previewbanner" src="#" alt="Xem trước Banner" class="img-fluid mt-2 border" style="display:none; max-height: 10rem;" onerror="this.style.display='none';"> {{-- Ẩn nếu URL lỗi --}}
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn bg-gradient-info px-5 mt-3">Lưu Phim</button>
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
