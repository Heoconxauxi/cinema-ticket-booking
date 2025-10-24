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
    
class TaiKhoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $taiKhoans = TaiKhoan::with('nguoidung')->get();
        return response()->json(['success' => true, 'data' => $taiKhoans]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TenDangNhap' => 'required|string|max:50|unique:taikhoan,TenDangNhap',
            'MatKhau' => 'required|string|min:6|max:100',
            'TenND' => 'required|string|max:200',
            'Quyen' => 'required|integer',
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

            NguoiDung::create([
                'MaND' => $taiKhoan->MaND,
                'TenND' => $request->TenND,
                'NguoiTao' => $request->NguoiTao ?? 0,
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
            return response()->json([
                'success' => false,
                'message' => 'Tạo tài khoản thất bại. Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $taiKhoan = TaiKhoan::with(['nguoiDung'])
                            ->where('MaND', $id)
                            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $taiKhoan
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaiKhoan $taiKhoan) // Nhận TaiKhoan từ Route Model Binding
    {
        // 1. Tìm NguoiDung liên kết (nếu có)
        $nguoiDung = $taiKhoan->nguoiDung; // Sử dụng quan hệ đã định nghĩa

        // 2. Kết hợp Validation Rules
        $validator = Validator::make($request->all(), [
            // --- Quy tắc cho TaiKhoan ---
            'TenDangNhap' => [
                'required',
                'string',
                'max:50',
                Rule::unique('taikhoan')->ignore($taiKhoan->MaND, 'MaND') // Bỏ qua chính nó
            ],
            'MatKhau' => 'nullable|string|min:6|confirmed', // Mật khẩu không bắt buộc, cần _confirmation nếu có
            'Quyen' => 'nullable', 'integer',
            // Tên người dùng trong TaiKhoan (nếu cần cập nhật đồng bộ)
            'TenND_TaiKhoan' => 'required|string|max:200', // Đặt tên khác để phân biệt với TenND của NguoiDung

            // --- Quy tắc cho NguoiDung ---
            'TenND_NguoiDung' => 'required|string|max:255', // Tên người dùng trong NguoiDung
            'NgaySinh' => 'nullable|date',
            'GioiTinh' => 'nullable|boolean',
            'SDT' => 'nullable|string|max:10',
            'Email' => 'nullable|email|max:255',
            'Anh' => 'nullable|url|max:1000', // Sử dụng URL ảnh
            'TrangThai' => 'required|boolean', // Trạng thái của NguoiDung
        ]);

        // 3. Xử lý Validation Fails
        if ($validator->fails()) {
            // Nếu là request API thì trả về JSON
            if ($request->expectsJson()) {
                 return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            // Nếu là request từ web form, quay lại với lỗi và input cũ
            return back()->withErrors($validator)->withInput();
        }

        // 4. Bắt đầu Transaction
        DB::beginTransaction();
        try {
            // 5. Chuẩn bị và Cập nhật TaiKhoan
            $taiKhoanData = [
                'TenDangNhap' => $request->TenDangNhap,
                'TenND' => $request->TenND_TaiKhoan, // Cập nhật tên trong taikhoan
                'Quyen' => $request->input('Quyen', 0),
            ];
            if ($request->filled('MatKhau')) {
                $taiKhoanData['MatKhau'] = Hash::make($request->MatKhau);
            }
            $taiKhoan->update($taiKhoanData);

            // 6. Chuẩn bị và Cập nhật NguoiDung (nếu tồn tại)
            if ($nguoiDung) {
                $nguoiDungData = [
                    'TenND' => $request->TenND_NguoiDung, // Cập nhật tên trong nguoidung
                    'NgaySinh' => $request->NgaySinh,
                    'GioiTinh' => $request->has('GioiTinh') ? $request->GioiTinh : null,
                    'SDT' => $request->SDT,
                    'Email' => $request->Email,
                    'NguoiCapNhat' => Auth::id() ?? 0, // Lấy ID người đang đăng nhập
                    'NgayCapNhat' => now(),
                    'TrangThai' => $request->TrangThai,
                ];

                // Xử lý URL ảnh
                 if ($request->exists('Anh')) {
                    $nguoiDungData['Anh'] = $request->input('Anh') ?: null;
                }

                $nguoiDung->update($nguoiDungData);
            } else {
                // (Tùy chọn) Có thể ghi log hoặc báo lỗi nếu không tìm thấy NguoiDung tương ứng
                Log::warning("Cập nhật tài khoản {$taiKhoan->MaND} nhưng không tìm thấy hồ sơ người dùng tương ứng.");
            }

            // 7. Commit Transaction
            DB::commit();

             // 8. Trả về Response
             // Nếu là request API
             if ($request->expectsJson()) {
                 $taiKhoan->load('nguoidung'); // Load lại NguoiDung để trả về dữ liệu mới nhất
                 return response()->json([
                     'success' => true,
                     'message' => 'Cập nhật tài khoản và hồ sơ thành công!',
                     'data' => $taiKhoan // Trả về TaiKhoan đã load NguoiDung
                 ]);
             }
             // Nếu là request từ web form
             return redirect()->route('admin.taikhoan.index')->with('success', 'Cập nhật tài khoản thành công!');


        } catch (\Exception $e) {
            // 9. Rollback Transaction nếu có lỗi
            DB::rollBack();
            // \Log::error('Lỗi khi cập nhật tài khoản/người dùng: ' . $e->getMessage());

             // Trả về Response lỗi
             if ($request->expectsJson()) {
                 return response()->json([
                     'success' => false,
                     'message' => 'Cập nhật thất bại. Lỗi: ' . $e->getMessage() // Chỉ hiển thị lỗi chi tiết khi debug
                     // 'message' => 'Cập nhật thất bại. Đã xảy ra lỗi.' // Thông báo chung
                 ], 500);
             }
            return back()->withInput()->with('error', 'Đã xảy ra lỗi khi cập nhật.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaiKhoan $taiKhoan)
    {
        try {
            $taiKhoan->delete(); 
            
            return response()->json([
                'success' => true,
                'message' => 'Xóa tài khoản (và profile) thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xóa thất bại. Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }
}
