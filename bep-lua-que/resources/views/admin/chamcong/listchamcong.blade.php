    {{-- PHẦN CHẤM CÔNG NHANH --}}


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
                        @endphp
                        <td class="relative px-2 py-2 border">
                            <div class="flex justify-between gap-4">
                                @foreach ($caLams as $caLam)
                                    @php
                                        $caKey = Str::slug($caLam->ten_ca, '_'); // ví dụ: "Ca Sáng" => "ca_sang"
                                    @endphp
                                    <label class="flex items-center gap-2 text-sm font-medium inline-flex">
                                        <input type="hidden"
                                            name="ca_lam[{{ $nhanVien->id }}][{{ $date->format('Y-m-d') }}][{{ $caKey }}]"
                                            value="0">
                                        <input type="checkbox"
                                            name="ca_lam[{{ $nhanVien->id }}][{{ $date->format('Y-m-d') }}][{{ $caKey }}]"
                                            value="1"
                                            {{ $chamCongForDay->contains('ca_lam_id', $caLam->id) ? 'checked' : '' }}
                                            {{ $date->isToday() ? '' : 'disabled' }}>
                                        <span>{{ $caLam->ten_ca }}</span>
                                    </label>
                                @endforeach


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
