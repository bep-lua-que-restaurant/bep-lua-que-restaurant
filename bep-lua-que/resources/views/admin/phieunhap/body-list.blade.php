@foreach ($data as $item)
    <tr>
        <td><strong>{{ $item->id }}</strong></td>
        <td><strong>{{ $item->ma_phieu_nhap }}</strong></td>
        <td>{{ $item->nhanVien->ho_ten ?? 'N/A' }}</td>
        <td>{{ $item->nhaCungCap->ten_nha_cung_cap ?? 'N/A' }}</td>
        <td>{{ \Carbon\Carbon::parse($item->ngay_nhap)->format('d/m/Y') }}</td>
        <td>
            <div class="d-flex">
                <a href="{{ route('phieu-nhap-kho.show', $item->id) }}" class="btn btn-info btn-sm mx-1">
                    <i class="fa fa-eye"></i>
                </a>
                {{-- <a href="{{ route('phieu-nhap-kho.edit', $item->id) }}" class="btn btn-warning btn-sm mx-1">
                    <i class="fa fa-edit"></i>
                </a> --}}
                <form action="{{ route('phieu-nhap-kho.destroy', $item->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm mx-1" onclick="return confirm('Xóa phiếu nhập này?')">
                        <i class="fa fa-trash"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@endforeach
