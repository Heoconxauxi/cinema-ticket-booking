@extends('admin.layouts.app')

@section('title', 'Danh sách Ghế')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center pb-0">
                    <h5>Danh sách Ghế</h5>

                    {{-- FORM LỌC (CÓ TÌM KIẾM) --}}
                    <div class="d-flex align-items-center">

                        {{-- Form chọn số bản ghi --}}
                        <form method="GET" action="{{ route('admin.ghe.index') }}" class="d-flex align-items-center me-2">
                            {{-- Giữ lại searchString khi đổi per_page --}}
                            @if (request('searchString'))
                                <input type="hidden" name="searchString" value="{{ $search }}">
                            @endif
                            <select class="form-select me-2" name="per_page" onchange="this.form.submit()">
                                <option value="10" {{ $per_page == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ $per_page == 20 ? 'selected' : '' }}>20</option>
                                <option value="50" {{ $per_page == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ $per_page == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </form>

                        {{-- Form tìm kiếm --}}
                        <form method="GET" action="{{ route('admin.ghe.index') }}" class="d-flex align-items-center me-2">
                            {{-- Giữ lại per_page khi tìm kiếm --}}
                            @if (request('per_page'))
                                <input type="hidden" name="per_page" value="{{ $per_page }}">
                            @endif
                            <div class="input-group">
                                <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                                <input type="search" name="searchString" class="form-control" placeholder="Tìm tên ghế, tên phòng..." 
                                       value="{{ $search ?? '' }}">
                            </div>
                        </form>

                        {{-- Nút Thêm mới --}}
                        <a href="{{ route('admin.ghe.create') }}" class="btn btn-purple flex-shrink-0">
                            <i class="bi bi-plus"></i> Thêm
                        </a>
                    </div>
                </div>
                {{-- KẾT THÚC FORM --}}

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table table-striped table-borderless align-items-center mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Tên Ghế</th>
                                    <th>Tên Phòng</th>
                                    <th>Loại Ghế</th>
                                    <th>Giá (VNĐ)</th>
                                    <th>Trạng Thái</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($list_ghe as $ghe)
                                <tr>
                                    <td>{{ $loop->iteration + ($list_ghe->currentPage() - 1) * $list_ghe->perPage() }}</td>
                                    <td>{{ $ghe->TenGhe }}</td>
                                    {{-- Dùng quan hệ 'phong' đã Eager Load --}}
                                    <td>{{ $ghe->phong->TenPhong ?? 'Lỗi: Phòng không tồn tại' }}</td>
                                    <td>{{ $ghe->LoaiGhe }}</td>
                                    <td>{{ number_format($ghe->GiaGhe, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($ghe->TrangThai == 1)
                                        <span class="badge bg-success">ON</span>
                                        @else
                                        <span class="badge bg-secondary">OFF</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.ghe.edit', $ghe->MaGhe) }}" class="btn btn-info btn-sm">SỬA</a>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal" 
                                                data-id="{{ $ghe->MaGhe }}" 
                                                data-name="{{ $ghe->TenGhe }} ({{ $ghe->phong->TenPhong ?? 'N/A' }})">
                                            XOÁ
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Không có bản ghi nào</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $list_ghe->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Xóa --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Xác Nhận Xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc muốn xóa ghế <strong id="itemName"></strong>?
                <p class="text-danger fw-bold mt-2">
                    CẢNH BÁO: Hành động này có thể xóa cả vé (ChiTietHoaDon) đã bán liên quan đến ghế này (do thiết lập ON DELETE CASCADE).
                </p>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Không</button>
                    <button type="submit" class="btn btn-danger">Có, Xóa!</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- JS cho Modal --}}
<script>
    var deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var itemId = button.getAttribute('data-id');
        var itemName = button.getAttribute('data-name');

        var modalTitle = deleteModal.querySelector('#itemName');
        var deleteForm = deleteModal.querySelector('#deleteForm');

        modalTitle.textContent = itemName;

        var actionUrl = '{{ route("admin.ghe.destroy", ":id") }}';
        deleteForm.action = actionUrl.replace(':id', itemId);
    });
</script>
@endpush