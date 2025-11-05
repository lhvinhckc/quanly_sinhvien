<?php
require_once __DIR__ . '/../includes/init.php';

// Kiểm tra xem có phải admin không
if (!isset($_SESSION['admin'])) {
    header('Location: ' . INDEX_URL . 'admin/login.php');
    exit();
}

// Lấy mã khoa từ URL
$makhoa = trim($_GET['makhoa'] ?? '');
if ($makhoa === '') {
    $_SESSION['error'] = 'Không tìm thấy mã khoa.';
    header('Location: ' . INDEX_URL . 'admin/faculty_list.php');
    exit();
}

// Kiểm tra khoa có tồn tại không và lấy tên khoa
$khoa = DB::selectOne('
    SELECT k.*, COUNT(l.malop) as solop 
    FROM khoa k 
    LEFT JOIN lop l ON k.makhoa = l.makhoa 
    WHERE k.makhoa = :makhoa 
    GROUP BY k.makhoa', 
    ['makhoa' => $makhoa]
);

if (!$khoa) {
    $_SESSION['error'] = 'Không tìm thấy khoa cần xóa.';
    header('Location: ' . INDEX_URL . 'admin/faculty_list.php');
    exit();
}

try {
    // Bắt đầu transaction
    DB::beginTransaction();

    // Xóa các lớp thuộc khoa
    DB::delete('lop', 'makhoa = :makhoa', ['makhoa' => $makhoa]);

    // Xóa khoa
    DB::delete('khoa', 'makhoa = :makhoa', ['makhoa' => $makhoa]);

    // Commit transaction
    DB::commit();

    $_SESSION['success'] = sprintf(
        'Đã xóa thành công khoa %s và %d lớp thuộc khoa!',
        $khoa['tenkhoa'],
        $khoa['solop']
    );
} catch (Exception $e) {
    // Rollback nếu có lỗi
    DB::rollBack();
    $_SESSION['error'] = 'Có lỗi xảy ra khi xóa khoa. Vui lòng thử lại.';
}

header('Location: ' . INDEX_URL . 'admin/faculty_list.php');
exit();
