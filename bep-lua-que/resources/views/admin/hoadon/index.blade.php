@extends('layouts.admin')

@section('title', 'Hóa đơn')

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Hóa đơn</a></li>
                </ol>
            </div>
        </div>

        <!-- Form tìm kiếm -->
        <div class="row">
            <div class="col-lg-12">
                <form method="GET" action="{{ route('hoa-don.index') }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" id="search" name="search" class="form-control"
                            placeholder="Tìm kiếm hóa đơn..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Danh sách hóa đơn -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Danh sách hóa đơn</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th style="vertical-align: middle;">ID</th>
                                        <th style="vertical-align: middle;">Mã Hóa Đơn</th>
                                        <th style="vertical-align: middle;">Khách Hàng</th>
                                        <th style="vertical-align: middle;">Số điện thoại</th>
                                        <th style="vertical-align: middle;">Tổng Tiền</th>
                                        <th style="vertical-align: middle;">Phương Thức Thanh Toán</th>
                                        <th style="vertical-align: middle;">Ngày Tạo</th>
                                        <th style="vertical-align: middle;">Hành động</th>
                                    </tr>
                                </thead>

                                <tbody id="hoaDonTableBody" class="text-center">
                                    @include('admin.hoadon.listhoadon')
                                </tbody>
                            </table>
                        </div>
                        <div id="pagination" class="d-flex justify-content-center mt-3">
                            {{ $hoa_don->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    @foreach ($hoa_don as $hoa_dons)
        <!-- Nội dung hóa đơn cần in -->
        <div id="print-area-{{ $hoa_dons->id }}"
            style="display: none; font-family: Arial, sans-serif; width: 320px; padding: 20px; border: 3px solid #000; background: #fff; 
            border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">

            <!-- Header -->
            <div style="text-align: center; border-bottom: 2px dashed #000; padding-bottom: 10px;">
                <h2 style="margin: 0; color: #000;">🍽️ Nhà Hàng Bếp Lửa Quê</h2>
            </div>

            <h3 style="text-align: center; margin: 15px 0; text-transform: uppercase; color: #000;">HÓA ĐƠN THANH TOÁN
            </h3>

            <!-- Thông tin hóa đơn -->
            <p><strong>Mã hóa đơn:</strong> {{ $hoa_dons->ma_hoa_don }}</p>
            <p><strong>Ngày:</strong> {{ \Carbon\Carbon::parse($hoa_dons->ngay_tao)->format('d/m/Y H:i') }}</p>


            <p><strong>Khách hàng:</strong> {{ $hoa_dons->ten_ban }}</p>




            <p><strong>Khách hàng:</strong> {{ $hoa_dons->ho_ten }}</p>
            <p><strong>Số điện thoại:</strong> {{ $hoa_dons->so_dien_thoai }}</p>

            <hr style="border-top: 1px dashed #000;">

            <!-- Danh sách món ăn -->
            <table
                style="width: 100%; border-collapse: collapse; font-size: 14px; border: 2px solid #000; border-radius: 10px; overflow: hidden;">
                <thead>
                    <tr style="background: #f8f8f8; text-align: center;">
                        <th style="border-bottom: 2px solid #000; padding: 8px;">STT</th>
                        <th style="border-bottom: 2px solid #000; padding: 8px;">Món ăn</th>
                        <th style="border-bottom: 2px solid #000; padding: 8px;">SL</th>
                        <th style="border-bottom: 2px solid #000; padding: 8px;">Giá</th>
                        <th style="border-bottom: 2px solid #000; padding: 8px;">Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($hoa_dons->chiTietHoaDons && count($hoa_dons->chiTietHoaDons) > 0)
                        @foreach ($hoa_dons->chiTietHoaDons as $chiTiet)
                            <tr style="border-bottom: 1px solid #ddd;">
                                <td style="padding: 8px; text-align: center;">{{ $loop->iteration }}</td>
                                <td style="padding: 8px;">{{ $chiTiet->monAn->ten ?? 'Không có' }}</td>
                                <td style="padding: 8px; text-align: center;">{{ $chiTiet->so_luong ?? 0 }}</td>
                                <td style="padding: 8px; text-align: right;">
                                    {{ number_format($chiTiet->monAn->gia ?? 0, 0, ',', '.') }} VNĐ
                                </td>
                                <td style="padding: 8px; text-align: right;">
                                    {{ number_format($chiTiet->thanh_tien ?? 0, 0, ',', '.') }} VNĐ
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 10px;">Không có dữ liệu</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <hr style="border-top: 1px dashed #000;">

            <!-- Tổng cộng -->
            <p style="text-align: right; font-size: 18px; font-weight: bold; color: #000;">
                Tổng cộng: {{ number_format($hoa_dons->tong_tien, 0, ',', '.') }} VNĐ
            </p>

            <p style="text-align: center; font-style: italic; margin-top: 15px; color: #000;">
                Cảm ơn quý khách! Hẹn gặp lại! 🎉
            </p>
        </div>
    @endforeach

@endsection

<!-- Script in hóa đơn -->
<script>
    function printInvoice(id) {
        var content = document.getElementById('print-area-' + id).innerHTML;
        var myWindow = window.open('', '', 'width=800,height=1000'); // Tăng kích thước cửa sổ in
        myWindow.document.write('<html><head><title>In Hóa Đơn</title>');
        myWindow.document.write('<style>');
        myWindow.document.write('body { font-size: 18px; padding: 20px; }'); // Tăng kích thước chữ và thêm padding
        myWindow.document.write('</style></head><body>');
        myWindow.document.write(content);
        myWindow.document.write('</body></html>');
        myWindow.document.close();
        myWindow.print();
    }

    // JavaScript (Blade Template)
    document.addEventListener("DOMContentLoaded", function() {
        let searchInput = document.getElementById("search");

        if (searchInput) {
            searchInput.addEventListener("input", function() {
                let query = this.value.trim(); // Lấy dữ liệu nhập vào

                // Nếu input rỗng, không gọi AJAX
                if (query === "") {
                    fetchData("{{ route('hoa-don.index') }}", {});
                    return;
                }

                fetchData("{{ route('hoa-don.index') }}", {
                    search: query
                });
            });
        }

        // Xử lý phân trang AJAX
        $(document).on("click", "#pagination a", function(e) {
            e.preventDefault();
            let url = $(this).attr("href");
            let search = $("#search").val().trim(); // Lấy giá trị tìm kiếm nếu có

            $.ajax({
                url: url,
                type: "GET",
                data: {
                    search: search
                },
                success: function(response) {
                    $("#hoaDonTableBody").html(response.html);
                    $("#pagination").html(response.pagination);
                },
                error: function(xhr) {
                    console.log("Lỗi AJAX:", xhr.responseText);
                }
            });
        });


        function fetchData(url, params = {}) {
            fetch(url + '?' + new URLSearchParams(params), {
                    method: "GET",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById("hoaDonTableBody").innerHTML = data.html;
                    document.getElementById("pagination").innerHTML = data.pagination;
                })
                .catch(error => console.error("Lỗi AJAX:", error));
        }
    });
</script>
