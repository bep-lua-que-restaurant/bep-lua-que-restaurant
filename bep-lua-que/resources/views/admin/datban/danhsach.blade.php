@extends('admin.datban.layout')

@section('title')
    Danh mục Bàn Ăn
@endsection

@section('content')
    <div class="row">
        <div id="booked-list-section">
            <h2>Danh sách bàn đã được đặt</h2>

            <!-- Bộ lọc tìm kiếm -->
            <div class="row d-flex p-3">
                <div class="col-9">
                    <input type="text" name="searchBanDat" id="searchBanDat" class="form-control"
                        placeholder="Tìm theo họ tên hoặc số điện thoại">
                </div>
                <div class="col-3">
                    <select name="trang_thai" id="trang_thai" class="form-control">
                        <option value="">Tất cả trạng thái</option>
                        <option value="dang_xu_ly">Đang xử lý</option>
                        <option value="xac_nhan">Xác nhận</option>
                        <option value="da_huy">Đã hủy</option>
                    </select>
                </div>
            </div>

            <!-- Bảng dữ liệu -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Thời Gian Đến</th>
                        <th>Họ Tên</th>
                        <th>Số Điện Thoại</th>
                        <th>Số Người</th>
                        <th>Danh Sách Bàn</th>
                        <th>Trạng Thái</th>
                        <th>Mô Tả</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <!-- Dữ liệu sẽ được load ở đây bằng AJAX -->
                </tbody>
            </table>

        </div>
    </div>
    {{-- {{ $banhSachDatban->links('pagination::bootstrap-5') }} --}}


    <script>
        $(document).ready(function() {
            function loadData() {
                let search = $('#searchBanDat').val();
                let trang_thai = $('#trang_thai').val();

                $.ajax({
                    url: "{{ route('datban.filter') }}",
                    method: "GET",
                    data: {
                        search: search,
                        trang_thai: trang_thai
                    },
                    success: function(response) {
                        let rows = '';
                        if (response.length > 0) {
                            $.each(response, function(index, datban) {
                                let trangThaiText = '';
                                if (datban.trang_thai === 'dang_xu_ly') {
                                    trangThaiText = 'Đang xử lý';
                                } else if (datban.trang_thai === 'xa_nhan') {
                                    trangThaiText = 'Đã xác nhận';
                                } else if (datban.trang_thai === 'da_huy') {
                                    trangThaiText = 'Đã hủy';
                                } else {
                                    trangThaiText = 'Đã xác nhận';
                                }

                                rows += `
                            <tr>
                                <td>${datban.thoi_gian_den}</td>
                                <td>${datban.ho_ten}</td>
                                <td>${datban.so_dien_thoai}</td>
                                <td>${datban.so_nguoi}</td>
                                <td>${datban.danh_sach_ban}</td>
                                <td>${trangThaiText}</td>
                                <td>
                                    <a href="/dat-ban/${datban.id}" class="btn btn-info btn-sm" title="Xem chi tiết">Xem</a>
                        `;

                                if (datban.trang_thai === 'dang_xu_ly') {
                                    rows += `
                                <form action="/dat-ban/${datban.id}" method="post">
                                    @csrf
                                    @method('PUT')
                                    <input type="submit" class="btn btn-warning btn-sm" value="Xác nhận">
                                </form>
                                <form action="/dat-ban/${datban.id}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <input type="submit" class="btn btn-danger btn-sm mt-22" value="Hủy đặt">
                                </form>
                            `;
                                }

                                rows += `</td></tr>`;
                            });
                        } else {
                            rows =
                                `<tr><td colspan="8" class="text-center">Không tìm thấy dữ liệu</td></tr>`;
                        }
                        $('#tableBody').html(rows);
                    }
                });
            }

            // Load dữ liệu ban đầu
            loadData();

            // Tìm kiếm khi nhập vào ô input
            $('#searchBanDat').on('keyup', function() {
                loadData();
            });

            // Lọc theo trạng thái khi thay đổi select
            $('#trang_thai').on('change', function() {
                loadData();
            });

            // Tự động tải lại dữ liệu mỗi 5 giây
            setInterval(loadData, 5000);
        });
    </script>

    </div>

    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    @vite('resources/js/datban.js')
@endsection
