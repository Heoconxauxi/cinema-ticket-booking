@extends('admin.layouts.app')

@section('title', 'Cập nhật phim')

@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-12 mx-auto">
        <div class="text-end mb-4">
            <a class="btn btn-secondary" href="{{ route('admin.phim.index') }}">
                Quay lại
            </a>
        </div>

        <form id="editFilmForm" action="{{ route('admin.phim.update', $phim->MaPhim) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- CỘT BÊN TRÁI --}}
                <div class="col-md-6">
                    {{-- Tên Phim --}}
                    <div class="form-group mb-3"> <label for="TenPhim">Tên phim</label> <input type="text" class="form-control @error('TenPhim') is-invalid @enderror" id="TenPhim" name="TenPhim" value="{{ old('TenPhim', $phim->TenPhim) }}"> @error('TenPhim') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror </div>
                    {{-- Phân Loại --}}
                    <div class="form-group mb-3"> <label for="PhanLoai">Phân loại</label> <select class="form-select @error('PhanLoai') is-invalid @enderror" id="PhanLoai" name="PhanLoai">
                            <option value="P" {{ old('PhanLoai', $phim->PhanLoai) == 'P' ? 'selected' : '' }}>P</option>
                            <option value="T13" {{ old('PhanLoai', $phim->PhanLoai) == 'T13' ? 'selected' : '' }}>T13</option>
                            <option value="T16" {{ old('PhanLoai', $phim->PhanLoai) == 'T16' ? 'selected' : '' }}>T16</option>
                            <option value="T18" {{ old('PhanLoai', $phim->PhanLoai) == 'T18' ? 'selected' : '' }}>T18</option>
                        </select> @error('PhanLoai') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror </div>
                    {{-- Đạo diễn, Diễn viên --}}
                    <div class="form-group mb-3"> <label for="DaoDien">Đạo diễn</label> <input type="text" class="form-control @error('DaoDien') is-invalid @enderror" id="DaoDien" name="DaoDien" value="{{ old('DaoDien', $phim->DaoDien) }}"> @error('DaoDien') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror </div>
                    <div class="form-group mb-3"> <label for="DienVien">Diễn viên</label> <input type="text" class="form-control @error('DienVien') is-invalid @enderror" id="DienVien" name="DienVien" value="{{ old('DienVien', $phim->DienVien) }}"> @error('DienVien') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror </div>
                    {{-- Quốc Gia --}}
                    <div class="form-group mb-3">
                        <label>Quốc gia</label>
                        <div class="d-flex flex-wrap">
                            {{-- Biến $phim_nations và $other_nation lấy từ controller --}}
                            @php $selected_nations = old('quoc_gia', $phim_nations); @endphp
                            @foreach ($defined_nations as $nation)
                            <div class="form-check me-3 mb-2">
                                <input class="form-check-input" type="checkbox" name="quoc_gia[]" value="{{ $nation }}" id="nation_{{ $loop->index }}" @if(in_array($nation, $selected_nations)) checked @endif>
                                <label class="form-check-label" for="nation_{{ $loop->index }}">{{ $nation }}</label>
                            </div>
                            @endforeach
                            <div class="d-flex align-items-center mt-1">
                                <label for="other_nation" class="me-2 text-nowrap">Khác: </label>
                                <input class="form-control" style="width: 250px;" type="text" name="other_nation" value="{{ old('other_nation', $other_nation) }}" placeholder="Nhập khác... (cách nhau bằng dấu phẩy)">
                            </div>
                        </div>
                        @error('quoc_gia') <small class="text-danger m-2 text-xs d-block">{{ $message }}</small> @enderror
                        @error('other_nation') <small class="text-danger m-2 text-xs d-block">{{ $message }}</small> @enderror
                    </div>
                    {{-- Mô Tả --}}
                    <div class="form-group mb-3"> <label for="MoTa">Mô tả phim</label> <textarea class="form-control @error('MoTa') is-invalid @enderror" id="MoTa" name="MoTa" rows="10">{{ old('MoTa', $phim->MoTa) }}</textarea> @error('MoTa') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror </div>
                </div>

                {{-- CỘT BÊN PHẢI --}}
                <div class="col-md-6">
                    {{-- Thể Loại --}}
                    <div class="form-group mb-3"> <label>Thể loại</label>
                        <div class="d-flex flex-wrap border rounded p-2" style="max-height: 150px; overflow-y: auto;"> @foreach ($the_loais as $genre) <div class="form-check me-3 mb-1 w-45"> <input class="form-check-input" type="checkbox" name="the_loai[]" value="{{ $genre->MaTheLoai }}" id="genre_{{ $genre->MaTheLoai }}" {{ (is_array(old('the_loai')) ? in_array($genre->MaTheLoai, old('the_loai')) : in_array($genre->MaTheLoai, $phim_theloais_ids)) ? 'checked' : '' }}> <label class="form-check-label" for="genre_{{ $genre->MaTheLoai }}">{{ $genre->TenTheLoai }}</label> </div> @endforeach </div> @error('the_loai') <small class="text-danger m-2 text-xs d-block">{{ $message }}</small> @enderror
                    </div>
                    {{-- Năm Phát Hành, Thời Lượng --}}
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="NamPhatHanh">Năm phát hành</label>
                            <input type="number" class="form-control @error('NamPhatHanh') is-invalid @enderror" id="NamPhatHanh" name="NamPhatHanh" value="{{ old('NamPhatHanh', $phim->NamPhatHanh) }}">
                            @error('NamPhatHanh') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="ThoiLuong">Thời lượng (phút)</label>
                            <input type="number" class="form-control @error('ThoiLuong') is-invalid @enderror" id="ThoiLuong" name="ThoiLuong" value="{{ old('ThoiLuong', $phim->ThoiLuong) }}">
                            @error('ThoiLuong') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    {{-- Trạng Thái --}}
                    <div class="form-group mb-3">
                        <label for="TrangThai">Trạng thái</label>
                        <select class="form-select @error('TrangThai') is-invalid @enderror" id="TrangThai" name="TrangThai">
                            <option value="1" {{ old('TrangThai', $phim->TrangThai) == 1 ? 'selected' : '' }}>Online</option>
                            <option value="0" {{ old('TrangThai', $phim->TrangThai) === 0 ? 'selected' : '' }}>Offline</option>
                            <option value="2" {{ old('TrangThai', $phim->TrangThai) == 2 ? 'selected' : '' }}>Coming soon</option>
                        </select> @error('TrangThai') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                    </div>
                    {{-- Ảnh Poster, Banner --}}
                    <div class="col-md-6">
                        {{-- Ảnh Poster, Banner --}}
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label for="Anh">URL Ảnh Poster</label>
                                {{-- Đổi type="file" thành type="url" --}}
                                <input type="url" class="form-control @error('Anh') is-invalid @enderror" id="Anh" name="Anh" value="{{ old('Anh', $phim->Anh) }}" {{-- Giữ URL cũ --}} placeholder="Nhập URL mới để thay thế" required {{-- Keep oninput for typing --}} oninput="previewUrlImage(this.value, 'preview')" {{-- Add onpaste to handle pasting --}} onpaste="handlePaste(event, 'preview')">
                                @error('Anh') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror

                                <label class="text-xs mt-2">Ảnh hiện tại:</label>
                                {{-- Hiển thị trực tiếp từ URL --}}
                                <img id="preview_old" src="{{ $phim->Anh ?: '#' }}" alt="Ảnh hiện tại" class="img-fluid mt-1 border" style="max-height: 10rem; {{ $phim->Anh ? '' : 'display:none;' }}" onerror="this.style.display='none';">

                                <label class="text-xs mt-1 d-block">Xem trước URL mới:</label>
                                <img id="preview" src="#" alt="Xem trước Poster mới" class="img-fluid mt-1 border" style="display:none; max-height: 10rem;" onerror="this.style.display='none';">
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="Banner">URL Ảnh Banner</label>
                                {{-- Đổi type="file" thành type="url" --}}
                                <input type="url" class="form-control @error('Banner') is-invalid @enderror" id="Banner" name="Banner" value="{{ old('Banner', $phim->Banner) }}" {{-- Giữ URL cũ --}} placeholder="Nhập URL mới để thay thế" oninput="previewUrlImage(this.value, 'previewbanner')" onpaste="handlePaste(event, 'previewbanner')">
                                @error('Banner') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror

                                <label class="text-xs mt-2">Banner hiện tại:</label>
                                {{-- Hiển thị trực tiếp từ URL --}}
                                <img id="previewbanner_old" src="{{ $phim->Banner ?: '#' }}" alt="Banner hiện tại" class="img-fluid mt-1 border" style="max-height: 10rem; {{ $phim->Banner ? '' : 'display:none;' }}" onerror="this.style.display='none';">

                                <label class="text-xs mt-1 d-block">Xem trước banner mới:</label>
                                <img id="previewbanner" src="#" alt="Xem trước Banner mới" class="img-fluid mt-1 border" style="display:none; max-height: 10rem;" onerror="this.style.display='none';">
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn bg-gradient-info px-5 mt-3">Lưu thay đổi</button>
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
