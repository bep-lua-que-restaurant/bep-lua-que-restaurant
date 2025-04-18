<?php

namespace App\Http\Controllers;

use App\Models\BillImage;
use App\Models\HoaDon;
use Illuminate\Http\Request;

class BillImageController extends Controller
{
    public function create()
    {
        // Lấy danh sách hóa đơn (hoặc có thể dùng một điều kiện nào đó)
        $hoaDons = HoaDon::all(); // Hoặc bạn có thể tùy chỉnh điều kiện

        // Trả về view upload ảnh với danh sách hóa đơn
        return view('gdnhanvien.thungan.upload_bill', compact('hoaDons'));
    }


    public function saveBillImage(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'invoiceCode' => 'required|string|regex:/^HD-\d{8}-[A-Z0-9]{4}$/',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Tìm hóa đơn theo mã
            $hoaDon = HoaDon::where('ma_hoa_don', $request->invoiceCode)->first();
            if (!$hoaDon) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy hóa đơn với mã ' . $request->invoiceCode,
                ], 404);
            }

            // Lấy file ảnh
            $image = $request->file('image');

            // Tạo tên file: [invoiceCode].[extension]
            $extension = $image->getClientOriginalExtension();
            $fileName = $request->invoiceCode . '.' . $extension;

            // Tạo đường dẫn: bill_images/YYYYMMDD/
            // Lấy ngày từ mã hóa đơn (YYYYMMDD)
            $datePart = substr($request->invoiceCode, 3, 8); // Lấy "20250419" từ "HD-20250419-9DEC"
            $path = "bill_images/{$datePart}";

            // Lưu ảnh với tên và đường dẫn mới
            $imagePath = $image->storeAs($path, $fileName, 'public');

            // Lưu vào bảng bill_images
            BillImage::create([
                'hoa_don_id' => $hoaDon->id,
                'image_path' => $imagePath,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lưu ảnh hóa đơn thành công',
            ]);
        } catch (\Exception $e) {
         
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lưu ảnh: ' . $e->getMessage(),
            ], 500);
        }
    }
}
