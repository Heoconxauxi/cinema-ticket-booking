@extends('admin.layouts.app')

@section('title', 'Danh sách Người Dùng')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center pb-0">
                    <h5>Danh sách Người Dùng</h5>

                    <div class="d-flex align-items-center">
                        {{-- Per Page Form --}}
                        <form method="GET" action="{{ route('admin.nguoidung.index') }}" class="d-flex align-items-center me-2">
                            @if (request('searchString')) <input type="hidden" name="searchString" value="{{ $search }}"> @endif
                            <select class="form-select me-2" name="per_page" onchange="this.form.submit()">
                                <option value="10" {{ $per_page == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ $per_page == 20 ? 'selected' : '' }}>20</option>
                                <option value="50" {{ $per_page == 50 ? 'selected' : '' }}>50</option>
                            </select>
                        </form>
                        {{-- Search Form --}}
                        <form method="GET" action="{{ route('admin.nguoidung.index') }}" class="d-flex align-items-center me-2">
                            @if (request('per_page')) <input type="hidden" name="per_page" value="{{ $per_page }}"> @endif
                            <div class="input-group">
                                <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                                <input type="search" name="searchString" class="form-control" placeholder="Tìm tên, email, SĐT, username..." value="{{ $search ?? '' }}">
                            </div>
                        </form>
                        {{-- Add Button --}}
                        {{-- <a href="{{ route('admin.nguoidung.create') }}" class="btn btn-purple flex-shrink-0">
                        <i class="bi bi-plus"></i> Thêm
                        </a> --}}
                    </div>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table table-striped table-borderless align-items-center mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Ảnh</th>
                                    <th>Tên Người Dùng</th>
                                    <th>Tên Đăng Nhập</th>
                                    <th>Email</th>
                                    <th>SĐT</th>
                                    <th>Quyền</th>
                                    <th>Trạng Thái</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($list_nguoidung as $user)
                                <tr>
                                    <td>{{ $loop->iteration + ($list_nguoidung->currentPage() - 1) * $list_nguoidung->perPage() }}</td>
                                    <td>
                                        {{-- Đặt đường dẫn đến ảnh đại diện mặc định của bạn --}}
                                        @php $defaultAvatarUrl = 'https://static.thenounproject.com/png/363640-200.png'; @endphp
                                        <img 
                                            src="{{ $user->Anh ?: $defaultAvatarUrl }}" 
                                            alt="Avatar" 
                                            class="avatar me-3 border rounded-circle" {{-- Bỏ class avatar-sm, thêm rounded-circle --}}
                                            style="width: 18px; height: 18px; object-fit: cover;" {{-- Set kích thước nhỏ hơn --}}
                                            onerror="this.onerror=null; this.src='{{ $defaultAvatarUrl }}';">
                                    </td>
                                    <td>{{ $user->TenND }}</td>

                                    {{-- THAY ĐỔI: Truy cập trực tiếp $user->TenDangNhap --}}
                                    <td>{{ $user->TenDangNhap ?? 'Lỗi' }}</td>

                                    <td>{{ $user->Email ?? '(Trống)' }}</td>
                                    <td>{{ $user->SDT ?? '(Trống)' }}</td>
                                    <td>
                                        @php
                                        $roleName = $roles[$user->Quyen] ?? 'Không xác định';
                                        @endphp
                                        <span class="badge bg-dark text-white rounded-pill px-3 py-2">{{ $roleName }}</span>
                                    </td>
                                    <td>
                                        @if ($user->TrangThai == 1) <span class="badge bg-success">ON</span> @else <span class="badge bg-secondary">OFF</span> @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.nguoidung.show', $user->MaND) }}" class="btn btn-secondary btn-sm">XEM</a>
                                        <a href="{{ route('admin.nguoidung.edit', $user->MaND) }}" class="btn btn-info btn-sm">SỬA</a>
                                        {{-- Prevent deleting the currently logged-in user or the primary admin (optional) --}}
                                        @if (auth()->id() != $user->MaND && $user->MaND != 1) {{-- Assuming MaND 1 is the main admin --}}
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="{{ $user->MaND }}" data-name="{{ $user->TenND }}">
                                            XOÁ
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">Không có bản ghi nào</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $list_nguoidung->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1"> {{-- Modal giống các module khác --}}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Xác Nhận Xóa</h5> <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body"> Bạn có chắc muốn xóa người dùng <strong id="itemName"></strong>? <p class="text-danger mt-1">Hành động này sẽ xóa cả tài khoản đăng nhập.</p>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST" action=""> @csrf @method('DELETE') <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Không</button> <button type="submit" class="btn btn-danger">Có, Xóa!</button> </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    var deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var itemId = button.getAttribute('data-id');
        var itemName = button.getAttribute('data-name');
        var modalTitle = deleteModal.querySelector('#itemName');
        var deleteForm = deleteModal.querySelector('#deleteForm');
        modalTitle.textContent = itemName;
        var actionUrl = '{{ route("admin.nguoidung.destroy", ":id") }}';
        deleteForm.action = actionUrl.replace(':id', itemId);
    });

</script>
@endpush
