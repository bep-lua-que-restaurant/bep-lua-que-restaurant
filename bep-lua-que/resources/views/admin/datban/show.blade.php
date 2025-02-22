@extends('layouts.admin')

@section('title')
    Danh mục Bàn Ăn
@endsection

@section('content')
    <div>
        <h3>Thông tin đặt bàn của khách hàng</h3>
        <p><strong>Tên khách hàng: </strong>{{ $datBan->khachHang->ho_ten }}</p>
        <p><strong>Số điện thoại: </strong>{{ $datBan->khachHang->so_dien_thoai }}</p>
        <p><strong>Căn cước: </strong>{{ $datBan->khachHang->can_cuoc }}</p>
        <p><strong>Thời gian đến: </strong>{{ $datBan->thoi_gian_den }}</p>
        <p><strong>Mô tả: </strong>{{ $datBan->mo_ta }}</p>

        <h4>Bàn đã đặt:</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Phòng ăn</th>
                    <th>Tên bàn</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($datBans as $datBanItem)
                    <tr>
                        <td>{{ $datBanItem->banAn->phongAn->ten_phong_an }}</td>
                        <td>{{ $datBanItem->banAn->ten_ban }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <a class="btn btn-primary" href="/dat-ban/">quay lai</a>
@endsection
