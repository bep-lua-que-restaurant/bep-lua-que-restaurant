<?php


namespace App\Http\Controllers;

use App\Exports\MaGiamGiaExport;
use App\Models\MaGiamGia;
use App\Http\Requests\StoreMaGiamGiaRequest;
use App\Http\Requests\UpdateMaGiamGiaRequest;
use App\Imports\MaGiamGiaImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class MaGiamGiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MaGiamGia::query();

        if ($request->has('ten') && $request->ten != '') {
            $query->where('ten_ma_giam', 'like', '%' . $request->ten . '%');
        }

        $data = $query->latest('id')->paginate(15);
        
        // Xử lý trả về khi yêu cầu là Ajax
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.magiamgia.body-list', compact('data'))->render(),
            ]);
        }

        return view('admin.magiamgia.list', [
            'data' => $data,
            'route' => route('ma-giam-gia.index'), // URL route cho AJAX
            'tableId' => 'list-container', // ID của bảng
            'searchInputId' => 'search-name', // ID của ô tìm kiếm
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.magiamgia.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMaGiamGiaRequest $request)

    {
        //
        $data = $request->validated([
            'code'            => 'required|string|max:20|unique:ma_giam_gias,code',
            'type'            => 'required|in:percentage,fixed',
            'value'           => 'required|numeric|min:0.01',
            'min_order_value' => 'nullable|numeric|min:0',
            'start_date'      => 'required|date|after_or_equal:today',
            'end_date'        => 'required|date|after:start_date',
            'usage_limit'     => 'nullable|integer|min:0',
        ]);


        // $validated = $request->validated();


        // $validated['usage_limit'] = $validated['usage_limit'] ?? 0;

        // $maGiamGia->update($validated);

        // return back()->with('success', 'Cập nhật thành công!');
       // Lấy dữ liệu đã được validated theo các rule trong StoreMaGiamGiaRequest
    $validated = $request->validated();

    // Nếu không có usage_limit, gán mặc định là 0
    $validated['usage_limit'] = $validated['usage_limit'] ?? 0;

    // Tạo bản ghi mới với dữ liệu validated
    MaGiamGia::create($validated);

    return redirect()->route('ma-giam-gia.index')->with('success', 'Thêm mã giảm giá thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $maGiamGia = MaGiamGia::findOrFail($id);
        return view('admin.magiamgia.show', compact('maGiamGia'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)

    {
        $maGiamGia = MaGiamGia::findOrFail($id);
        return view('admin.magiamgia.edit', compact('maGiamGia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $maGiamGia = MaGiamGia::findOrFail($id);

        $validated = $request->validate([
            'code'            => 'required|string|max:20|unique:ma_giam_gias,code,' . $maGiamGia->id,
            'type'            => 'required|in:percentage,fixed',
            'value'           => 'required|numeric|min:0.01',
            'min_order_value' => 'nullable|numeric|min:0',
            'start_date'      => 'required|date|after_or_equal:today',
            'end_date'        => 'required|date|after:start_date',
            'usage_limit'     => 'nullable|integer|min:0',
        ]);

        // Nếu usage_limit không được cung cấp, đặt giá trị mặc định là 0
        $validated['usage_limit'] = $validated['usage_limit'] ?? 0;

        $maGiamGia->update($validated);
        return back()->with('success', 'Cập nhật thành công!');
    }

    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $maGiamGia = MaGiamGia::findOrFail($id);
        $maGiamGia->delete();

        return redirect()->route('ma-giam-gia.index')
            ->with('success', 'Mã giảm giá đã được xóa.');
    }

    public function restore($id)
    {
        $maGiamGia = MaGiamGia::withTrashed()->findOrFail($id);
        $maGiamGia->restore();

        return redirect()->route('ma-giam-gia.index')->with('success', 'Khôi phục thành công!');
    }

    public function export()
    {
        // Xuất file Excel với tên "DanhMucMonAn.xlsx"
        return Excel::download(new MaGiamGiaExport, 'MaGiamGia.xlsx');
    }

    public function importMaGiamGia(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new MaGiamGiaImport, $request->file('file'));

        return back()->with('success', 'Nhập dữ liệu thành công!');
    }
}


 
 namespace App\Http\Controllers;
 
 use App\Exports\MaGiamGiaExport;
 use App\Models\MaGiamGia;
 use App\Http\Requests\StoreMaGiamGiaRequest;
 use App\Http\Requests\UpdateMaGiamGiaRequest;
 use App\Imports\MaGiamGiaImport;
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Storage;
 use Maatwebsite\Excel\Facades\Excel;
 
 class MaGiamGiaController extends Controller
 {
     /**
      * Display a listing of the resource.
      */
     public function index(Request $request)
     {
         $query = MaGiamGia::query();
 
         if ($request->has('ten') && $request->ten != '') {
             $query->where('ten_ma_giam', 'like', '%' . $request->ten . '%');
         }
 
         $data = $query->latest('id')->paginate(15);
         
         // Xử lý trả về khi yêu cầu là Ajax
         if ($request->ajax()) {
             return response()->json([
                 'html' => view('admin.magiamgia.body-list', compact('data'))->render(),
             ]);
         }
 
         return view('admin.magiamgia.list', [
             'data' => $data,
             'route' => route('ma-giam-gia.index'), // URL route cho AJAX
             'tableId' => 'list-container', // ID của bảng
             'searchInputId' => 'search-name', // ID của ô tìm kiếm
         ]);
     }
 
     /**
      * Show the form for creating a new resource.
      */
     public function create()
     {
         return view('admin.magiamgia.create');
     }
 
     /**
      * Store a newly created resource in storage.
      */
     public function store(StoreMaGiamGiaRequest $request)
 
     {
         //
         $data = $request->validated([
             'code'            => 'required|string|max:20|unique:ma_giam_gias,code',
             'type'            => 'required|in:percentage,fixed',
             'value'           => 'required|numeric|min:0.01',
             'min_order_value' => 'nullable|numeric|min:0',
             'start_date'      => 'required|date|after_or_equal:today',
             'end_date'        => 'required|date|after:start_date',
             'usage_limit'     => 'nullable|integer|min:0',
         ]);
 
 
         // $validated = $request->validated();
 
 
         // $validated['usage_limit'] = $validated['usage_limit'] ?? 0;
 
         // $maGiamGia->update($validated);
 
         // return back()->with('success', 'Cập nhật thành công!');
        // Lấy dữ liệu đã được validated theo các rule trong StoreMaGiamGiaRequest
     $validated = $request->validated();
 
     // Nếu không có usage_limit, gán mặc định là 0
     $validated['usage_limit'] = $validated['usage_limit'] ?? 0;
 
     // Tạo bản ghi mới với dữ liệu validated
     MaGiamGia::create($validated);
 
     return redirect()->route('ma-giam-gia.index')->with('success', 'Thêm mã giảm giá thành công!');
     }
 
     /**
      * Display the specified resource.
      */
     public function show($id)
     {
         $maGiamGia = MaGiamGia::findOrFail($id);
         return view('admin.magiamgia.show', compact('maGiamGia'));
     }
 
     /**
      * Show the form for editing the specified resource.
      */
     public function edit($id)
 
     {
         $maGiamGia = MaGiamGia::findOrFail($id);
         return view('admin.magiamgia.edit', compact('maGiamGia'));
     }
 
     /**
      * Update the specified resource in storage.
      */
     public function update(Request $request, $id)
     {
         $maGiamGia = MaGiamGia::findOrFail($id);
 
         $validated = $request->validate([
             'code'            => 'required|string|max:20|unique:ma_giam_gias,code,' . $maGiamGia->id,
             'type'            => 'required|in:percentage,fixed',
             'value'           => 'required|numeric|min:0.01',
             'min_order_value' => 'nullable|numeric|min:0',
             'start_date'      => 'required|date|after_or_equal:today',
             'end_date'        => 'required|date|after:start_date',
             'usage_limit'     => 'nullable|integer|min:0',
         ]);
 
         // Nếu usage_limit không được cung cấp, đặt giá trị mặc định là 0
         $validated['usage_limit'] = $validated['usage_limit'] ?? 0;
 
         $maGiamGia->update($validated);
         return back()->with('success', 'Cập nhật thành công!');
     }
 
     
     /**
      * Remove the specified resource from storage.
      */
     public function destroy($id)
     {
         $maGiamGia = MaGiamGia::findOrFail($id);
         $maGiamGia->delete();
 
         return redirect()->route('ma-giam-gia.index')
             ->with('success', 'Mã giảm giá đã được xóa.');
     }
 
     public function restore($id)
     {
         $maGiamGia = MaGiamGia::withTrashed()->findOrFail($id);
         $maGiamGia->restore();
 
         return redirect()->route('ma-giam-gia.index')->with('success', 'Khôi phục thành công!');
     }
 
     public function export()
     {
         // Xuất file Excel với tên "DanhMucMonAn.xlsx"
         return Excel::download(new MaGiamGiaExport, 'MaGiamGia.xlsx');
     }
 
     public function importMaGiamGia(Request $request)
     {
         $request->validate([
             'file' => 'required|mimes:xlsx,xls'
         ]);
 
         Excel::import(new MaGiamGiaImport, $request->file('file'));
 
         return back()->with('success', 'Nhập dữ liệu thành công!');
     }

 }


