<div class="container">
    <h2 class="text-center">Danh Sách Đặt Bàn</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Thời Gian Đến</th>
                <th>Họ Tên</th>
                <th>Số Điện Thoại</th>
                <th>Số Người</th>
                <th>Danh Sách Bàn</th>
                <th>Trạng Thái</th>
                <th>Hoat dong</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($banhSachDatban as $datban)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($datban->thoi_gian_den)->format('d/m/Y H:i') }}</td>
                    <td>{{ $datban->ho_ten }}</td>
                    <td>{{ $datban->so_dien_thoai }}</td>
                    <td>{{ $datban->so_nguoi }}</td>
                    <td>
                        <span class="badge bg-primary">{{ $datban->danh_sach_ban }}</span>
                    </td>
                    <td>
                        @if ($datban->trang_thai == 'xac_nhan')
                            <span class="badge bg-success">Đã xác nhận</span>
                        @elseif ($datban->trang_thai == 'dang_xu_ly')
                            <span class="badge bg-warning">Đang xử lý</span>
                        @else
                            <span class="badge bg-danger">Đã hủy</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('dat-ban.show', $datban->datban_id) }}" class="btn btn-primary btn-sm">Xem</a>
                        @if ($datban->trang_thai === 'dang_xu_ly')
                            {{-- <form action="dat-ban/${datban.datban_id}" method="post"> --}}
                            <form action="{{ route('dat-ban.update', $datban->datban_id) }}" method="post">

                                @csrf
                                @method('PUT')
                                <input type="submit" class="btn btn-warning btn-sm  mt-2" value="Xác nhận">
                            </form>
                            {{-- <form action="{{ route('dat-ban/', $datban->datban_id) }}" method="post"> --}}
                            <form action="{{ route('dat-ban.destroy', $datban->datban_id) }}" method="post">

                                @csrf
                                @method('DELETE')
                                <input type="submit" class="btn btn-danger btn-sm mt-2" value="Hủy đặt">
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>



{{-- <script>
    $(document).ready(function() {
        function loadData(page = 1) {
            let search = $('#searchBanDat').val();
            let trang_thai = $('#trang_thai').val();

            $.ajax({
                url: "{{ route('datban.filter') }}?page=" + page,
                method: "GET",
                data: {
                    search: search,
                    trang_thai: trang_thai
                },
                success: function(response) {
                    let rows = '';
                    if (response.data.length > 0) {
                        $.each(response.data, function(index, datban) {
                            let trangThaiText = datban.trang_thai === 'dang_xu_ly' ?
                                'Đang xử lý' :
                                datban.trang_thai === 'da_xac_nhan' ? 'Đã xác nhận' :
                                datban.trang_thai === 'da_huy' ? 'Đã hủy' : 'Đã xác nhận';

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
                                <input type="submit" class="btn btn-danger btn-sm mt-2" value="Hủy đặt">
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

                    // Cập nhật phân trang
                    $('#paginationLinks').html(response.pagination);
                }
            });
        }

        // Gọi AJAX khi nhấn nút phân trang
        $(document).on('click', '#paginationLinks a', function(event) {
            event.preventDefault();
            let page = $(this).attr('href').split('page=')[1];
            loadData(page);
        });

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
</script> --}}
