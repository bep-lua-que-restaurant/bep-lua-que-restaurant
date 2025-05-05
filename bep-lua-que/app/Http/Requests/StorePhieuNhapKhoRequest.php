<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Support\Carbon;

class StorePhieuNhapKhoRequest extends FormRequest
{
    /**
     * Xác định xem người dùng có quyền gửi yêu cầu này hay không.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Đảm bảo chỉ người dùng có quyền có thể tạo phiếu nhập kho
    }

    /**
     * Lấy các quy tắc xác thực cho yêu cầu.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ma_phieu' => 'required|string|max:255|unique:phieu_nhap_khos,ma_phieu',
            'loai_phieu' => 'required|in:nhap_tu_bep,nhap_tu_ncc',
            'nha_cung_cap_id' => 'nullable|required_if:loai_phieu,nhap_tu_ncc|exists:nha_cung_caps,id',
            'nhan_vien_id' => 'required|exists:nhan_viens,id',
            'ghi_chu' => 'nullable|string',

            // Danh sách nguyên liệu
            'ten_nguyen_lieus' => 'nullable|array',
            'ten_nguyen_lieus.*' => 'nullable|string|max:255',

            'nguyen_lieu_ids' => 'nullable|array',
            'nguyen_lieu_ids.*' => 'nullable|exists:nguyen_lieus,id',

            'loai_nguyen_lieu_ids' => 'required|array',
            'loai_nguyen_lieu_ids.*' => 'required|exists:loai_nguyen_lieus,id',

            'don_vi_nhaps' => 'required|array',
            'don_vi_nhaps.*' => ['required', 'string', 'regex:/^[^\d]*$/'],

            'so_luong_nhaps' => 'required|array',
            'so_luong_nhaps.*' => 'required|numeric|min:0.01|max:500',


            'don_gias' => 'nullable|array|required_if:loai_phieu,nhap_tu_ncc',
            'don_gias.*' => 'nullable|numeric|min:0.01',

            'ngay_san_xuats' => 'nullable|array',
            'ngay_san_xuats.*' => 'nullable|date',

            'ngay_het_hans' => 'nullable|array',
            'ngay_het_hans.*' => 'nullable|date',

            'ghi_chus' => 'nullable|array',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $tenNguyenLieus = $this->input('ten_nguyen_lieus', []);
            $nguyenLieuIds = $this->input('nguyen_lieu_ids', []);
            $ngaySXs = $this->input('ngay_san_xuats', []);
            $ngayHHs = $this->input('ngay_het_hans', []);
            $donVis = $this->input('don_vi_nhaps', []);
            $soLuongs = $this->input('so_luong_nhaps', []);
            $loaiPhieu = $this->input('loai_phieu');

            $max = max(
                count($tenNguyenLieus),
                count($nguyenLieuIds),
                count($donVis),
                count($soLuongs)
            );

            for ($i = 0; $i < $max; $i++) {
                $ten = $tenNguyenLieus[$i] ?? null;
                $id = $nguyenLieuIds[$i] ?? null;

                // Kiểm tra ít nhất có tên hoặc ID nguyên liệu
                if (empty($ten) && empty($id)) {
                    $validator->errors()->add("ten_nguyen_lieus.$i", 'Bạn phải nhập tên nguyên liệu hoặc chọn từ danh sách.');
                }

                // Nếu nhập tay, kiểm tra tên
                if (!empty($ten)) {
                    if (strlen(trim($ten)) < 2) {
                        $validator->errors()->add("ten_nguyen_lieus.$i", 'Tên nguyên liệu quá ngắn.');
                    }
                   
                }

                // Nếu chọn từ danh sách, kiểm tra ID
                if (!empty($id) && !\App\Models\NguyenLieu::find($id)) {
                    $validator->errors()->add("nguyen_lieu_ids.$i", 'Nguyên liệu không tồn tại.');
                }

                // Kiểm tra hạn sử dụng >= ngày sản xuất
                $sx = $ngaySXs[$i] ?? null;
                $hh = $ngayHHs[$i] ?? null;
                if ($sx && $hh) {
                    try {
                        if (Carbon::parse($hh)->lt(Carbon::parse($sx))) {
                            $validator->errors()->add("ngay_het_hans.$i", 'Hạn sử dụng phải sau ngày sản xuất.');
                        }
                    } catch (\Exception $e) {
                        $validator->errors()->add("ngay_het_hans.$i", 'Định dạng ngày không hợp lệ.');
                    }
                }

                // Kiểm tra nếu loại phiếu là "nhập từ NCC", đơn giá là bắt buộc
                if ($loaiPhieu == 'nhap_tu_ncc') {
                    $donGias = $this->input('don_gias', []);
                    if (!isset($donGias[$i]) || $donGias[$i] === null) {
                        $validator->errors()->add("don_gias.$i", 'Đơn giá là bắt buộc khi nhập từ nhà cung cấp.');
                    }
                }
            }

            // Kiểm tra số lượng dòng khớp nhau
            $fieldsToCheck = ['loai_nguyen_lieu_ids', 'don_vi_nhaps', 'so_luong_nhaps'];
            foreach ($fieldsToCheck as $field) {
                if (count($this->input($field, [])) !== $max) {
                    $validator->errors()->add($field, 'Số dòng dữ liệu không khớp nhau.');
                }
            }
        });
    }







    /**
     * Các thông báo xác thực tùy chỉnh.
     *
     * @return array
     */
    public function messages()
    {
        return [
            // Thông báo cho bảng phiếu nhập kho
            'ma_phieu.required' => 'Mã phiếu nhập kho là bắt buộc.',
            'ma_phieu.unique' => 'Mã phiếu nhập kho đã tồn tại.',

            'nha_cung_cap_id.required_if' => 'Nhà cung cấp là bắt buộc khi loại phiếu là nhập từ nhà cung cấp.',
            'nhan_vien_id.required' => 'Nhân viên là bắt buộc.',
            'loai_phieu.required' => 'Loại phiếu là bắt buộc.',

            // Thông báo cho bảng chi tiết phiếu nhập kho
            'ten_nguyen_lieus.required' => 'Tên nguyên liệu là bắt buộc.',
            'ten_nguyen_lieus.*.required' => 'Tên nguyên liệu không được để trống.',

            'loai_nguyen_lieu_ids.*.required' => 'Loại nguyên liệu không được để trống.',

            'loai_nguyen_lieu_ids.*.exists' => 'Loại nguyên liệu không tồn tại.',

            'nguyen_lieu_ids.*.exists' => 'Nguyên liệu không tồn tại.',
            'nguyen_lieu_ids.*.nullable' => 'Nguyên liệu có thể để trống.',

            'don_vi_nhaps.required' => 'Đơn vị nhập là bắt buộc.',
            'don_vi_nhaps.*.required' => 'Đơn vị nhập không được để trống.',
            'don_vi_nhaps.*.regex' => 'Đơn vị nhập không được chứa số.',



            'so_luong_nhaps.required' => 'Số lượng nhập là bắt buộc.',
            'so_luong_nhaps.*.required' => 'Số lượng nhập không được để trống.',
            'so_luong_nhaps.*.min' => 'Số lượng nhập phải lớn hơn 0.',
            'so_luong_nhaps.*.max' => 'Số lượng nhập không được lớn hơn 500.',


            'don_gias.required' => 'Đơn giá là bắt buộc.',
            'don_gias.*.required' => 'Đơn giá không được để trống.',
            'don_gias.*.min' => 'Đơn giá phải lớn hơn 0.',
            'don_gias.*.required_if' => 'Đơn giá là bắt buộc khi loại phiếu là nhập từ nhà cung cấp.',


            'ngay_san_xuats.date' => 'Ngày sản xuất phải là một ngày hợp lệ.',
            'ngay_het_hans.date' => 'Hạn sử dụng phải là một ngày hợp lệ.',


            'ghi_chus.array' => 'Ghi chú phải là một mảng.',
            'ghi_chus.*.nullable' => 'Ghi chú có thể để trống.',
        ];
    }
}
