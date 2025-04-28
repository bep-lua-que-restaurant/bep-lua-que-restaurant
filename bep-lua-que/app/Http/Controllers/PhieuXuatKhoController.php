<?php

namespace App\Http\Controllers;

use App\Exports\PhieuXuatKhoExport;
use App\Http\Requests\UpdatePhieuXuatKhoRequest;
use App\Models\PhieuXuatKho;
use App\Models\ChiTietPhieuXuatKho;
use App\Models\NhanVien;
use App\Models\NhaCungCap;
use App\Models\NguyenLieu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StorePhieuXuatKhoRequest;
use App\Models\LoaiNguyenLieu;
use Maatwebsite\Excel\Facades\Excel;

class PhieuXuatKhoController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = PhieuXuatKho::query()
                ->with(['nhaCungCap', 'nhanVien'])
                ->withTrashed();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('trang_thai', function ($row) {
                    return $row->deleted_at
                        ? '<div class="d-flex align-items-center"><i class="fa fa-circle text-danger mr-1"></i> Đã xóa</div>'
                        : ($row->trang_thai == 'cho_duyet'
                            ? '<div class="d-flex align-items-center"><i class="fa fa-circle text-warning mr-1"></i> Chờ duyệt</div>'
                            : ($row->trang_thai == 'da_duyet'
                                ? '<div class="d-flex align-items-center"><i class="fa fa-circle text-success mr-1"></i> Đã duyệt</div>'
                                : '<div class="d-flex align-items-center"><i class="fa fa-circle text-secondary mr-1"></i> Đã hủy</div>'));
                })
                ->addColumn('tong_gia_tri', function ($row) {
                    return number_format($row->tong_tien, 0, ',', '.');
                })
                ->addColumn('nha_cung_cap', function ($row) {
                    return $row->nhaCungCap ? $row->nhaCungCap->ten_nha_cung_cap : 'Không có';
                })
                ->addColumn('nhanvien', function ($row) {
                    return $row->nhanvien ? $row->nhanVien->ho_ten : 'Chưa có';
                })
                ->addColumn('action', function ($row) {
                    $html = '<div class="d-flex align-items-center">';

                    // Nút xem chi tiết
                    $html .= '<a href="' . route('phieu-xuat-kho.show', $row->id) . '" class="btn btn-info btn-sm p-2 m-1" title="Xem chi tiết"><i class="fa fa-eye"></i></a>';

                    // Chỉ hiện nút chỉnh sửa nếu chưa bị xoá
                    if (!$row->deleted_at) {
                        $html .= '<a href="' . route('phieu-xuat-kho.edit', $row->id) . '" class="btn btn-warning btn-sm p-2 m-1" title="Chỉnh sửa"><i class="fa fa-edit"></i></a>';
                    }

                    // Nút xóa hoặc khôi phục
                    if ($row->deleted_at) {
                        $html .= '<form action="' . route('phieu-xuat-kho.restore', $row->id) . '" method="POST" style="display:inline;">'
                            . csrf_field()
                            . '<button type="submit" class="btn btn-success btn-sm p-2 m-1" title="Khôi phục"><i class="fa fa-recycle"></i></button>'
                            . '</form>';
                    } else {
                        $html .= '<form action="' . route('phieu-xuat-kho.destroy', $row->id) . '" method="POST" style="display:inline;">'
                            . csrf_field()
                            . method_field('DELETE')
                            . '<button type="submit" class="btn btn-danger btn-sm p-2 m-1" title="Xóa"><i class="fa fa-trash"></i></button>'
                            . '</form>';
                    }

                    $html .= '</div>';
                    return $html;
                })

                ->rawColumns(['trang_thai', 'action'])
                ->make(true);
        }

        return view('admin.phieuxuatkho.list', [
            'route' => route('phieu-xuat-kho.index'),
            'tableId' => 'list-container',
            'searchInputId' => 'searchInput',
        ]);
    }


    public function create()
    {
        // Lấy toàn bộ nguyên liệu (cả đã bị xóa mềm), load thêm loại và chi tiết nhập kho mới nhất
        $nguyenLieus = NguyenLieu::withTrashed()
            ->with(['loaiNguyenLieu', 'chiTietNhapKhoMoiNhat'])
            ->get(); // Bỏ điều kiện so_luong_ton nếu muốn lấy tất cả
        $tonKhos = $nguyenLieus->pluck('so_luong_ton', 'id');
        // Lấy danh sách loại nguyên liệu, nhà cung cấp và nhân viên
        $loaiNguyenLieus = LoaiNguyenLieu::all();
        $nhaCungCaps = NhaCungCap::all(); // Chỉ dùng nếu loại phiếu là trả hàng
        $nhanViens = NhanVien::all();

        // Tạo mã phiếu tự động
        $nextCode = 'PXK' . now()->format('YmdHis');

        return view('admin.phieuxuatkho.create', compact(
            'nguyenLieus',
            'loaiNguyenLieus',
            'nhaCungCaps',
            'nhanViens',
            'nextCode',
            'tonKhos'
        ));
    }






    public function store(StorePhieuXuatKhoRequest $request)
    {
        DB::beginTransaction();
        try {
            // Tạo phiếu xuất kho
            $phieu = PhieuXuatKho::create([
                'ma_phieu' => 'PXK' . now()->format('YmdHis'),
                'ngay_xuat' => $request->ngay_xuat,
                'loai_phieu' => $request->loai_phieu,
                'nhan_vien_id' => $request->nhan_vien_id,
                'ghi_chu' => $request->ghi_chu,
                'tong_tien' => 0,

                // người nhận luôn có nếu là xuat_bep, xuat_huy, hoặc xuat_tra_hang
                'nguoi_nhan' => in_array($request->loai_phieu, ['xuat_bep', 'xuat_huy', 'xuat_tra_hang']) ? $request->nguoi_nhan : null,

                // nhà cung cấp chỉ khi là xuat_tra_hang
                'nha_cung_cap_id' => $request->loai_phieu === 'xuat_tra_hang' ? $request->nha_cung_cap_id : null,
            ]);


            // Kiểm tra danh sách nguyên liệu
            if (!is_array($request->nguyen_lieu_ids) || empty($request->nguyen_lieu_ids)) {
                return back()->withInput()->with('error', 'Vui lòng thêm ít nhất một nguyên liệu.');
            }

            $tongTien = 0;

            foreach ($request->nguyen_lieu_ids as $index => $nguyenLieuId) {
                $soLuong = floatval($request->so_luong_xuats[$index] ?? 0);
                $donGia = floatval($request->don_gias[$index] ?? 0);

                // Nếu là phiếu trả hàng, phải có đơn giá
                if ($request->loai_phieu === 'xuat_tra_hang' && $donGia <= 0) {
                    DB::rollBack();
                    return back()->withInput()->with('error', 'Vui lòng nhập đơn giá cho nguyên liệu trả hàng.');
                }

                $phieu->chiTietPhieuXuatKhos()->create([
                    'nguyen_lieu_id' => $nguyenLieuId,
                    'don_vi_xuat' => $request->don_vi_xuats[$index] ?? '',
                    'so_luong' => $soLuong,
                    'don_gia' => $donGia,
                    'ghi_chu' => $request->ghi_chus[$index] ?? null,
                ]);

                // Nếu có đơn giá thì cộng tiền (dùng cho tất cả các loại nếu bạn muốn theo dõi tổn thất)
                $tongTien += $soLuong * $donGia;
            }

            // Cập nhật tổng tiền nếu có
            $phieu->update(['tong_tien' => $tongTien]);

            DB::commit();
            return redirect()->route('phieu-xuat-kho.index')->with('success', 'Tạo phiếu xuất kho thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }



    public function show(PhieuXuatKho $phieuXuatKho)
    {
        if ($phieuXuatKho->deleted_at !== null) {
            return redirect()->route('phieu-xuat-kho.index')->with('error', 'Không thể xem phiếu xuất kho đã bị xoá.');
        }

        $phieuXuatKho->load([
            'nhaCungCap',
            'nhanVien',
            'chiTietPhieuXuatKhos.nguyenLieu' => function ($query) {
                $query->withTrashed(); // Hiện nguyên liệu đã bị xoá
            },
            'chiTietPhieuXuatKhos.loaiNguyenLieu',
        ]);

        $chiTietPhieuXuatKhos = $phieuXuatKho->chiTietPhieuXuatKhos;

        return view('admin.phieuxuatkho.detail', compact('phieuXuatKho', 'chiTietPhieuXuatKhos'));
    }



    public function edit(PhieuXuatKho $phieuXuatKho)
    {
        if ($phieuXuatKho->deleted_at !== null) {
            return redirect()->route('phieu-xuat-kho.index')->with('error', 'Không thể sửa phiếu đã bị xoá.');
        }

        if ($phieuXuatKho->trang_thai !== 'cho_duyet') {
            return redirect()->route('phieu-xuat-kho.index')->with('error', 'Chỉ được sửa phiếu ở trạng thái "Chờ duyệt".');
        }

        // Load nguyên liệu kể cả bị xoá
        $phieuXuatKho->load([
            'chiTietPhieuXuatKhos.nguyenLieu' => function ($query) {
                $query->withTrashed();
            },
            'chiTietPhieuXuatKhos.loaiNguyenLieu',
            'nhaCungCap'
        ]);

        $loaiPhieu = $phieuXuatKho->loai_phieu;
        $loaiNguyenLieus = LoaiNguyenLieu::with(['nguyenLieus' => function ($query) {
            $query->withTrashed();
        }])->get();

        $nguyenLieuOptions = collect($loaiNguyenLieus)->flatMap(function ($loai) {
            return $loai->nguyenLieus
                ->filter(fn($nl) => $nl->so_luong_ton > 5)
                ->map(fn($nl) => [
                    'id' => $nl->id,
                    'text' => $loai->ten_loai . ' - ' . $nl->ten_nguyen_lieu,
                    'don_gia' => $nl->don_gia,
                    'don_vi' => $nl->don_vi_ton,
                    'loai_nguyen_lieu_id' => $nl->loai_nguyen_lieu_id,
                    'deleted_at' => $nl->deleted_at,
                ]);
        })->values();

        $nhanViens = NhanVien::all();

        return view('admin.phieuxuatkho.edit', compact(
            'phieuXuatKho',
            'nhanViens',
            'loaiNguyenLieus',
            'nguyenLieuOptions',
            'loaiPhieu',
        ));
    }




    public function update(UpdatePhieuXuatKhoRequest $request, PhieuXuatKho $phieuXuatKho)
    {
        if ($phieuXuatKho->trang_thai !== 'cho_duyet') {
            return redirect()->back()->with('error', 'Không thể cập nhật phiếu đã duyệt hoặc đã hủy.');
        }

        DB::beginTransaction();

        try {
            // Cập nhật phiếu xuất
            $phieuXuatKho->update([
                'ma_phieu'        => $request->ma_phieu,
                'nhan_vien_id'    => $request->nhan_vien_id,
                'loai_phieu'      => $request->loai_phieu,
                'ghi_chu'         => $request->ghi_chu,
                'ngay_xuat'       => $request->filled('ngay_xuat') ? $request->ngay_xuat : now(),

                'nguoi_nhan'      => in_array($request->loai_phieu, ['xuat_bep', 'xuat_huy', 'xuat_tra_hang']) ? $request->nguoi_nhan : null,
                'nha_cung_cap_id' => $request->loai_phieu === 'xuat_tra_hang' ? $request->nha_cung_cap_id : null,
            ]);

            $updatedIds = [];

            // Duyệt từng dòng chi tiết
            foreach ($request->nguyen_lieu_ids as $index => $nguyenLieuId) {
                $chiTietId = $request->chi_tiet_ids[$index] ?? null;

                $data = [
                    'phieu_xuat_kho_id' => $phieuXuatKho->id,
                    'nguyen_lieu_id'    => $nguyenLieuId,
                    'don_vi_xuat'       => $request->don_vi_xuats[$index],
                    'so_luong'          => $request->so_luongs[$index],
                    'don_gia'           => $request->don_gias[$index],
                    'ghi_chu'           => $request->ghi_chus[$index],
                ];

                if ($chiTietId) {
                    // Cập nhật chi tiết cũ
                    $chiTiet = ChiTietPhieuXuatKho::find($chiTietId);
                    if ($chiTiet) {
                        $chiTiet->update($data);
                        $updatedIds[] = $chiTiet->id;
                    }
                } else {
                    // Thêm chi tiết mới
                    $newChiTiet = ChiTietPhieuXuatKho::create($data);
                    $updatedIds[] = $newChiTiet->id;
                }
            }

            // Xoá chi tiết đã bị xoá khỏi form
            $phieuXuatKho->load('chiTietPhieuXuatKhos');
            $existingIds = $phieuXuatKho->chiTietPhieuXuatKhos->pluck('id')->toArray();
            $toDelete = array_diff($existingIds, $updatedIds);
            ChiTietPhieuXuatKho::destroy($toDelete);

            // Cập nhật tổng tiền
            $tongTien = $phieuXuatKho->chiTietPhieuXuatKhos()->sum(DB::raw('so_luong * don_gia'));
            $phieuXuatKho->update(['tong_tien' => $tongTien]);

            DB::commit();

            return redirect()->route('phieu-xuat-kho.edit', $phieuXuatKho->id)
                ->with('success', 'Cập nhật phiếu xuất kho thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }



    public function destroy(PhieuXuatKho $phieuXuatKho)
    {
        if (in_array($phieuXuatKho->trang_thai, ['da_duyet', 'da_huy'])) {
            $message = 'Không thể xoá phiếu đã duyệt hoặc đã huỷ.';

            if (request()->ajax()) {
                return response()->json(['message' => $message], 422);
            }

            return redirect()->route('phieu-xuat-kho.index')->with('error', $message);
        }

        $phieuXuatKho->delete();

        if (request()->ajax()) {
            return response()->json(['message' => 'Đã xoá phiếu xuất kho thành công!']);
        }

        return redirect()->route('phieu-xuat-kho.index')->with('success', 'Đã xoá phiếu xuất kho thành công!');
    }
    public function restore($id)
    {
        $phieuXuatKho = PhieuXuatKho::withTrashed()->findOrFail($id);
        $phieuXuatKho->restore();

        return redirect()->route('phieu-xuat-kho.index')->with('success', 'Đã khôi phục phiếu xuất kho!');
    }

    public function duyet($id)
    {
        $phieu = PhieuXuatKho::with('chiTietPhieuXuatKhos')->findOrFail($id);

        if ($phieu->trang_thai === 'da_huy') {
            return redirect()->back()->with('error', 'Phiếu đã bị hủy. Không thể duyệt.');
        }

        if ($phieu->trang_thai !== 'cho_duyet') {
            return redirect()->back()->with('error', 'Phiếu đã được xử lý.');
        }

        DB::beginTransaction();
        try {
            foreach ($phieu->chiTietPhieuXuatKhos as $chiTiet) {
                $nguyenLieu = NguyenLieu::find($chiTiet->nguyen_lieu_id);

                // Chỉ kiểm tra nguyên liệu bị xoá nếu là phiếu xuất bếp
                if ($phieu->loai_phieu === 'xuat_bep' && !$nguyenLieu) {
                    throw new \Exception("Nguyên liệu ID {$chiTiet->nguyen_lieu_id} đã bị xóa hoặc ngừng sử dụng. Không thể duyệt phiếu xuất bếp.");
                }

                // Nếu nguyên liệu không tồn tại (nhưng phiếu không phải 'xuat_bep') thì bỏ qua xử lý trừ kho
                if (!$nguyenLieu) {
                    continue;
                }

                $soLuongTru = $chiTiet->so_luong;

                if ($nguyenLieu->so_luong_ton < $soLuongTru) {
                    throw new \Exception("Không đủ tồn kho cho nguyên liệu: {$nguyenLieu->ten_nguyen_lieu}");
                }

                $nguyenLieu->so_luong_ton -= $soLuongTru;
                $nguyenLieu->save();
            }

            $phieu->trang_thai = 'da_duyet';
            $phieu->save();

            DB::commit();
            return redirect()->back()->with('success', 'Phiếu xuất đã được duyệt thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }



    public function huy($id)
    {
        $phieu = PhieuXuatKho::with('chiTietPhieuXuatKhos')->findOrFail($id);

        if ($phieu->trang_thai === 'da_huy') {
            return redirect()->back()->with('error', 'Phiếu đã bị hủy.');
        }

        DB::beginTransaction();
        try {
            // Nếu phiếu đã được duyệt, cộng lại tồn kho
            if ($phieu->trang_thai === 'da_duyet') {
                foreach ($phieu->chiTietPhieuXuatKhos as $chiTiet) {
                    $nguyenLieu = NguyenLieu::find($chiTiet->nguyen_lieu_id);

                    if ($nguyenLieu) {
                        $nguyenLieu->so_luong_ton += $chiTiet->so_luong;
                        $nguyenLieu->save();
                    }
                }
            }

            $phieu->trang_thai = 'da_huy';
            $phieu->save();

            DB::commit();
            return redirect()->back()->with('success', 'Phiếu xuất đã được hủy thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Lỗi khi huỷ phiếu: ' . $e->getMessage());
        }
    }
    public function export()
    {
        return Excel::download(new PhieuXuatKhoExport, 'phieu_xuat_kho.xlsx');
    }
}
