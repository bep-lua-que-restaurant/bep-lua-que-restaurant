@extends('layouts.admin')

@section('title')
    Thống kê doanh thu
@endsection

@section('content')
    <style>

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #ebebeb;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }

        .highcharts-data-table thead tr,
        .highcharts-data-table tbody tr:nth-child(even) {
            background: #f8f8f8;
        }

        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }
    </style>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-bold">DOANH THU <span id="phamViLoc">
                    @if ($boLoc == 'year') TRONG NĂM @elseif ($boLoc == 'month') TRONG THÁNG @elseif ($boLoc == 'week') TRONG TUẦN @else TRONG NGÀY @endif
                </span></h5>

                <h5 class="text-primary fw-bold">
                    <i class="bi bi-info-circle"></i>
                    <span id="tongDoanhSo">{{ number_format(array_sum($data), 0, ',', '.') }} VND</span>
                </h5>

                <form id="bieuMauLoc">
                    <div class="form-group d-flex align-items-center justify-content-between">
                        <select name="boLoc" id="boLoc" class="form-select mr-2" style="width: 135px">
                            <option value="year" {{ $boLoc == 'year' ? 'selected' : '' }}>Theo Năm</option>
                            <option value="month" {{ $boLoc == 'month' ? 'selected' : '' }}>Theo Tháng</option>
                            <option value="week" {{ $boLoc == 'week' ? 'selected' : '' }}>Theo Tuần</option>
                            <option value="day" {{ $boLoc == 'day' ? 'selected' : '' }}>Theo Ngày</option>
                        </select>

                        <div class="boLocTuyChinh">
                            <label for="ngayBatDau"><strong>Từ:</strong></label>
                            <input type="date" name="ngayBatDau" id="ngayBatDau" style="padding: 8px 12px; border-radius: 5px; border: 1px solid #ccc;">
                            <label for="ngayKetThuc"><strong>Đến:</strong></label>
                            <input type="date" name="ngayKetThuc" id="ngayKetThuc" style="padding: 8px 12px; border-radius: 5px; border: 1px solid #ccc;">
                            <button type="button" class="btn btn-primary" id="btnFilter">Lọc</button>
                        </div>
                    </div>
                </form>
                <!-- Biểu đồ -->
{{--                <canvas id="thongKeDoanhSo" height="100"></canvas>--}}

                <figure class="highcharts-figure">
                    <div id="thongKeDoanhSo" style="height: 430px;"></div>
                </figure>
            </div>
        </div>
    </div>

    <!-- Nhúng Chart.js -->
{{--    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>--}}
    <!-- Thay thế Chart.js bằng Highcharts -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        window.labels = @json($labels);
        window.data = @json($data);
        window.boLoc = "{{ $boLoc }}";
    </script>
    <script src="{{ asset('admin/js/thong-ke-doanh-thu.js') }}"></script>
@endsection
