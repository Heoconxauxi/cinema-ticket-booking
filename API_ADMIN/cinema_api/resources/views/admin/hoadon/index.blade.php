@extends('admin.layouts.app')

@section('title', 'Danh sách Hóa Đơn')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center pb-0">
                    <h5>Danh sách Hóa Đơn</h5>

                    <div class="d-flex align-items-center">
                        {{-- Per Page Form --}}
                        <form method="GET" action="{{ route('admin.hoadon.index') }}" class="d-flex align-items-center me-2">
                             @if (request('searchString')) <input type="hidden" name="searchString" value="{{ $search }}"> @endif
                            <select class="form-select me-2" name="per_page" onchange="this.form.submit()">
                                <option value="10" {{ $per_page == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ $per_page == 20 ? 'selected' : '' }}>20</option>
                                <option value="50" {{ $per_page == 50 ? 'selected' : '' }}>50</option>
                            </select>
                        </form>
                        {{-- Search Form --}}
                        <form method="GET" action="{{ route('admin.hoadon.index') }}" class="d-flex align-items-center me-2">
                            @if (request('per_page')) <input type="hidden" name="per_page" value="{{ $per_page }}"> @endif
                            <div class="input-group">
                                <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                                <input type="search" name="searchString" class="form-control" placeholder="Tìm Mã HĐ, Tên User, Username..." 
                                       value="{{ $search ?? '' }}">
                            </div>
                        </form>
                        {{-- No "Add" button typically needed --}}
                    </div>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table table-striped table-borderless align-items-center mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Mã HĐ</th>
                                    <th>Người Đặt</th>
                                    <th>Ngày Lập</th>
                                    <th>Tổng Tiền</th>
                                    <th>Trạng Thái</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($list_hoadon as $hd)
                                <tr>
                                    <td>{{ $loop->iteration + ($list_hoadon->currentPage() - 1) * $list_hoadon->perPage() }}</td>
                                    <td>#{{ $hd->MaHD }}</td>
                                    <td>
                                        {{ $hd->nguoiDung->TenND ?? 'N/A' }} 
                                        <small class="d-block text-muted">{{ $hd->nguoiDung->taiKhoan->TenDangNhap ?? '' }}</small>
                                    </td>
                                    <td>{{ $hd->NgayLapHD->format('d/m/Y H:i') }}</td>
                                    <td>{{ number_format($hd->TongTien, 0, ',', '.') }} VNĐ</td>
                                    <td>
                                        @if ($hd->TrangThai == 1) 
                                            <span class="badge bg-success">Đã Thanh Toán</span> 
                                        @else 
                                            <span class="badge bg-warning text-dark">Chưa Thanh Toán</span> 
                                        @endif
                                        {{-- Add more statuses if needed --}}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.hoadon.show', $hd->MaHD) }}" class="btn btn-secondary btn-sm">XEM</a>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal" 
                                                data-id="{{ $hd->MaHD }}" 
                                                data-name="Hóa đơn #{{ $hd->MaHD }}">
                                            XOÁ
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr> <td colspan="7" class="text-center">Không có bản ghi nào</td> </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $list_hoadon->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1"> {{-- Modal --}}
    <div class="modal-dialog"> <div class="modal-content"> <div class="modal-header"> <h5 class="modal-title" id="deleteModalLabel">Xác Nhận Xóa</h5> <button type="button" class="btn-close" data-bs-dismiss="modal"></button> </div> <div class="modal-body"> Bạn có chắc muốn xóa <strong id="itemName"></strong>? <p class="text-danger fw-bold mt-1">CẢNH BÁO: Hành động này sẽ xóa vĩnh viễn tất cả vé (Chi Tiết Hóa Đơn) thuộc hóa đơn này.</p> </div> <div class="modal-footer"> <form id="deleteForm" method="POST" action=""> @csrf @method('DELETE') <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Không</button> <button type="submit" class="btn btn-danger">Có, Xóa!</button> </form> </div> </div> </div>
</div>
@endsection

@push('scripts')
<script>
    var deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget; var itemId = button.getAttribute('data-id'); var itemName = button.getAttribute('data-name');
        var modalTitle = deleteModal.querySelector('#itemName'); var deleteForm = deleteModal.querySelector('#deleteForm');
        modalTitle.textContent = itemName; var actionUrl = '{{ route("admin.hoadon.destroy", ":id") }}'; deleteForm.action = actionUrl.replace(':id', itemId);
    });
</script>
@endpush