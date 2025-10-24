@extends('admin.layouts.app')

@section('title', 'Quản lý Menu')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center pb-0">
                    <h5>Quản lý Menu</h5>

                    <div class="d-flex align-items-center">
                        <form method="GET" action="{{ route('admin.menu.index') }}" class="d-flex align-items-center me-2">
                             @if (request('searchString')) <input type="hidden" name="searchString" value="{{ $search }}"> @endif
                            <select class="form-select me-2" name="per_page" onchange="this.form.submit()">
                                <option value="10" {{ $per_page == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ $per_page == 20 ? 'selected' : '' }}>20</option>
                                <option value="50" {{ $per_page == 50 ? 'selected' : '' }}>50</option>
                            </select>
                        </form>

                        <form method="GET" action="{{ route('admin.menu.index') }}" class="d-flex align-items-center me-2">
                            @if (request('per_page')) <input type="hidden" name="per_page" value="{{ $per_page }}"> @endif
                            <div class="input-group">
                                <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                                <input type="search" name="searchString" class="form-control" placeholder="Tìm tên menu, vị trí..." 
                                       value="{{ $search ?? '' }}">
                            </div>
                        </form>

                        <a href="{{ route('admin.menu.create') }}" class="btn btn-purple flex-shrink-0">
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
                                    <th>Tên Menu</th>
                                    <th>Kiểu</th>
                                    <th>Vị Trí</th>
                                    <th>Liên Kết / ID</th>
                                    <th>Thứ Tự</th>
                                    <th>Trạng Thái</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($list_menu as $item)
                                <tr>
                                    <td>{{ $loop->iteration + ($list_menu->currentPage() - 1) * $list_menu->perPage() }}</td>
                                    <td>{{ $item->TenMenu }}</td>
                                    <td>{{ $item->KieuMenu }}</td>
                                    <td>{{ $item->ViTri }}</td>
                                    <td>{{ $item->KieuMenu == 'Custom' ? $item->LienKet : $item->TableId ?? '(Trống)' }}</td>
                                    <td>{{ $item->Order }}</td>
                                    <td>
                                        @if ($item->TrangThai == 1) <span class="badge bg-success">ON</span> @else <span class="badge bg-secondary">OFF</span> @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.menu.edit', $item->Id) }}" class="btn btn-info btn-sm">SỬA</a>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal" 
                                                data-id="{{ $item->Id }}" 
                                                data-name="{{ $item->TenMenu }}">
                                            XOÁ
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr> <td colspan="8" class="text-center">Không có bản ghi nào</td> </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $list_menu->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1"> {{-- Nội dung modal giống các module khác --}} </div>
@endsection

@push('scripts')
<script> /* JS cho modal giống các module khác */ </script>
@endpush