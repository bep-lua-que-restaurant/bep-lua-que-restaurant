@foreach ($hoa_don as $hoa_dons)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td class="fw-bold text-primary">{{ $hoa_dons->ma_hoa_don }}</td>
        <td>{{ $hoa_dons->ho_ten }}</td>
        <td>{{ $hoa_dons->so_dien_thoai }}</td>
        <td class="text-danger fw-bold">
            {{ number_format($hoa_dons->tong_tien, 0, ',', '.') }} VNĐ
        </td>
        @php
            $paymentMethods = [
                'tien_mat' => 'Tiền mặt',
                'the' => 'Thẻ',
                'tai_khoan' => 'Tài khoản',
            ];
        @endphp
        <td>{{ $paymentMethods[$hoa_dons->phuong_thuc_thanh_toan] ?? 'Không xác định' }}
        </td>
        <td>{{ $hoa_dons->ngay_tao ? \Carbon\Carbon::parse($hoa_dons->ngay_tao)->format('d/m/Y H:i') : 'Chưa có' }}
        </td>

        <td>
            <a href="{{ route('hoa-don.show', $hoa_dons->id) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-eye"></i>
            </a>
            <button class="btn btn-success btn-sm" onclick="printInvoice({{ $hoa_dons->id }})">
                <i class="fas fa-print"></i>
            </button>
        </td>
    </tr>
@endforeach
