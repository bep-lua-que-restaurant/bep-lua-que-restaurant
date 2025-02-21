
@foreach ($data as $index => $item)
    <tr data-toggle="collapse" data-target="#detail{{ $index }}" class="clickable-row">
        
        <td><strong>{{ $item->id }}</strong></td>
        <td><strong>{{ $item->ma_phieu_nhap }}</strong></td>
        <td>{{ $item->nhanVien->ho_ten ?? 'N/A' }}</td>
        <td>{{ $item->nhaCungCap->ten_nha_cung_cap ?? 'N/A' }}</td>
        <td>{{ \Carbon\Carbon::parse($item->ngay_nhap)->format('d/m/Y') }}</td>

        <!-- Trạng thái phiếu nhập -->
        {{-- <td>
            @if ($item->deleted_at)
                <div class="d-flex align-items-center"><i class="fa fa-circle text-danger mr-1"></i> Đã xóa</div>
            @else
                <div class="d-flex align-items-center"><i class="fa fa-circle text-success mr-1"></i> Hoạt động</div>
            @endif
        </td> --}}

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
