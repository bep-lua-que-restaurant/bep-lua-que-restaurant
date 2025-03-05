<table class="table table-bordered text-center table-hover">
    <thead class="table-dark">
        <tr>
            <th class="text-center">Ca làm việc</th>
            @foreach ($dates as $date)
                <th>
                    {{ ucfirst($date->translatedFormat('l')) }} <br>
                    <span class="badge bg-secondary rounded-pill">{{ $date->format('d') }}</span>
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($caLams as $caLam)
            <tr>
                <td class="align-middle fw-bold">
                    {{ $caLam->ten_ca }} <br>
                    <span class="small text-muted">({{ $caLam->gio_bat_dau }} -
                        {{ $caLam->gio_ket_thuc }})</span>
                </td>
                @foreach ($dates as $date)
                    @php
                        $nhanViens = $chamCongs->filter(
                            fn($chamCong) => $chamCong->ca_lam_id == $caLam->id &&
                                $chamCong->ngay_lam == $date->format('Y-m-d'),
                        );
                    @endphp
                    <td class="align-middle">
                        @foreach ($nhanViens as $nhanVien)
                            @php
                                $daChamCong = collect($chamCongs)->contains(
                                    fn($chamCong) => $chamCong->nhan_vien_id == $nhanVien->nhan_vien_id &&
                                        $chamCong->ca_lam_id == $caLam->id &&
                                        $chamCong->ngay_cham_cong == $date->format('Y-m-d'),
                                );

                                $badgeClass = $nhanVien->deleted_at
                                    ? 'bg-danger'
                                    : ($daChamCong
                                        ? 'bg-success'
                                        : 'bg-warning');
                            @endphp

                            <div class="d-block mb-2">
                                <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2 cham-cong"
                                    data-id="{{ $nhanVien->ca_lam_nhan_vien_id }}"
                                    data-nhanvien-id="{{ $nhanVien->nhan_vien_id }}"
                                    data-ngay="{{ $date->format('Y-m-d') }}" data-ca="{{ $caLam->id }}">
                                    {{ $nhanVien->ten_nhan_vien }}
                                </span>

                                @if ($nhanVien->deleted_at)
                                    <form action="{{ route('cham-cong.restore') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="nhan_vien_id"
                                            value="{{ $nhanVien->nhan_vien_id }}">
                                        <input type="hidden" name="ca_lam_id" value="{{ $nhanVien->ca_lam_id }}">
                                        <input type="hidden" name="ngay_cham_cong"
                                            value="{{ $nhanVien->ngay_cham_cong }}">
                                        <button type="submit"
                                            onclick="return confirm('Bạn có chắc muốn khôi phục chấm công này không?')"
                                            class="btn btn-success btn-md rounded-pill shadow-sm d-flex align-items-center gap-1 px-3"
                                            title="Khôi phục">
                                            <i class="fas fa-undo-alt"></i>
                                        </button>
                                    </form>
                                @else
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
                                            class="btn btn-danger btn-sm" title="Hủy">
                                            ✖
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
