<?php
require_once __DIR__ . '/includes/init.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['sinhvien'])) {
    header('Location: ' . INDEX_URL . 'login.php');
    exit();
}

// Lấy thông tin sinh viên
$mssv = $_SESSION['sinhvien'];
$sinh_vien = DB::selectOne("
    SELECT sv.*, l.tenlop, k.tenkhoa 
    FROM sinh_vien sv 
    LEFT JOIN lop l ON sv.malop = l.malop 
    LEFT JOIN khoa k ON sv.makhoa = k.makhoa 
    WHERE sv.mssv = :mssv", 
    ['mssv' => $mssv]
);

if (!$sinh_vien) {
    $_SESSION['error'] = 'Không tìm thấy thông tin sinh viên!';
    header('Location: ' . INDEX_URL . 'logout.php');
    exit();
}

// Lấy 5 thông báo mới nhất
$thong_bao = DB::select("
    SELECT id, tieude, created_at 
    FROM thong_bao 
    ORDER BY created_at DESC 
    LIMIT 5"
);

include('includes/sidebar.php');
include('includes/header.php');

// Flash Messages
if (!empty($_SESSION['success'])) {
    echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 max-w-7xl mx-auto">' 
        . htmlspecialchars($_SESSION['success']) . '</div>';
    unset($_SESSION['success']);
}
if (!empty($_SESSION['error'])) {
    echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 max-w-7xl mx-auto">' 
        . htmlspecialchars($_SESSION['error']) . '</div>';
    unset($_SESSION['error']);
}
?>

<!-- CHÀO MỪNG -->
<section class="bg-blue-600 text-white rounded-xl p-6 shadow mb-6">
    <h2 class="text-2xl font-semibold">Xin chào, <?php echo htmlspecialchars($sinh_vien['hoten']); ?> 👋</h2>
    <p class="mt-2 text-blue-100 text-sm">
        Chào mừng bạn đến với hệ thống quản lý sinh viên. 
        <?php if ($sinh_vien['trangthai'] === 'Đang học'): ?>
            Hãy kiểm tra thông báo mới và cập nhật thông tin cá nhân của bạn.
        <?php else: ?>
            Trạng thái hiện tại của bạn: <strong><?php echo htmlspecialchars($sinh_vien['trangthai']); ?></strong>
        <?php endif; ?>
    </p>
</section>

<!-- THÔNG TIN CÁ NHÂN -->
<section class="bg-white rounded-xl shadow p-6 mb-6">
    <h3 class="text-lg font-semibold text-blue-700 border-b pb-2 mb-4">
        <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        Thông tin sinh viên
    </h3>
    <div class="grid sm:grid-cols-2 gap-6 text-sm">
        <div class="space-y-2">
            <p><strong>Mã số SV:</strong> <?php echo htmlspecialchars($sinh_vien['mssv']); ?></p>
            <p><strong>Họ và tên:</strong> <?php echo htmlspecialchars($sinh_vien['hoten']); ?></p>
            <p><strong>Ngày sinh:</strong> <?php echo $sinh_vien['ngaysinh'] ? date('d/m/Y', strtotime($sinh_vien['ngaysinh'])) : 'Chưa cập nhật'; ?></p>
            <p><strong>Giới tính:</strong> <?php echo htmlspecialchars($sinh_vien['gioitinh']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($sinh_vien['email'] ?? 'Chưa cập nhật'); ?></p>
        </div>
        <div class="space-y-2">
            <p><strong>Lớp:</strong> <?php echo htmlspecialchars($sinh_vien['tenlop'] ?? 'Chưa cập nhật'); ?></p>
            <p><strong>Khoa:</strong> <?php echo htmlspecialchars($sinh_vien['tenkhoa'] ?? 'Chưa cập nhật'); ?></p>
            <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($sinh_vien['sdt'] ?? 'Chưa cập nhật'); ?></p>
            <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($sinh_vien['diachi'] ?? 'Chưa cập nhật'); ?></p>
            <p><strong>Trạng thái:</strong> 
                <span class="<?php echo $sinh_vien['trangthai'] === 'Đang học' ? 'text-green-600' : 'text-yellow-600'; ?>">
                    <?php echo htmlspecialchars($sinh_vien['trangthai']); ?>
                </span>
            </p>
        </div>
    </div>
    <div class="mt-6">
        <a href="profile.php" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-md text-sm inline-block transition duration-200">
            ✏️ Cập nhật thông tin
        </a>
        <a href="change_password.php" class="ml-3 bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-md text-sm inline-block transition duration-200">
            🔑 Đổi mật khẩu
        </a>
    </div>
</section>

<!-- THÔNG BÁO -->
<section class="bg-white rounded-xl shadow p-6">
    <h3 class="text-lg font-semibold text-blue-700 border-b pb-2 mb-4">
        <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        Thông báo mới
    </h3>
    <?php if (count($thong_bao) > 0): ?>
        <ul class="space-y-3 text-sm">
            <?php foreach ($thong_bao as $index => $tb): ?>
                <li class="<?php echo $index < count($thong_bao) - 1 ? 'border-b pb-2' : ''; ?>">
                    <strong>[<?php echo date('d/m/Y', strtotime($tb['created_at'])); ?>]</strong>
                    <a href="notifications.php?id=<?php echo $tb['id']; ?>" class="text-blue-600 hover:text-blue-800">
                        <?php echo htmlspecialchars($tb['tieude']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="mt-4 text-right">
            <a href="notifications.php" class="text-blue-600 hover:text-blue-800 text-sm">
                Xem tất cả thông báo →
            </a>
        </div>
    <?php else: ?>
        <p class="text-gray-500 text-sm">Chưa có thông báo nào.</p>
    <?php endif; ?>

<?php include('includes/footer.php'); ?>