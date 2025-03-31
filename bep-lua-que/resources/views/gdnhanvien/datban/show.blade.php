@extends('gdnhanvien.datban.layout')

@section('title')
    Chi tiết Đặt Bàn
@endsection

@section('content')
    <div>
        <h3>Thông tin đặt bàn của khách hàng</h3>
        <p><strong>Tên khách hàng: </strong>{{ $datBan->khachHang->ho_ten }}</p>
        <p><strong>Số điện thoại: </strong>{{ $datBan->khachHang->so_dien_thoai }}</p>
        <p><strong>Email: </strong>{{ $datBan->khachHang->email }}</p>
        <p><strong>Thời gian đến: </strong>{{ \Carbon\Carbon::parse($datBan->thoi_gian_den)->format('d/m/Y H:i') }}</p>
        <p><strong>Mô tả: </strong>{{ $datBan->mo_ta ?? 'Không có mô tả' }}</p>

        <h4>Bàn đã đặt:</h4>

        <div class="m-3">
            @foreach ($datBans as $datBanItem)
                {{-- <td>{{ optional($datBanItem->banAn->phongAn)->ten_phong_an ?? 'Không xác định' }}</td> --}}
                <div class="btn sm border border-primary">{{ optional($datBanItem->banAn)->ten_ban ?? 'Không xác định' }}
                </div>
            @endforeach
        </div>

    </div>
    <a class="btn btn-primary" href="{{ route('datban.danhsach') }}">Quay lại</a>
@endsection
