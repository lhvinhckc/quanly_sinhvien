<?php
require_once __DIR__ . '/../includes/init.php';

// Kiểm tra xem có phải admin không
if (!isset($_SESSION['admin'])) {
    header('Location: ' . INDEX_URL . 'admin/login.php');
    exit();
}

// Lấy mã lớp từ URL
$malop = trim($_GET['malop'] ?? '');
if ($malop === '') {
    $_SESSION['error'] = 'Không tìm thấy mã lớp.';
    header('Location: ' . INDEX_URL . 'admin/class_list.php');
    exit();
}

// Kiểm tra lớp có tồn tại không và lấy tên lớp
$lop = DB::selectOne('
    SELECT l.*, k.tenkhoa, COUNT(sv.mssv) as sosv 
    FROM lop l 
    LEFT JOIN khoa k ON l.makhoa = k.makhoa 
    LEFT JOIN sinh_vien sv ON l.malop = sv.malop 
    WHERE l.malop = :malop 
    GROUP BY l.malop', 
    ['malop' => $malop]
);

if (!$lop) {
    $_SESSION['error'] = 'Không tìm thấy lớp cần xóa.';
    header('Location: ' . INDEX_URL . 'admin/class_list.php');
    exit();
}

try {
    // Bắt đầu transaction
    DB::beginTransaction();

    // Cập nhật các sinh viên thuộc lớp này (set malop = NULL)
    DB::update('sinh_vien', 
        ['malop' => null],
        'malop = :malop',
        ['malop' => $malop]
    );

    // Xóa lớp
    DB::delete('lop', 'malop = :malop', ['malop' => $malop]);

    // Commit transaction
    DB::commit();

    $_SESSION['success'] = sprintf(
        'Đã xóa thành công lớp %s và gỡ %d sinh viên khỏi lớp!',
        $lop['tenlop'],
        $lop['sosv']
    );
} catch (Exception $e) {
    // Rollback nếu có lỗi
    DB::rollBack();
    $_SESSION['error'] = 'Có lỗi xảy ra khi xóa lớp. Vui lòng thử lại.';
}

header('Location: ' . INDEX_URL . 'admin/class_list.php');
exit();
