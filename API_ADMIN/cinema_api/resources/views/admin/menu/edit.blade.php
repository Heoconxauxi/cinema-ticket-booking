@extends('admin.layouts.app')

@section('title', 'Cập nhật Menu')

@section('content')
<div class="row">
    <div class="col-xl-8 col-lg-10 mx-auto">
        <div class="text-end mb-4"><a class="btn btn-secondary" href="{{ route('admin.menu.index') }}">Quay lại</a></div>
        
        <div class="card">
            <div class="card-header"><h5>Chỉnh sửa: {{ $menu->TenMenu }}</h5></div>
            <div class="card-body">
                <form id="editMenuForm" action="{{ route('admin.menu.update', $menu->Id) }}" method="POST">
                    @csrf 
                    @method('PUT')
                    
                     <div class="form-group mb-3">
                        <label for="TenMenu">Tên Menu</label>
                        <input type="text" class="form-control @error('TenMenu') is-invalid @enderror" id="TenMenu" name="TenMenu" value="{{ old('TenMenu', $menu->TenMenu) }}">
                        @error('TenMenu') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="KieuMenu">Kiểu Menu</label>
                            <select class="form-select @error('KieuMenu') is-invalid @enderror" id="KieuMenu" name="KieuMenu">
                                @foreach ($kieuMenus as $key => $value)
                                <option value="{{ $key }}" {{ old('KieuMenu', $menu->KieuMenu) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('KieuMenu') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                        </div>
                         <div class="col-md-6 form-group mb-3">
                            <label for="ViTri">Vị Trí Menu</label>
                            <select class="form-select @error('ViTri') is-invalid @enderror" id="ViTri" name="ViTri">
                                @foreach ($viTris as $key => $value)
                                <option value="{{ $key }}" {{ old('ViTri', $menu->ViTri) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                             @error('ViTri') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                        </div>
                    </div>
                     <div class="form-group mb-3" id="lienKetGroup">
                        <label for="LienKet">Liên Kết (URL)</label>
                        <input type="text" class="form-control @error('LienKet') is-invalid @enderror" id="LienKet" name="LienKet" value="{{ old('LienKet', $menu->LienKet) }}">
                        @error('LienKet') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                    </div>
                     <div class="form-group mb-3" id="tableIdGroup" style="display: none;">
                        <label for="TableId">ID Đối Tượng (Chưa hỗ trợ)</label>
                        <input type="number" class="form-control @error('TableId') is-invalid @enderror" id="TableId" name="TableId" value="{{ old('TableId', $menu->TableId) }}">
                        @error('TableId') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="Order">Thứ Tự Sắp Xếp</label>
                            <input type="number" class="form-control @error('Order') is-invalid @enderror" id="Order" name="Order" value="{{ old('Order', $menu->Order) }}">
                            @error('Order') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                        </div>
                         <div class="col-md-6 form-group mb-3 align-self-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="TrangThai" name="TrangThai" value="1" {{ old('TrangThai', $menu->TrangThai) == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="TrangThai">Kích hoạt</label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn bg-gradient-info px-5 mt-3">Lưu thay đổi</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script> /* JS ẩn/hiện giống file create */ </script>
@endpush