@extends('admin.layouts.app')

@section('title', 'Danh sách Slider')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center pb-0">
                    <h5>Danh sách Slider</h5>

                    <div class="d-flex align-items-center">
                        <form method="GET" action="{{ route('admin.slider.index') }}" class="d-flex align-items-center me-2">
                            @if (request('searchString'))
                            <input type="hidden" name="searchString" value="{{ $search }}">
                            @endif
                            <select class="form-select me-2" name="per_page" onchange="this.form.submit()">
                                <option value="5" {{ $per_page == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ $per_page == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ $per_page == 20 ? 'selected' : '' }}>20</option>
                            </select>
                        </form>

                        <form method="GET" action="{{ route('admin.slider.index') }}" class="d-flex align-items-center me-2">
                            @if (request('per_page'))
                            <input type="hidden" name="per_page" value="{{ $per_page }}">
                            @endif
                            <div class="input-group">
                                <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                                <input type="search" name="searchString" class="form-control" placeholder="Tìm tên slider, vị trí, phim..." value="{{ $search ?? '' }}">
                            </div>
                        </form>

                        <a href="{{ route('admin.slider.create') }}" class="btn btn-purple flex-shrink-0">
                            <i class="bi bi-plus"></i> Thêm
                        </a>
                    </div>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table table-striped table-borderless align-items-center mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Ảnh</th>
                                    <th>Tên Slider</th>
                                    <th>Vị Trí</th>
                                    <th>Thứ Tự</th>
                                    <th>Phim Liên Kết</th>
                                    <th>Trạng Thái</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($list_slider as $item)
                                <tr>
                                    <td>{{ $loop->iteration + ($list_slider->currentPage() - 1) * $list_slider->perPage() }}</td>
                                    <td>
                                        @if ($item->Anh)
                                        <img src="{{ $item->Anh }}" alt="{{ $item->TenSlider }}" style="width: 150px; height: auto; object-fit: contain;" onerror="this.style.display='none'; this.closest('td').innerText = '(Lỗi ảnh)';">
                                        @else
                                        (Không có ảnh)
                                        @endif
                                    </td>
                                    </td>
                                    <td>{{ $item->TenSlider }}</td>
                                    <td>{{ $item->ViTri }}</td>
                                    <td>{{ $item->SapXep }}</td>
                                    <td>{{ $item->phim->TenPhim ?? '(Không có)' }}</td>
                                    <td>
                                        @if ($item->TrangThai == 1)
                                        <span class="badge bg-success">ON</span>
                                        @else
                                        <span class="badge bg-secondary">OFF</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.slider.show', $item->Id) }}" class="btn btn-secondary btn-sm">XEM</a>
                                        <a href="{{ route('admin.slider.edit', $item->Id) }}" class="btn btn-info btn-sm">SỬA</a>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="{{ $item->Id }}" data-name="{{ $item->TenSlider }}">
                                            XOÁ
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Không có bản ghi nào</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $list_slider->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    {{-- Nội dung modal giống các module khác --}}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Xác Nhận Xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc muốn xóa slider <strong id="itemName"></strong>?
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST" action="">
                    @csrf @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Không</button>
                    <button type="submit" class="btn btn-danger">Có, Xóa!</button>
                </form>
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
        var actionUrl = '{{ route("admin.slider.destroy", ":id") }}';
        deleteForm.action = actionUrl.replace(':id', itemId);
    });

</script>
@endpush
