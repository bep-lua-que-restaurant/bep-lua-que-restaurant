    {{-- PHẦN CHẤM CÔNG NHANH --}}
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="dateSelect" class="form-label">📅 Ngày chấm công</label>
            <input type="date" id="dateSelect" name="ngay_cham_cong_nhanh" class="form-control">
        </div>

        <div class="col-md-6">
            <label for="shiftSelect" class="form-label">🕐 Ca làm</label>
            <select id="shiftSelect" name="ca_lam_id_nhanh" class="form-control">
                <option value="">-- Chọn ca làm --</option>
                @foreach ($caLams as $caLam)
                    <option value="{{ $caLam->id }}">{{ $caLam->ten_ca }}</option>
                @endforeach
            </select>
        </div>
    </div>


    <div class="mb-3">
        <label class="form-label">👥 Chọn nhân viên</label>
        <input type="text" id="searchEmployees" class="form-control mb-3" placeholder="Tìm nhân viên...">

        <div id="employeeList" class="border rounded p-3" style="max-height: 100px; overflow-y: auto;">
            <!-- Giảm max-height xuống -->
            <div class="row g-3">
                @foreach ($nhanViens as $nhanVien)
                    <div class="col-4 d-flex align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="nhan_vien_ids_nhanh[]"
                                value="{{ $nhanVien->id }}" id="nhanVien{{ $nhanVien->id }}">
                            <label class="form-check-label" for="nhanVien{{ $nhanVien->id }}">
                                {{ $nhanVien->ho_ten }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- PHẦN CHẤM CÔNG LẺ --}}
    <table class="min-w-full text-center">
        {{-- <thead>

        </thead> --}}
        <tbody id="chamcongTable">
            <tr>
                <th class="px-4 py-3 border-end text-left">Nhân Viên</th>
                @foreach ($dates as $date)
                    <th class="px-3 py-3 border-end text-center">
                        <span class="d-block fs-5">{{ $date->day }}</span>
                        <span class="text-muted small">{{ $date->locale('vi')->isoFormat('dd') }}</span>
                    </th>
                @endforeach
            </tr>
            @foreach ($nhanViens as $nhanVien)
                <tr>
                    <td class="px-4 py-2 border text-left">{{ $nhanVien->ho_ten }}</td>
                    @foreach ($dates as $date)
                        @php
                            $chamCongForDay = $chamCongs->filter(function ($chamCong) use ($nhanVien, $date) {
                                return $chamCong->nhan_vien_id == $nhanVien->id &&
                                    $chamCong->ngay_cham_cong == $date->format('Y-m-d');
                            });

                            $isCaSangChecked = $chamCongForDay->contains('ca_lam_id', 1);
                            $isCaChieuChecked = $chamCongForDay->contains('ca_lam_id', 2);
                            $isCaToiChecked = $chamCongForDay->contains('ca_lam_id', 3);
                            $isDisabled = !$date->equalTo(\Carbon\Carbon::today());

                        @endphp
                        <td class="relative px-2 py-2 border">
                            <div class="flex justify-between gap-4">
                                <label class="flex items-center gap-1 text-sm font-medium">
                                    <input type="hidden"
                                        name="ca_lam[{{ $nhanVien->id }}][{{ $date->format('Y-m-d') }}][ca_sang]"
                                        value="0">
                                    <input type="checkbox"
                                        name="ca_lam[{{ $nhanVien->id }}][{{ $date->format('Y-m-d') }}][ca_sang]"
                                        value="1" {{ $isCaSangChecked ? 'checked' : '' }}
                                        {{ $isDisabled ? 'disabled' : '' }}>
                                    <span>S</span>
                                </label>
                                <label class="flex items-center gap-1 text-sm font-medium">
                                    <input type="hidden"
                                        name="ca_lam[{{ $nhanVien->id }}][{{ $date->format('Y-m-d') }}][ca_chieu]"
                                        value="0">
                                    <input type="checkbox"
                                        name="ca_lam[{{ $nhanVien->id }}][{{ $date->format('Y-m-d') }}][ca_chieu]"
                                        value="1" {{ $isCaChieuChecked ? 'checked' : '' }}
                                        {{ $isDisabled ? 'disabled' : '' }}>
                                    <span>C</span>
                                </label>
                                <label class="flex items-center gap-1 text-sm font-medium">
                                    <input type="hidden"
                                        name="ca_lam[{{ $nhanVien->id }}][{{ $date->format('Y-m-d') }}][ca_toi]"
                                        value="0">
                                    <input type="checkbox"
                                        name="ca_lam[{{ $nhanVien->id }}][{{ $date->format('Y-m-d') }}][ca_toi]"
                                        value="1" {{ $isCaToiChecked ? 'checked' : '' }}
                                        {{ $isDisabled ? 'disabled' : '' }}>
                                    <span>T</span>
                                </label>
                            </div>

                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>
        const searchInput = document.getElementById('searchEmployees');
        const employeeList = document.querySelector('#employeeList .row');
        const originalItems = Array.from(employeeList.querySelectorAll('.col-4')); // 👈 Lưu lại danh sách ban đầu

        searchInput.addEventListener('input', function() {
            let keyword = this.value.toLowerCase();
            let items = Array.from(originalItems); // lấy theo thứ tự ban đầu

            if (keyword === '') {
                // Nếu không nhập gì, reset về danh sách gốc
                employeeList.innerHTML = '';
                items.forEach(function(item) {
                    item.style.display = 'flex'; // hiện tất cả
                    employeeList.appendChild(item);
                });
            } else {
                let matchedItems = [];

                items.forEach(function(item) {
                    let text = item.innerText.toLowerCase();
                    if (text.includes(keyword)) {
                        item.style.display = 'flex';
                        matchedItems.push(item);
                    } else {
                        item.style.display = 'none';
                    }
                });

                employeeList.innerHTML = '';
                matchedItems.forEach(function(item) {
                    employeeList.appendChild(item);
                });
            }
        });
    </script>


    <style>
        #employeeList .employee-item {
            width: calc(33.33% - 10px);
            /* 3 người 1 dòng */
            min-width: 200px;
        }
    </style>
