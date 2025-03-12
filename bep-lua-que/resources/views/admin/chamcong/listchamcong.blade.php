<table class="table table-bordered text-center table-hover">
    <thead class="table-dark">
        <tr>
            <th style="background-color: #198754;">Ca làm việc</th>
            {{-- <th style="background-color: #198754;">Ngày làm</th> --}}
            <th style="background-color: #198754;">Nhân viên</th>
            <th style="background-color: #198754;">Trạng thái</th>
            <th style="background-color: #198754;">Hành động</th>
        </tr>
    <tbody>
        @foreach ($caLams as $caLam)
            @foreach ($dates as $date)
                @php
                    $nhanViens = $chamCongs->filter(
                        fn($chamCong) => $chamCong->ca_lam_id == $caLam->id &&
                            $chamCong->ngay_lam == $date->format('Y-m-d'),
                    );
                @endphp
                @foreach ($nhanViens as $nhanVien)
                    @php
                        $daChamCong = collect($chamCongs)->contains(
                            fn($chamCong) => $chamCong->nhan_vien_id == $nhanVien->nhan_vien_id &&
                                $chamCong->ca_lam_id == $caLam->id &&
                                $chamCong->ngay_cham_cong == $date->format('Y-m-d'),
                        );

                        $badgeClass = $nhanVien->deleted_at ? 'bg-danger' : ($daChamCong ? 'bg-success' : 'bg-warning');

                        $statusText = $nhanVien->deleted_at
                            ? 'Đã hủy'
                            : ($daChamCong
                                ? 'Đã chấm công'
                                : 'Chưa chấm công');
                    @endphp
                    <tr>
                        {{-- <td>{{ $nhanVien->ca_lam_nhan_vien_id }}</td> --}}
                        <td class="align-middle fw-bold">
                            {{ $caLam->ten_ca }} <br>
                            <span class="small text-muted">({{ $caLam->gio_bat_dau }} -
                                {{ $caLam->gio_ket_thuc }})</span>
                        </td>
                        {{-- <td class="align-middle">{{ $date->format('d/m/Y') }}</td> --}}
                        <td class="align-middle">{{ $nhanVien->ten_nhan_vien }}</td>
                        <td class="align-middle">
                            <span class="badge {{ $badgeClass }} px-3 py-2">{{ $statusText }}</span>
                        </td>
                        <td class="align-middle">
                            @if (!$nhanVien->deleted_at)
                                @if (!$nhanVien->da_cham_cong)
                                    {{-- Nếu chưa chấm công, hiển thị nút "Xác nhận" --}}
                                    <form action="{{ route('chamcong.store') }}" method="POST" class="d-inline"
                                        onsubmit="return confirmChamCong(event, '{{ $nhanVien->ca_lam_nhan_vien_id }}', '{{ $nhanVien->ten_nhan_vien }}')">
                                        @csrf
                                        <input type="hidden" name="ca_lam_nhan_vien_id"
                                            value="{{ $nhanVien->ca_lam_nhan_vien_id }}">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            ✔ Xác nhận
                                        </button>
                                    </form>
                                @else
                                    {{-- Nếu đã chấm công, hiển thị nút "Hủy" --}}
                                    <form action="{{ route('cham-cong.softDelete') }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="nhan_vien_id"
                                            value="{{ $nhanVien->nhan_vien_id }}">
                                        <input type="hidden" name="ca_lam_id" value="{{ $nhanVien->ca_lam_id }}">
                                        <input type="hidden" name="ngay_cham_cong"
                                            value="{{ $nhanVien->ngay_cham_cong }}">
                                        <button type="submit"
                                            onclick="return confirm('Bạn muốn hủy chấm công nhân viên này chứ?')"
                                            class="btn btn-danger btn-sm">
                                            ✖ Hủy
                                        </button>
                                    </form>
                                @endif
                            @endif

                            @if ($nhanVien->deleted_at)
                                {{-- Nếu đã bị xóa mềm, hiển thị nút "Khôi phục" --}}
                                <form action="{{ route('cham-cong.restore') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="nhan_vien_id" value="{{ $nhanVien->nhan_vien_id }}">
                                    <input type="hidden" name="ca_lam_id" value="{{ $nhanVien->ca_lam_id }}">
                                    <input type="hidden" name="ngay_cham_cong"
                                        value="{{ $nhanVien->ngay_cham_cong }}">
                                    <button type="submit"
                                        onclick="return confirm('Bạn có chắc muốn khôi phục chấm công này không?')"
                                        class="btn btn-success btn-sm">
                                        <i class="fas fa-undo-alt"></i> Khôi phục
                                    </button>
                                </form>
                            @endif


                        </td>

                    </tr>
                @endforeach
            @endforeach
        @endforeach
    </tbody>
</table>
</table>

<!-- Thêm phân trang -->
{{-- <div class="d-flex justify-content-center mt-3">
    {{ $chamCongs->links() }}
</div> --}}

<script>
    function confirmChamCong(event, caLamNhanVienId, nhanVien) {
        event.preventDefault();
        let message =
            `Xác nhận chấm công cho:\n\n👤 Nhân viên: ${nhanVien}\n🆔 ID: ${caLamNhanVienId}\n\nBạn có chắc không?`;

        if (confirm(message)) {
            event.target.submit();
        }
    }
</script>
