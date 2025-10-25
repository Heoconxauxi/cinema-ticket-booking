@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div id="toast"></div>

<!-- Display Laravel session flash messages -->
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center pb-0">
                    <h5>Dashboard</h5>
                    <!-- Add any action buttons if needed -->
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <!-- Top Section: Revenue Stats -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-gradient-purple text-white p-3">
                                <h6 class="card-title">Tổng Doanh Thu (VND)</h6>
                                <h3 class="card-text">39,509,320 VND</h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-gradient-primary text-white p-3">
                                <h6 class="card-title">Doanh Thu Hôm Nay (VND)</h6>
                                <h3 class="card-text">153,400 VND</h3> <!-- Updated for 12:41 PM on 22-10-2025 -->
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-gradient-success text-white p-3">
                                <h6 class="card-title">Tổng Số Khách Hàng</h6>
                                <h3 class="card-text">8</h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-gradient-danger text-white p-3">
                                <h6 class="card-title">Tổng Hóa Đơn (Đơn)</h6>
                                <h3 class="card-text">27</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Middle Section: Charts -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-dark text-white">
                                    <h6>Doanh thu theo ngày</h6>
                                </div>
                                <div class="card-body p-0">
                                    <div id="doanhThuChart" style="height: 200px; background-color: #1a2035;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h6>Xu hướng doanh thu</h6>
                                    <p class="text-muted small">Ngày 2025/10/22 (153,400 VND) - 99.5% tăng so với ngày 2024 (27,889,140 VND)</p>
                                </div>
                                <div class="card-body p-0">
                                    <div id="xuHuongChart" style="height: 200px; background-color: #fff;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Section: Top 5 Lists -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-dark text-white">
                                <div class="card-header">
                                    <h6>Top 5 khách hàng chi tiêu cao nhất</h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="list-group list-group-flush">
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            Nguyễn Thị Duy Dần
                                            <span class="badge bg-light text-dark">100,000 VND</span>
                                        </div>
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            Trần Duy Phất
                                            <span class="badge bg-light text-dark">80,000 VND</span>
                                        </div>
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            Phất Duy
                                            <span class="badge bg-light text-dark">60,000 VND</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-primary text-white">
                                <div class="card-header">
                                    <h6>Top 5 bộ phim đang chiếu doanh cao nhất</h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="list-group list-group-flush">
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            Thiên Đường Quá Bộ
                                            <span class="badge bg-light text-dark">120,000 VND</span>
                                        </div>
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            Venom: Kẻ Cuối
                                            <span class="badge bg-light text-dark">90,000 VND</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .bg-gradient-purple {
        background: linear-gradient(135deg, #6f42c1, #5a2d9e);
    }
    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff, #0056b3);
    }
    .bg-gradient-success {
        background: linear-gradient(135deg, #28a745, #1e7e34);
    }
    .bg-gradient-danger {
        background: linear-gradient(135deg, #dc3545, #c82333);
    }
    #doanhThuChart, #xuHuongChart {
        position: relative;
        overflow: hidden;
    }
    .card-header.bg-dark {
        background-color: #1a2035 !important;
    }
    .card-header.bg-primary {
        background-color: #007bff !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Bar Chart for Doanh thu theo ngày
    const doanhThuChart = new Chart(document.getElementById('doanhThuChart'), {
        type: 'bar',
        data: {
            labels: ['16-10', '17-10', '18-10', '19-10', '20-10', '21-10', '22-10'],
            datasets: [{
                label: 'Doanh thu (VND)',
                data: [0, 50000, 150000, 100000, 200000, 150000, 153400], // Updated for 12:41 PM on 22-10-2025
                backgroundColor: '#6f42c1',
                borderColor: '#6f42c1',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Line Chart for Xu hướng doanh thu
    const xuHuongChart = new Chart(document.getElementById('xuHuongChart'), {
        type: 'line',
        data: {
            labels: ['Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'Th8', 'Th9', 'Th10', 'Th11', 'Th12'],
            datasets: [{
                label: 'Doanh thu (VND)',
                data: [0, 50000, 150000, 100000, 200000, 150000, 100000, 50000, 0, 153400], // Updated for October 22
                backgroundColor: 'rgba(111, 66, 193, 0.2)',
                borderColor: '#6f42c1',
                borderWidth: 2,
                tension: 0.4
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush