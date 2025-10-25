@extends('admin.layouts.app')

@section('title', 'Danh sách Phòng Chiếu')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center pb-0">
                    <h5>Danh sách Phòng Chiếu</h5>

                    {{-- FORM LỌC (KHÔNG CÓ TÌM KIẾM) --}}
                    <div class="d-flex align-items-center">

                        {{-- Form chọn số bản ghi (Giống phim/index) --}}
                        <form method="GET" action="{{ route('admin.phong.index') }}" class="d-flex align-items-center me-2">
                            <select class="form-select me-2" name="per_page" onchange="this.form.submit()">
                                <option value="5"  {{ $per_page == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ $per_page == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ $per_page == 20 ? 'selected' : '' }}>20</option>
                            </select>
                        </form>

                        {{-- Nút Thêm mới --}}
                        <a href="{{ route('admin.phong.create') }}" class="btn btn-purple flex-shrink-0">
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
                                    <th>Tên Phòng</th>
                                    <th>Ngày tạo</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($list_phong as $phong)
                                <tr>
                                    <td>{{ $loop->iteration + ($list_phong->currentPage() - 1) * $list_phong->perPage() }}</td>
                                    <td>{{ $phong->TenPhong }}</td>
                                    <td>{{ $phong->NgayTao ? $phong->NgayTao->format('d/m/Y') : 'N/A' }}</td>
                                    <td>
                                        @if ($phong->TrangThai == 1)
                                        <span class="badge bg-success">ON</span>
                                        @else
                                        <span class="badge bg-secondary">OFF</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.phong.edit', $phong->MaPhong) }}" class="btn btn-info btn-sm">SỬA</a>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal" 
                                                data-id="{{ $phong->MaPhong }}" 
                                                data-name="{{ $phong->TenPhong }}">
                                            XOÁ
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Không có bản ghi nào</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $list_phong->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Xóa (Copy từ file phim/index) --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Xác Nhận Xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc muốn xóa phòng <strong id="itemName"></strong>?
                <p class="text-danger fw-bold mt-2">
                    CẢNH BÁO: Hành động này sẽ xóa vĩnh viễn tất cả Ghế và Suất Chiếu thuộc phòng này (do thiết lập ON DELETE CASCADE).
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
{{-- JS cho Modal (Copy từ file phim/index và đổi tên route) --}}
<script>
    var deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var itemId = button.getAttribute('data-id');
        var itemName = button.getAttribute('data-name');

        var modalTitle = deleteModal.querySelector('#itemName');
        var deleteForm = deleteModal.querySelector('#deleteForm');

        modalTitle.textContent = itemName;

        var actionUrl = '{{ route("admin.phong.destroy", ":id") }}';
        deleteForm.action = actionUrl.replace(':id', itemId);
    });
</script>
@endpush