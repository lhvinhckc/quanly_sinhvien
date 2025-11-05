<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🎓</text></svg>">
  <title>Hệ thống quản lý sinh viên</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <?php
  // Hàm xác định active cho sidebar, hỗ trợ nhiều trang con
  function isActiveMenu($type) {
    $basename = basename($_SERVER['SCRIPT_NAME']);
    if ($type === 'student' && strpos($basename, 'student') !== false) return 'bg-blue-100 font-semibold';
    if ($type === 'class' && strpos($basename, 'class') !== false) return 'bg-blue-100 font-semibold';
    if ($type === 'faculty' && strpos($basename, 'faculty') !== false) return 'bg-blue-100 font-semibold';
    if ($type === 'dashboard' && $basename === 'index.php') return 'bg-blue-100 font-semibold';
    return '';
  }
  ?>
  <style>
    @media (max-width: 768px) {
      #sidebar.open {
        transform: translateX(0);
      }
      #overlay.open {
        display: block;
      }
    }
  </style>
</head>

<body class="bg-gray-100 font-sans text-gray-800">
  <!-- Nút toggle cho mobile -->
  <button onclick="toggleSidebar()" 
          class="fixed top-4 left-4 z-50 md:hidden bg-white p-2 rounded-lg shadow-lg hover:bg-gray-100">
    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
    </svg>
  </button>

  <!-- Overlay cho mobile -->
  <div id="overlay" 
       onclick="toggleSidebar()"
       class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden">
  </div>

  <!-- ===== SIDEBAR ===== -->
  <aside id="sidebar" 
         class="fixed top-0 left-0 h-full w-64 bg-white shadow-md z-40 md:translate-x-0 -translate-x-full transition-transform duration-300 ease-in-out">
    <div class="border-b h-16 flex items-center justify-center">
      <h1 class="text-xl font-bold text-blue-600">
        🎓<?= isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin' ? 'Quản trị viên' : 'Sinh viên'; ?>
      </h1>
    </div>

    <!-- MENU -->
    <nav class="p-4 space-y-2">
      <?php if ($_SESSION['user_role'] === 'admin'): ?>
        <!-- 🔸 Menu dành cho Admin -->
        <a href="index.php" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-blue-50 transition <?= isActiveMenu('dashboard') ?>">
          <span>🏠</span><span>Dashboard</span>
        </a>
        <a href="student_list.php" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-blue-50 transition <?= isActiveMenu('student') ?>">
          <span>👨‍🎓</span><span>Quản lý sinh viên</span>
        </a>
        <a href="class_list.php" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-blue-50 transition <?= isActiveMenu('class') ?>">
          <span>🏫</span><span>Quản lý lớp học</span>
        </a>
        <a href="faculty_list.php" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-blue-50 transition <?= isActiveMenu('faculty') ?>">
          <span>📚</span><span>Quản lý khoa</span>
        </a>
        <a href="logout.php" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-red-50 transition text-red-600">
          <span>🚪</span><span>Đăng xuất</span>
        </a>
      <?php else: ?>
        <!-- 🔸 Menu dành cho Sinh viên -->
        <a href="index.php" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-blue-50 transition <?= isActive('index.php') ?>">
          <span>🏠</span><span>Trang chủ</span>
        </a>
        <a href="profile.php" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-blue-50 transition <?= isActive('profile.php') ?>">
          <span>👤</span><span>Thông tin cá nhân</span>
        </a>
        <a href="change_password.php" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-blue-50 transition <?= isActive('change_password.php') ?>">
          <span>🔒</span><span>Đổi mật khẩu</span>
        </a>
        <a href="notifications.php" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-blue-50 transition <?= isActive('notifications.php') ?>">
          <span>📢</span><span>Thông báo</span>
        </a>
        <a href="logout.php" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-red-50 transition text-red-600">
          <span>🚪</span><span>Đăng xuất</span>
        </a>
      <?php endif; ?>
    </nav>
  </aside>

  <!-- Main content wrapper -->
  <div class="md:pl-64 min-h-screen">
    <script>
      function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        
        sidebar.classList.toggle('open');
        overlay.classList.toggle('open');
        
        // Prevent scrolling when sidebar is open on mobile
        document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
      }

      // Close sidebar when screen size becomes larger than mobile
      window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) {
          const sidebar = document.getElementById('sidebar');
          const overlay = document.getElementById('overlay');
          
          sidebar.classList.remove('open');
          overlay.classList.remove('open');
          document.body.style.overflow = '';
        }
      });
    </script>