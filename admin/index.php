<?php
require_once __DIR__ . '/../includes/init.php';

// Kiểm tra xem có phải admin không
if (!isset($_SESSION['admin'])) {
  header('Location: ' . INDEX_URL . 'admin/login.php');
  exit();
}

include('../includes/sidebar.php');
include('../includes/header.php');
?>

<!-- CHÀO MỪNG -->
<section class="bg-blue-600 text-white rounded-xl p-6 shadow">
  <h2 class="text-2xl font-semibold">Xin chào, <?php if (isset($_SESSION['fullname'])) {
                                                  echo htmlspecialchars($_SESSION['fullname']);
                                                } else {
                                                  echo 'Khách';
                                                } ?>👋</h2>
  <p class="mt-2 text-blue-100 text-sm">
    Đây là bảng điều khiển trung tâm — nơi bạn có thể xem thống kê, quản lý sinh viên, lớp học và khoa một cách nhanh chóng.
  </p>
</section>

<?php
// Lấy thống kê từ database
$stats = [
    'total_students' => DB::selectOne('SELECT COUNT(*) as count FROM sinh_vien')['count'] ?? 0,
    'total_classes' => DB::selectOne('SELECT COUNT(*) as count FROM lop')['count'] ?? 0,
    'total_faculties' => DB::selectOne('SELECT COUNT(*) as count FROM khoa')['count'] ?? 0
];
?>
<!-- THỐNG KÊ TỔNG QUAN -->
<section class="grid grid-cols-1 sm:grid-cols-3 gap-6">
  <div class="bg-white p-5 rounded-xl shadow text-center hover:shadow-md transition">
    <h3 class="text-gray-500 text-sm uppercase">Tổng sinh viên</h3>
    <p class="text-3xl font-bold text-blue-600 mt-1"><?php echo number_format($stats['total_students']); ?></p>
  </div>
  <div class="bg-white p-5 rounded-xl shadow text-center hover:shadow-md transition">
    <h3 class="text-gray-500 text-sm uppercase">Số lớp học</h3>
    <p class="text-3xl font-bold text-green-600 mt-1"><?php echo number_format($stats['total_classes']); ?></p>
  </div>
  <div class="bg-white p-5 rounded-xl shadow text-center hover:shadow-md transition">
    <h3 class="text-gray-500 text-sm uppercase">Số khoa</h3>
    <p class="text-3xl font-bold text-yellow-500 mt-1"><?php echo number_format($stats['total_faculties']); ?></p>
  </div>
</section>

<?php
// Lấy thống kê chi tiết theo khoa
$faculty_stats = DB::select("
    SELECT 
        k.makhoa,
        k.tenkhoa,
        COUNT(DISTINCT l.malop) as total_classes,
        COUNT(sv.mssv) as total_students
    FROM khoa k
    LEFT JOIN lop l ON l.makhoa = k.makhoa
    LEFT JOIN sinh_vien sv ON sv.malop = l.malop
    GROUP BY k.makhoa, k.tenkhoa
    ORDER BY total_students DESC
");
?>
<!-- THỐNG KÊ THEO KHOA -->
<section class="bg-white rounded-xl shadow p-6">
  <h3 class="text-lg font-semibold text-blue-700 border-b pb-2 mb-4">📊 Thống kê sinh viên theo khoa</h3>
  <div class="overflow-x-auto">
    <table class="min-w-full border border-gray-200 text-sm text-left">
      <thead class="bg-gray-100">
        <tr>
          <th class="px-4 py-2 border">STT</th>
          <th class="px-4 py-2 border">Tên khoa</th>
          <th class="px-4 py-2 border">Số lớp</th>
          <th class="px-4 py-2 border">Số sinh viên</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($faculty_stats as $index => $stat): ?>
        <tr class="hover:bg-blue-50 transition">
          <td class="px-4 py-2 border"><?php echo $index + 1; ?></td>
          <td class="px-4 py-2 border"><?php echo htmlspecialchars($stat['tenkhoa']); ?></td>
          <td class="px-4 py-2 border"><?php echo number_format($stat['total_classes']); ?></td>
          <td class="px-4 py-2 border font-semibold text-blue-600"><?php echo number_format($stat['total_students']); ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($faculty_stats)): ?>
        <tr>
          <td colspan="4" class="px-4 py-2 border text-center text-gray-500">Chưa có dữ liệu</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

<?php include('../includes/footer.php'); ?>