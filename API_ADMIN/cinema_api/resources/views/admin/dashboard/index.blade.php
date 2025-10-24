@extends('admin.layouts.app') {{-- Hoặc layout của bạn --}}

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid py-4">
    {{-- Hàng 1: Các chỉ số chính --}}
    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Tổng Doanh Thu</p>
                                <h5 class="font-weight-bolder"> {{ number_format($totalRevenue, 0, ',', '.') }} VNĐ </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            {{-- Icon --}}
                            <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
             <div class="card">
                <div class="card-body p-3">
                    {{-- Tương tự cho Doanh Thu Hôm Nay --}}
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Doanh Thu Hôm Nay</p>
                    <h5 class="font-weight-bolder"> {{ number_format($todayRevenue, 0, ',', '.') }} VNĐ </h5>
                </div>
            </div>
        </div>
         <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
             <div class="card">
                <div class="card-body p-3">
                    {{-- Tương tự cho Tổng Khách Hàng --}}
                     <p class="text-sm mb-0 text-uppercase font-weight-bold">Tổng Số Khách Hàng</p>
                     <h5 class="font-weight-bolder">{{ $totalCustomers }}</h5>
                 </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
             <div class="card">
                <div class="card-body p-3">
                    {{-- Tương tự cho Tổng Hóa Đơn --}}
                     <p class="text-sm mb-0 text-uppercase font-weight-bold">Tổng Hóa Đơn</p>
                     <h5 class="font-weight-bolder">{{ $totalOrders }}</h5>
                </div>
            </div>
        </div>
         {{-- Thêm thẻ cho Khách hàng hôm nay và Hóa đơn hôm nay nếu cần --}}
    </div>

    {{-- Hàng 2: Biểu đồ --}}
    <div class="row mt-4">
        <div class="col-lg-7 mb-lg-0 mb-4">
            <div class="card z-index-2 h-100">
                <div class="card-header pb-0 pt-3 bg-transparent">
                    <h6 class="text-capitalize">Doanh thu 7 ngày gần nhất</h6>
                </div>
                <div class="card-body p-3">
                    <div class="chart">
                        {{-- Canvas cho Chart.js (Biểu đồ ngày) --}}
                        <canvas id="chart-line-daily" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
         <div class="col-lg-5">
             <div class="card z-index-2 h-100">
                 <div class="card-header pb-0 pt-3 bg-transparent">
                     <h6 class="text-capitalize">Doanh thu 12 tháng gần nhất</h6>
                 </div>
                 <div class="card-body p-3">
                     <div class="chart">
                         {{-- Canvas cho Chart.js (Biểu đồ tháng) --}}
                         <canvas id="chart-line-monthly" class="chart-canvas" height="300"></canvas>
                     </div>
                 </div>
             </div>
        </div>
    </div>

    {{-- Hàng 3: Top Lists --}}
    <div class="row mt-4">
        <div class="col-lg-6 mb-lg-0 mb-4">
            <div class="card ">
                <div class="card-header pb-0 p-3">
                     <h6 class="mb-0">Top 5 khách hàng chi tiêu cao nhất</h6>
                </div>
                <div class="card-body p-3">
                    <ul class="list-group">
                        @forelse($topCustomers as $customer)
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex align-items-center">
                                {{-- Icon or Avatar --}}
                                <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center"> <i class="ni ni-circle-08 text-white opacity-10"></i> </div>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark text-sm">{{ $customer->TenND }}</h6>
                                    <span class="text-xs">Mã KH: {{ $customer->MaND }}</span>
                                </div>
                            </div>
                            <div class="d-flex">
                                <button class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto">
                                    <span class="text-success text-sm font-weight-bolder">{{ number_format($customer->total_spent, 0, ',', '.') }} VNĐ</span>
                                </button>
                            </div>
                        </li>
                        @empty
                         <li class="list-group-item border-0 ps-0 text-sm">Không có dữ liệu.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
             <div class="card">
                 <div class="card-header pb-0 p-3">
                      <h6 class="mb-0">Top 5 phim doanh thu cao nhất (Đang chiếu)</h6>
                 </div>
                <div class="card-body p-3">
                    {{-- Tương tự, hiển thị $topMovies --}}
                     <ul class="list-group">
                        @forelse($topMovies as $movie)
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex align-items-center">
                                 <div class="icon icon-shape icon-sm me-3 bg-gradient-info shadow text-center"> <i class="ni ni-tv-2 text-white opacity-10"></i> </div>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark text-sm">{{ $movie->TenPhim }}</h6>
                                </div>
                            </div>
                            <div class="d-flex">
                                 <span class="text-info text-sm font-weight-bolder my-auto">{{ number_format($movie->movie_revenue, 0, ',', '.') }} VNĐ</span>
                            </div>
                        </li>
                         @empty
                         <li class="list-group-item border-0 ps-0 text-sm">Không có dữ liệu.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
{{-- Nhúng thư viện Chart.js (ví dụ: qua CDN) --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // === Biểu đồ Doanh thu Ngày ===
    var ctxDaily = document.getElementById("chart-line-daily").getContext("2d");
    new Chart(ctxDaily, {
        type: "line", // Kiểu biểu đồ đường
        data: {
            labels: @json($chartLabelsDaily), // Lấy labels từ controller
            datasets: [{
                label: "Doanh thu",
                tension: 0.4,
                borderWidth: 3,
                borderColor: "#5e72e4", // Màu đường line
                backgroundColor: 'rgba(94, 114, 228, 0.1)', // Màu nền dưới line (tùy chọn)
                fill: true,
                data: @json($chartDataDaily), // Lấy data từ controller
                maxBarThickness: 6
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                     ticks: {
                        // Định dạng tiền tệ cho trục Y
                        callback: function(value, index, values) {
                            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
                        }
                    }
                }
            },
            plugins: {
                 tooltip: {
                     callbacks: {
                         label: function(context) {
                             let label = context.dataset.label || '';
                             if (label) { label += ': '; }
                             if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                             }
                             return label;
                         }
                     }
                 }
             }
        },
    });

     // === Biểu đồ Doanh thu Tháng ===
    var ctxMonthly = document.getElementById("chart-line-monthly").getContext("2d");
    new Chart(ctxMonthly, {
        type: "bar", // Kiểu biểu đồ cột
        data: {
            labels: @json($chartLabelsMonthly), 
            datasets: [{
                label: "Doanh thu",
                borderWidth: 0,
                backgroundColor: "#3A416F", // Màu cột
                 // data: [50, 20, 10, 22, 50, 10, 40, 30, 25, 15, 45, 60], // Sample data
                data: @json($chartDataMonthly), 
                maxBarThickness: 20 // Độ rộng tối đa của cột
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
             scales: {
                y: {
                     beginAtZero: true,
                     ticks: {
                        callback: function(value, index, values) {
                            // Rút gọn số lớn (ví dụ: 1.000.000 -> 1tr)
                            if (value >= 1000000) return (value / 1000000) + 'tr';
                            if (value >= 1000) return (value / 1000) + 'k';
                            return value;
                        }
                    }
                },
                 x: {
                    grid: { display: false } // Ẩn đường kẻ dọc
                 }
            },
            plugins: {
                 tooltip: {
                     callbacks: {
                         label: function(context) {
                             let label = context.dataset.label || '';
                             if (label) { label += ': '; }
                             if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                             }
                             return label;
                         }
                     }
                 }
             }
        },
    });

</script>
@endpush