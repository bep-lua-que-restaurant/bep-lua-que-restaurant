<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;

class UpdatePhieuNhapKhoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'ma_phieu' => [
                'required',
                'string',
                'max:255',
                Rule::unique('phieu_nhap_khos', 'ma_phieu')->ignore($this->phieu_nhap_kho),
            ],
            'loai_phieu' => 'required|in:nhap_tu_bep,nhap_tu_ncc',

            'nha_cung_cap_id' => [
                'nullable',
                'required_if:loai_phieu,nhap_tu_ncc',
                'exists:nha_cung_caps,id'
            ],

            'nhan_vien_id' => 'required|exists:nhan_viens,id',
            'ghi_chu' => 'nullable|string',

            'ten_nguyen_lieus' => 'nullable|array',
            'ten_nguyen_lieus.*' => 'nullable|string',

            'loai_nguyen_lieu_ids' => 'required|array',
            'loai_nguyen_lieu_ids.*' => 'required|exists:loai_nguyen_lieus,id',

            'nguyen_lieu_ids' => 'nullable|array',
            'nguyen_lieu_ids.*' => 'nullable|exists:nguyen_lieus,id',

            'don_vi_nhaps' => 'required|array',
            'don_vi_nhaps.*' => ['required', 'string', 'regex:/^[^\d]*$/'],

            'so_luong_nhaps' => 'required|array',
            'so_luong_nhaps.*' => 'required|numeric|min:0.01',

            'don_gias' => [
                'nullable',
                'array',
                'required_if:loai_phieu,nhap_tu_ncc',
            ],
            'don_gias.*' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'ngay_san_xuats' => 'nullable|array',
            'ngay_san_xuats.*' => 'nullable|date',

            'ngay_het_hans' => 'nullable|array',
            'ngay_het_hans.*' => 'nullable|date',

            'ghi_chus' => 'nullable|array',
            'ghi_chus.*' => 'nullable|string',

            'chi_tiet_ids' => 'nullable|array',
            'chi_tiet_ids.*' => 'exists:chi_tiet_phieu_nhap_khos,id',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $ngaySanXuats = $this->input('ngay_san_xuats', []);
            $hanSuDungs = $this->input('ngay_het_hans', []);
            $tenNguyenLieus = $this->input('ten_nguyen_lieus', []);
            $nguyenLieuIds = $this->input('nguyen_lieu_ids', []);

            foreach ($hanSuDungs as $index => $han) {
                $sanXuat = $ngaySanXuats[$index] ?? null;

                if ($han && $sanXuat) {
                    try {
                        $ngaySX = Carbon::parse($sanXuat);
                        $hanSD = Carbon::parse($han);

                        if ($hanSD->lt($ngaySX)) {
                            $validator->errors()->add("ngay_het_hans.$index", 'Hạn sử dụng phải sau hoặc bằng ngày sản xuất.');
                        }
                    } catch (\Exception $e) {
                        $validator->errors()->add("ngay_het_hans.$index", 'Hạn sử dụng hoặc ngày sản xuất không hợp lệ.');
                    }
                }
            }

            $max = max(count($tenNguyenLieus), count($nguyenLieuIds));
            for ($i = 0; $i < $max; $i++) {
                $ten = $tenNguyenLieus[$i] ?? null;
                $id = $nguyenLieuIds[$i] ?? null;

                if (empty($ten) && empty($id)) {
                    $validator->errors()->add("ten_nguyen_lieus.$i", 'Bạn phải nhập tên nguyên liệu hoặc chọn từ danh sách.');
                }

                if (!empty($ten) && empty($id)) {
                    if (!is_string($ten) || strlen($ten) < 1) {
                        $validator->errors()->add("ten_nguyen_lieus.$i", 'Tên nguyên liệu nhập tay không hợp lệ.');
                    }
                }

                if (empty($ten) && !empty($id)) {
                    if (!\App\Models\NguyenLieu::find($id)) {
                        $validator->errors()->add("nguyen_lieu_ids.$i", 'ID nguyên liệu không tồn tại trong hệ thống.');
                    }
                }
            }
        });
    }

    public function messages()
    {
        return [
            'ma_phieu.required' => 'Mã phiếu nhập kho là bắt buộc.',
            'ma_phieu.unique' => 'Mã phiếu nhập kho đã tồn tại.',

            'nha_cung_cap_id.required_if' => 'Nhà cung cấp là bắt buộc khi nhập từ NCC.',
            'nhan_vien_id.required' => 'Nhân viên là bắt buộc.',
            'loai_phieu.required' => 'Loại phiếu là bắt buộc.',

            'ten_nguyen_lieus.required' => 'Tên nguyên liệu là bắt buộc.',
            'ten_nguyen_lieus.*.required' => 'Tên nguyên liệu không được để trống.',

            'loai_nguyen_lieu_ids.required' => 'Loại nguyên liệu là bắt buộc.',
            'loai_nguyen_lieu_ids.*.exists' => 'Loại nguyên liệu không tồn tại.',

            'nguyen_lieu_ids.*.exists' => 'Nguyên liệu không tồn tại.',

            'don_vi_nhaps.required' => 'Đơn vị nhập là bắt buộc.',
            'don_vi_nhaps.*.required' => 'Đơn vị nhập không được để trống.',
            'don_vi_nhaps.*.regex' => 'Đơn vị nhập không được chứa số.',

            'so_luong_nhaps.required' => 'Số lượng nhập là bắt buộc.',
            'so_luong_nhaps.*.min' => 'Số lượng nhập phải lớn hơn 0.',

            'don_gias.required_if' => 'Đơn giá là bắt buộc khi loại phiếu là nhập từ nhà cung cấp.',
            'don_gias.*.min' => 'Đơn giá phải lớn hơn hoặc bằng 0.',

            'ngay_san_xuats.*.date' => 'Ngày sản xuất phải là một ngày hợp lệ.',
            'ngay_het_hans.*.date' => 'Hạn sử dụng phải là một ngày hợp lệ.',

            'ghi_chus.array' => 'Ghi chú phải là một mảng.',
            'ghi_chus.*.nullable' => 'Ghi chú có thể để trống.',

            'chi_tiet_ids.*.exists' => 'Chi tiết không hợp lệ.',
        ];
    }
}
