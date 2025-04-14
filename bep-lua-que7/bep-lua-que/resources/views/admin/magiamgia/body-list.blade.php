<<<<<<< HEAD
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Mã</th>
            <th>Loại</th>
            <th>Giá trị</th>
            <th>Hiệu lực</th>
            <th>Số lượt đã dùng</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $index => $item)
            <tr data-toggle="collapse" data-target="#detail{{ $index }}" class="clickable-row">
                <td>{{ $item->id }}</td>
                <td>{{ $item->code }}</td>
                <td>{{ $item->type }}</td>
                <td>{{ $item->value }}</td>
                <td>
                    @if($item->start_date && $item->end_date)
                        Từ: {{ \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') }}<br>
                        Đến: {{ \Carbon\Carbon::parse($item->end_date)->format('d/m/Y') }}
                    @else
                        Không xác định
                    @endif
                </td>
                <td>{{ $item->usage_count ?? 0 }}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <a href="{{ route('ma-giam-gia.show', $item->id) }}" class="btn btn-info btn-sm m-1">
                            <i class="fa fa-eye"></i>
                        </a>
                        <a href="{{ route('ma-giam-gia.edit', $item->id) }}" class="btn btn-warning btn-sm m-1">
                            <i class="fa fa-edit"></i>
                        </a>
                        @if ($item->deleted_at)
                            <form action="{{ route('ma-giam-gia.restore', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" onclick="return confirm('Bạn có chắc muốn khôi phục mục này không?')"
                                    class="btn btn-success btn-sm m-1" title="Khôi phục">
                                    <i class="fa fa-recycle"></i>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('ma-giam-gia.destroy', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Bạn muốn ngừng mã giảm này chứ?')"
                                    class="btn btn-danger btn-sm m-1" title="Xóa">
                                    <i class="fa fa-trash"></i>
=======
<div class="accordion" id="discountAccordion">
    @foreach ($data as $index => $item)
        <div class="accordion-item mb-3 shadow-sm border rounded">
            <h2 class="accordion-header" id="heading{{ $index }}">
                <button class="accordion-button collapsed d-flex justify-content-between" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="false" aria-controls="collapse{{ $index }}">
                    <div class="d-flex flex-column flex-md-row w-100 justify-content-between align-items-start align-items-md-center">
                        <span><strong>#{{ $item->id }}</strong> - {{ $item->code }}</span>
                        <span>
                            <span class="badge bg-{{ $item->type === 'percent' ? 'primary' : 'success' }}">
                                {{ $item->type === 'percent' ? 'Phần trăm' : 'Tiền mặt' }}
                            </span>
                            <span class="badge bg-warning text-dark ms-2">
                                {{ $item->value }}{{ $item->type === 'percent' ? '%' : '₫' }}
                            </span>
                        </span>
                    </div>
                </button>
            </h2>
            <div id="collapse{{ $index }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $index }}" data-bs-parent="#discountAccordion">
                <div class="accordion-body">
                    <p><strong>Hiệu lực:</strong>
                        @if($item->start_date && $item->end_date)
                            {{ \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($item->end_date)->format('d/m/Y') }}
                        @else
                            Không xác định
                        @endif
                    </p>
                    <p><strong>Số lượt đã dùng:</strong> {{ $item->usage_count ?? 0 }}</p>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('ma-giam-gia.show', $item->id) }}" class="btn btn-outline-info btn-sm">
                            <i class="fa fa-eye"></i> Xem
                        </a>
                        <a href="{{ route('ma-giam-gia.edit', $item->id) }}" class="btn btn-outline-warning btn-sm">
                            <i class="fa fa-edit"></i> Sửa
                        </a>
                        @if ($item->deleted_at)
                            <form action="{{ route('ma-giam-gia.restore', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" onclick="return confirm('Khôi phục mục này?')" class="btn btn-outline-success btn-sm">
                                    <i class="fa fa-recycle"></i> Khôi phục
                                </button>
                            </form>
                        @else
                            <form action="{{ route('ma-giam-gia.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Ngừng mã giảm giá này?')" class="btn btn-outline-danger btn-sm">
                                    <i class="fa fa-trash"></i> Xóa
>>>>>>> eb0fe4acf6f066edf0be422cb1177add1f22f2ba
                                </button>
                            </form>
                        @endif
                    </div>
<<<<<<< HEAD
                </td>
            </tr>
            <!-- Nếu muốn có hàng chi tiết ẩn hiện, bạn có thể thêm row dưới dạng collapse -->
            <tr id="detail{{ $index }}" class="collapse">
                <td colspan="7">
                    <!-- Nội dung chi tiết của row, có thể hiển thị thêm thông tin nếu cần -->
                    <strong>Mã giảm giá:</strong> {{ $item->code }} <br>
                    <strong>Loại:</strong> {{ $item->type }} <br>
                    <strong>Giá trị:</strong> {{ $item->value }} <br>
                    <strong>Hiệu lực:</strong> 
                        @if($item->start_date && $item->end_date)
                            Từ {{ \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') }} đến {{ \Carbon\Carbon::parse($item->end_date)->format('d/m/Y') }}
                        @else
                            Không xác định
                        @endif <br>
                    <strong>Số lượt đã dùng:</strong> {{ $item->usage_count ?? 0 }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
=======
                </div>
            </div>
        </div>
    @endforeach
</div>
>>>>>>> eb0fe4acf6f066edf0be422cb1177add1f22f2ba
