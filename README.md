# 🎓 Hệ thống Quản lý Sinh viên

Một hệ thống quản lý sinh viên toàn diện được xây dựng bằng PHP, MySQL và Tailwind CSS, cung cấp giao diện người dùng thân thiện và tính năng bảo mật cao.

## ✨ Tính năng chính

### 👨‍💼 Quản trị viên
- Quản lý thông tin sinh viên (thêm, sửa, xóa, tìm kiếm)
- Quản lý lớp học
- Quản lý khoa (thêm mới, cập nhật thông tin)
- Đăng thông báo cho sinh viên

### 👨‍🎓 Sinh viên
- Xem và cập nhật thông tin cá nhân
- Đổi mật khẩu
- Xem thông báo từ nhà trường

## 🛠️ Công nghệ sử dụng

- **Backend:** PHP 8.2.12
- **Database:** MySQL 10.4.32-MariaDB
- **Frontend:** 
  - Tailwind CSS 3.0
- **Security:** 
  - Password hashing
  - PDO với prepared statements
  - Session management
- **Server:** Apache/XAMPP

## 📋 Yêu cầu hệ thống

- PHP >= 8.2.12
- MySQL >= 10.4.32-MariaDB
- Apache Web Server
- PDO PHP Extension

## ⚡ Cài đặt

1. **Chuẩn bị môi trường**
   ```bash
   # Clone repository
   git clone [repository-url]

   # Di chuyển vào thư mục dự án
   cd quanly_sinhvien
   ```

2. **Cài đặt cơ sở dữ liệu**
   - Import file `database/quanly_sinhvien.sql` vào MySQL
   - Cập nhật thông tin kết nối trong `config/config.php`

3. **Cấu hình Apache**
   - Đảm bảo mod_rewrite được bật
   - Cấu hình Virtual Host (nếu cần)

## 🔒 Tài khoản mặc định

### Admin
- Username: `lhvinh`
- Password: `abc@123`

### Sinh viên mẫu
- MSSV: `2312401001`
- Password: `abcxyz`

## 🗃️ Cấu trúc thư mục

```
quanly_sinhvien/
├── admin/              # Trang quản trị
│   ├── class_*.php     # Quản lý lớp
│   ├── faculty_*.php   # Quản lý khoa
│   └── student_*.php   # Quản lý sinh viên
├── assets/            # Static files
│   ├── css/          # Stylesheets
│   ├── js/           # JavaScript
│   └── images/       # Hình ảnh
├── config/           # Cấu hình
│   └── config.php    # DB config
├── database/         # SQL files
|   └── pdo.php       # Kết nối DB
|   └── quanly_sinhvien.sql    # file DB mẫu
├── includes/         # Components
│   ├── header.php    # Header template
│   ├── footer.php    # Footer template
│   ├── sidebar.php   # Menu sidebar
│   └── init.php      # Initialization
└── index.php         # Trang chủ sinh viên
└── login.php         # Đăng nhập
└── logout.php        # Đăng xuất
└── notifications.php # Thông báo
└── profile.php       # Thông tin cá nhân
└── README.md         # Thông tin về dự án
```

## 🔐 Tính năng bảo mật

- Mã hóa mật khẩu với `password_hash()`
- Sử dụng PDO prepared statements
- Quản lý session an toàn
- Phân quyền người dùng

## 📝 Changelog

### Version 1.0.0 (2025-11-05)
- Phát hành phiên bản đầu tiên
- Tính năng cơ bản cho sinh viên và admin
- Giao diện responsive với Tailwind CSS
- Hệ thống thông báo
- Quản lý khoa và lớp

## 📄 License

Phần mềm này được phân phối dưới giấy phép MIT.
Xem file `LICENSE` để biết thêm chi tiết.

---
Made with ❤️ by Nhóm PHP cơ bản
