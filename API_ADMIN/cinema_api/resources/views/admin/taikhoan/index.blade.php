@extends('admin.layouts.app')

@section('title', 'Danh sách Tài Khoản')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center pb-0">
                    <h5>Danh sách Tài Khoản</h5>

                    <div class="d-flex align-items-center">
                        {{-- Per Page Form --}}
                        <form method="GET" action="{{ route('admin.taikhoan.index') }}" class="d-flex align-items-center me-2">
                             @if (request('searchString')) <input type="hidden" name="searchString" value="{{ $search }}"> @endif
                            <select class="form-select me-2" name="per_page" onchange="this.form.submit()">
                                <option value="10" {{ $per_page == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ $per_page == 20 ? 'selected' : '' }}>20</option>
                                <option value="50" {{ $per_page == 50 ? 'selected' : '' }}>50</option>
                            </select>
                        </form>
                        {{-- Search Form --}}
                        <form method="GET" action="{{ route('admin.taikhoan.index') }}" class="d-flex align-items-center me-2">
                            @if (request('per_page')) <input type="hidden" name="per_page" value="{{ $per_page }}"> @endif
                            <div class="input-group">
                                <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                                <input type="search" name="searchString" class="form-control" placeholder="Tìm tên đăng nhập, tên ND..." 
                                       value="{{ $search ?? '' }}">
                            </div>
                        </form>
                        {{-- Add Button --}}
                        <a href="{{ route('admin.taikhoan.create') }}" class="btn btn-purple flex-shrink-0">
                            <i class="bi bi-plus"></i> Thêm
                        </a>
                    </div>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table table-striped table-borderless align-items-center mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID (MaND)</th>
                                    <th>Tên Đăng Nhập</th>
                                    <th>Tên Người Dùng</th>
                                    <th>Quyền</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($list_taikhoan as $tk)
                                <tr>
                                    <td>{{ $tk->MaND }}</td>
                                    <td>{{ $tk->TenDangNhap }}</td>
                                    <td>{{ $tk->TenND }}</td> {{-- Lấy tên từ bảng taikhoan --}}
                                    <td>
                                        @php
                                            $roleName = $roles[$tk->Quyen] ?? 'Không xác định';
                                            $roleClass = match($tk->Quyen) { 0 => 'info', 1 => 'danger', 2 => 'warning', default => 'secondary' };
                                        @endphp
                                         <span class="bg-gradient-dark-{{ $roleClass }}">{{ $roleName }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.taikhoan.edit', $tk->MaND) }}" class="btn btn-info btn-sm">SỬA</a>
                                        {{-- Ngăn xóa admin chính và user đang đăng nhập --}}
                                        @if ($tk->MaND != 3 && $tk->MaND != auth()->id())
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal" 
                                                data-id="{{ $tk->MaND }}" 
                                                data-name="{{ $tk->TenDangNhap }}">
                                            XOÁ
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr> <td colspan="5" class="text-center">Không có bản ghi nào</td> </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $list_taikhoan->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1"> {{-- Modal giống các module khác --}} 
     <div class="modal-dialog"> <div class="modal-content"> <div class="modal-header"> <h5 class="modal-title" id="deleteModalLabel">Xác Nhận Xóa</h5> <button type="button" class="btn-close" data-bs-dismiss="modal"></button> </div> <div class="modal-body"> Bạn có chắc muốn xóa tài khoản <strong id="itemName"></strong>? <p class="text-danger fw-bold mt-1">CẢNH BÁO: Hành động này sẽ xóa vĩnh viễn cả hồ sơ người dùng liên kết (do ON DELETE CASCADE).</p> </div> <div class="modal-footer"> <form id="deleteForm" method="POST" action=""> @csrf @method('DELETE') <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Không</button> <button type="submit" class="btn btn-danger">Có, Xóa!</button> </form> </div> </div> </div>
</div>
@endsection

@push('scripts')
<script>
    var deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget; var itemId = button.getAttribute('data-id'); var itemName = button.getAttribute('data-name');
        var modalTitle = deleteModal.querySelector('#itemName'); var deleteForm = deleteModal.querySelector('#deleteForm');
        modalTitle.textContent = itemName; var actionUrl = '{{ route("admin.taikhoan.destroy", ":id") }}'; deleteForm.action = actionUrl.replace(':id', itemId);
    });
</script>
@endpush