<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TaiKhoan;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException; // <-- Thêm import để bắt lỗi 404

class TaiKhoanController extends Controller
{
    /**
    * Display a listing of the resource.
    */
    public function index()
    {
        try {
            $taiKhoans = TaiKhoan::with('nguoidung')->get();
            return response()->json(['success' => true, 'data' => $taiKhoans]);
        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy danh sách tài khoản: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Không thể lấy danh sách tài khoản.'], 500);
        }
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TenDangNhap' => 'required|string|max:50|unique:taikhoan,TenDangNhap',
            'MatKhau' => 'required|string|min:6|max:100|confirmed', // <-- Thêm 'confirmed'
            'TenND' => 'required|string|max:200',
            'Quyen' => 'required|integer',
            'Email' => 'nullable|email|max:255|unique:nguoidung,Email', // <-- Thêm validation cho Email
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $taiKhoan = TaiKhoan::create([
                'TenDangNhap' => $request->TenDangNhap,
                'MatKhau' => Hash::make($request->MatKhau),
                'TenND' => $request->TenND,
                'Quyen' => $request->Quyen,
            ]);

            nguoidung::create([
                'MaND' => $taiKhoan->MaND,
                'TenND' => $request->TenND,
                'NguoiTao' => Auth::id() ?? $request->NguoiTao ?? 0, // <-- Lấy Auth ID
                'NgayTao' => now(),
                'TrangThai' => 1,
                'Email' => $request->Email ?? null,
            ]);

            DB::commit(); 
            
            $taiKhoan->load('nguoidung');
            
            return response()->json([
                'success' => true,
                'message' => 'Tạo tài khoản thành công!',
                'data' => $taiKhoan
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack(); 
            // **Cải tiến: Ghi log và trả về lỗi bảo mật**
            Log::error('Lỗi khi tạo tài khoản: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Tạo tài khoản thất bại. Đã xảy ra lỗi máy chủ.' // <-- Thông báo chung
            ], 500);
        }
    }

    /**
    * Display the specified resource.
    */
    public function show($id)
    {
        try {
            $taiKhoan = TaiKhoan::with(['nguoidung'])
                                ->where('MaND', $id)
                                ->firstOrFail(); // <-- Dùng firstOrFail

            return response()->json([
                'success' => true,
                'data' => $taiKhoan
            ]);
        
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy tài khoản.'
            ], 404); // <-- Trả về 404
        
        } catch (\Exception $e) {
            Log::error("Lỗi khi tìm tài khoản (ID: $id): " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi máy chủ.'
            ], 500);
        }
    }

    /**
    * Update the specified resource in storage.
    */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            // Bảng TaiKhoan
            'TenDangNhap' => [
                'required',
                'string',
                'max:50',
                Rule::unique('taikhoan')->ignore($id, 'MaND'), // Bỏ qua chính user này
            ],
            'TenND' => 'required|string|max:200',
            'MatKhau' => 'nullable|string|min:6', // Cho phép không cập nhật mật khẩu

            // Bảng NguoiDung
            'Email' => 'nullable|email|max:255',
            'NgaySinh' => 'nullable|date_format:Y-m-d',
            'GioiTinh' => 'nullable|boolean',
            'SDT' => 'nullable|string|max:10',
            // --- THAY ĐỔI ---
            'Anh' => 'nullable|string|max:255', // Đổi từ 'image' sang 'string' (để nhận URL)
        ], [
            'TenDangNhap.unique' => 'Tên đăng nhập này đã được sử dụng.',
            'MatKhau.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Bắt đầu transaction
        try {
            DB::beginTransaction();

            // 1. Tìm và cập nhật TaiKhoan
            $taiKhoan = TaiKhoan::findOrFail($id);
            
            $taiKhoanData = [
                'TenDangNhap' => $request->input('TenDangNhap'),
                'TenND' => $request->input('TenND'), // Cập nhật TenND ở bảng taikhoan
            ];

            // Chỉ cập nhật mật khẩu nếu nó được cung cấp (không rỗng)
            if ($request->filled('MatKhau')) {
                $taiKhoanData['MatKhau'] = Hash::make($request->input('MatKhau'));
            }

            $taiKhoan->update($taiKhoanData);

            // 2. Tìm và cập nhật NguoiDung
            $nguoiDung = NguoiDung::findOrFail($id);

            $nguoiDungData = [
                'TenND' => $request->input('TenND'), // Cập nhật TenND ở bảng nguoidung
                'NgaySinh' => $request->input('NgaySinh'),
                'GioiTinh' => $request->input('GioiTinh'),
                'SDT' => $request->input('SDT'),
                'Email' => $request->input('Email'),
                'NguoiCapNhat' => $id, // Yêu cầu: NguoiCapNhat là MaND của chính tài khoản
                // --- THAY ĐỔI ---
                'Anh' => $request->input('Anh'), // Lấy thẳng URL từ input
            ];

            // --- ĐÃ XÓA ---
            // Đã xóa toàn bộ khối `if ($request->hasFile('Anh')) { ... }`
            // vì chúng ta không còn xử lý file upload.

            $nguoiDung->update($nguoiDungData);

            // Xác nhận transaction
            DB::commit();

            // Trả về dữ liệu đã cập nhật (gồm cả thông tin NguoiDung)
            $taiKhoan->load('nguoiDung'); 
            
            return response()->json([
                'message' => 'Cập nhật thông tin người dùng thành công!',
                'data' => $taiKhoan
            ], 200);

        } catch (\Exception $e) {
            // Hoàn tác lại nếu có lỗi
            DB::rollBack();
            return response()->json([
                'message' => 'Cập nhật thất bại. Đã có lỗi xảy ra.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
    * Remove the specified resource from storage.
    */
    public function destroy(TaiKhoan $taiKhoan)
    {
        // **Cải tiến: Thêm Transaction cho hàm destroy**
        DB::beginTransaction();
        try {
            // Tùy chọn: Xóa nguoidung liên kết trước
            if ($taiKhoan->nguoidung) {
                $taiKhoan->nguoidung->delete();
            }
            // Sau đó xóa TaiKhoan (Nếu bạn không set 'on delete cascade' ở DB)
            $taiKhoan->delete(); 
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Xóa tài khoản thành công!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            // **Cải tiến: Ghi log và trả về lỗi bảo mật**
            Log::error("Lỗi khi xóa tài khoản (MaND: {$taiKhoan->MaND}): " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Xóa thất bại. Đã xảy ra lỗi máy chủ.' // <-- Thông báo chung
            ], 500);
        }
    }
}

