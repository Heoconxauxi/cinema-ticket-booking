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

        {{-- Form trỏ đến PhimController@update --}}
        <form id="editFilmForm" action="{{ route('admin.phim.update', $phim->MaPhim) }}" method="POST" enctype="multipart/form-data">
            @csrf {{-- Bắt buộc --}}
            @method('PUT') {{-- Bắt buộc cho update --}}

            <input type="hidden" name="ma_phim" value="{{ $phim->MaPhim }}">

            <div class="row">
                {{-- CỘT BÊN TRÁI --}}
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="ten_phim">Tên phim</label>
                        {{--
                          Ưu tiên 1: Lấy dữ liệu old() nếu validate fail
                          Ưu tiên 2: Lấy dữ liệu từ $phim->TenPhim
                        --}}
                        <input type="text" class="form-control @error('ten_phim') is-invalid @enderror" id="ten_phim" name="ten_phim" value="{{ old('ten_phim', $phim->TenPhim) }}" placeholder="Nhập tên phim">
                        @error('ten_phim')
                        <small class="text-danger m-2 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="phan_loai">Phân loại</label>
                        <select class="form-select @error('phan_loai') is-invalid @enderror" id="phan_loai" name="phan_loai">
                            <option value="P" {{ old('phan_loai', $phim->PhanLoai) == 'P' ? 'selected' : '' }}>Phổ thông</option>
                            <option value="T13" {{ old('phan_loai', $phim->PhanLoai) == 'T13' ? 'selected' : '' }}>T13</option>
                            <option value="T16" {{ old('phan_loai', $phim->PhanLoai) == 'T16' ? 'selected' : '' }}>T16</option>
                            <option value="T18" {{ old('phan_loai', $phim->PhanLoai) == 'T18' ? 'selected' : '' }}>T18</option>
                        </select>
                        @error('phan_loai')
                        <small class="text-danger m-2 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="dao_dien">Đạo diễn</label>
                        <input type="text" class="form-control @error('dao_dien') is-invalid @enderror" id="dao_dien" name="dao_dien" value="{{ old('dao_dien', $phim->DaoDien) }}" placeholder="Nhập tên đạo diễn">
                        @error('dao_dien')
                        <small class="text-danger m-2 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="dien_vien">Diễn viên</label>
                        <input type="text" class="form-control @error('dien_vien') is-invalid @enderror" id="dien_vien" name="dien_vien" value="{{ old('dien_vien', $phim->DienVien) }}" placeholder="Nhập tên diễn viên">
                        @error('dien_vien')
                        <small class="text-danger m-2 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    @php
                    $selected_nations = old('quoc_gia', $phim_nations ?? []);
                    @endphp

                    @foreach ($defined_nations as $nation)
                    <div class="form-check me-3 mb-2">
                        <input class="form-check-input" type="checkbox" name="quoc_gia[]" value="{{ $nation }}" id="nation_{{ $loop->index }}" @if(in_array($nation, $selected_nations)) checked @endif>
                        <label class="form-check-label" for="nation_{{ $loop->index }}">
                            {{ $nation }}
                        </label>
                    </div>
                    @endforeach

                    <div class="d-flex align-items-center">
                        <label for="other_nation" class="me-2">Khác: </label>
                        <input class="form-control" style="width: 250px;" type="text" name="other_nation" value="{{ old('other_nation', $other_nation ?? '') }}" placeholder="Nhập khác... (cách nhau bằng dấu phẩy)">
                    </div>

                    <div class="form-group mb-3">
                        <label for="mo_ta">Mô tả phim</label>
                        <textarea class="form-control @error('mo_ta') is-invalid @enderror" id="mo_ta" name="mo_ta" rows="10" placeholder="Nhập mô tả phim">{{ old('mo_ta', $phim->MoTa) }}</textarea>
                        @error('mo_ta')
                        <small class="text-danger m-2 text-xs">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                {{-- CỘT BÊN PHẢI --}}
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="the_loai">Thể loại</label>
                        <div class="d-flex flex-wrap">
                            @foreach ($the_loais as $genre)
                            <div class="form-check me-3">
                                <input class="form-check-input" type="checkbox" name="the_loai[]" value="{{ $genre->MaTheLoai }}" id="the_loai_{{ $genre->MaTheLoai }}" {{-- 
                                          $phim_theloais_ids được truyền từ Controller@edit
                                        --}} {{ (is_array(old('the_loai')) ? in_array($genre->MaTheLoai, old('the_loai')) : in_array($genre->MaTheLoai, $phim_theloais_ids)) ? 'checked' : '' }}>
                                <label class="form-check-label" for="the_loai_{{ $genre->MaTheLoai }}">{{ $genre->TenTheLoai }}</label>
                            </div>
                            @endforeach
                        </div>
                        @error('the_loai')
                        <small class="text-danger m-2 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="nam_phat_hanh">Năm phát hành</label>
                        <input type="number" class="form-control @error('nam_phat_hanh') is-invalid @enderror" id="nam_phat_hanh" name="nam_phat_hanh" value="{{ old('nam_phat_hanh', $phim->NamPhatHanh) }}" placeholder="Nhập năm phát hành">
                        @error('nam_phat_hanh')
                        <small class="text-danger m-2 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="thoi_luong">Thời lượng (phút)</label>
                        <input type="number" class="form-control @error('thoi_luong') is-invalid @enderror" id="thoi_luong" name="thoi_luong" value="{{ old('thoi_luong', $phim->ThoiLuong) }}" placeholder="Nhập thời lượng phim">
                        @error('thoi_luong')
                        <small class="text-danger m-2 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="status">Trạng thái</label>
                        <select class="form-select" id="status" name="status">
                            <option value="1" {{ old('status', $phim->TrangThai) == 1 ? 'selected' : '' }}>Online</option>
                            <option value="0" {{ old('status', $phim->TrangThai) == 0 ? 'selected' : '' }}>Offline</option>
                            <option value="2" {{ old('status', $phim->TrangThai) == 2 ? 'selected' : '' }}>Coming soon</option>
                        </select>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-6">
                            <label for="anh_phim">Chọn ảnh phim mới (để thay thế)</label>
                            <input type="file" class="form-control @error('anh_phim') is-invalid @enderror" id="anh_phim" name="anh_phim" accept="image/*" onchange="previewImage(event, 'preview')">
                            @error('anh_phim')
                            <small class="text-danger m-2 text-xs">{{ $message }}</small>
                            @enderror
                            <div class="form-group d-flex flex-column justify-content-center mt-3">
                                <label class="text-xs">Ảnh hiện tại:</label>
                                {{--
                                  Sử dụng Storage::url() để lấy đường dẫn public
                                  Giả sử bạn đã chạy `php artisan storage:link`
                                  Và $phim->Anh chỉ lưu tên file (ví dụ: 'ten_file.jpg')
                                --}}
                                <img id="preview_old" src="{{ $phim->Anh ? Storage::url('uploads/film/' . $phim->Anh) : '#' }}" alt="Ảnh hiện tại" class="img-fluid" style="max-width: 100%; max-height: 10rem; {{ $phim->Anh ? '' : 'display:none;' }}" />
                                <label class="text-xs mt-2">Ảnh xem trước (nếu chọn file mới):</label>
                                <img id="preview" src="#" alt="Ảnh xem trước" class="img-fluid" style="display:none; max-width: 100%; max-height: 10rem;" />
                            </div>
                        </div>
                        <div class="col-6">
                            <label for="banner">Chọn ảnh banner mới (để thay thế)</label>
                            <input type="file" class="form-control @error('banner') is-invalid @enderror" id="banner" name="banner" accept="image/*" onchange="previewImage(event, 'previewbanner')">
                            @error('banner')
                            <small class="text-danger m-2 text-xs">{{ $message }}</small>
                            @enderror
                            <div class="form-group d-flex flex-column justify-content-center mt-3">
                                <label class="text-xs">Banner hiện tại:</label>
                                <img id="previewbanner_old" src="{{ $phim->Banner ? Storage::url('uploads/film/' . $phim->Banner) : '#' }}" alt="Banner hiện tại" class="img-fluid" style="max-width: 100%; max-height: 10rem; {{ $phim->Banner ? '' : 'display:none;' }}" />
                                <label class="text-xs mt-2">Banner xem trước (nếu chọn file mới):</label>
                                <img id="previewbanner" src="#" alt="Ảnh xem trước" class="img-fluid" style="display:none; max-width: 100%; max-height: 10rem;" />
                            </div>
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
{{-- Giữ lại hàm preview ảnh của bạn --}}
<script>
    function previewImage(event, previewId) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById(previewId);
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }

</script>
@endpush
