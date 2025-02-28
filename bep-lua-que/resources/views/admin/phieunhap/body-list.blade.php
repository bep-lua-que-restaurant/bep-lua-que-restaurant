@foreach ($data as $index => $item)
    <tr data-toggle="collapse" data-target="#detail{{ $index }}" class="clickable-row">

        <td><strong>{{ $item->id }}</strong></td>
        <td><strong>{{ $item->ma_phieu_nhap }}</strong></td>
        <td>{{ $item->nhanVien->ho_ten ?? 'N/A' }}</td>
        <td>{{ $item->nhaCungCap->ten_nha_cung_cap ?? 'N/A' }}</td>
        <td>{{ \Carbon\Carbon::parse($item->ngay_nhap)->format('d/m/Y') }}</td>

        <!-- Trạng thái phiếu nhập -->
        <td>
            @if ($item->trang_thai == 'cho_duyet')
                <span class="badge bg-warning">Chờ duyệt</span>
            @elseif ($item->trang_thai == 'da_duyet')
                <span class="badge bg-success">Đã duyệt</span>
            @else
                <span class="badge bg-danger">Hủy</span>
            @endif
        </td>

        <td>
            <div class="d-flex align-items-center">
                <a href="{{ route('phieu-nhap-kho.show', $item->id) }}" class="btn btn-info btn-sm p-2 m-2">
                    <i class="fa fa-eye"></i>
                </a>
               
                {{-- Nếu cần chức năng sửa, bỏ comment phần này --}}
                {{-- <a href="{{ route('phieu-nhap-kho.edit', $item->id) }}" class="btn btn-warning btn-sm p-2 m-2">
                    <i class="fa fa-edit"></i>
                </a> --}}
                {{-- @if ($item->deleted_at)
                    <form action="{{ route('phieu-nhap-kho.restore', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" onclick="return confirm('Bạn có chắc muốn khôi phục phiếu nhập này không?')"
                            class="btn btn-success btn-sm p-2 m-2" title="Khôi phục">
                            <i class="fa fa-recycle"></i>
                        </button>
                    </form>
                @else
                    <form action="{{ route('phieu-nhap-kho.destroy', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Bạn muốn xóa phiếu nhập này chứ?')"
                            class="btn btn-danger btn-sm p-2 m-2" title="Xóa">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>

                @endif --}}

            </div>
        </td>
    </tr>
@endforeach
