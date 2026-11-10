# XÁM SNEAKER — MVP (Frontend + Backend chung một website)

Website bán giày PHP thuần + MySQL. **Frontend và Backend dùng chung một hosting, một domain, một CSDL, một repository GitHub**. Đây là bản MVP phục vụ kiểm tra kết nối CSDL, không phải bản sản xuất.

## Cấu trúc

```
website/                    # Toàn bộ website PHP: cả giao diện khách hàng và Admin
  index.php                 # Trang chủ (Frontend)
  admin/index.php           # Quản trị (Backend, yêu cầu role admin)
  admin/db_check.php        # Trang kiểm tra MySQL (chỉ Admin truy cập)
  includes/db.php           # Kết nối MySQL dùng chung
  includes/config.example.php
  assets/                   # CSS, JS, hình ảnh
database/xamsneaker_mvp.sql # Cấu trúc 5 bảng + sản phẩm demo
.gitignore                  # Không đưa mật khẩu CSDL lên GitHub
```

## Cách có đúng 2 link nộp giảng viên

- **Website (FE + BE chung):** `https://TEN-MIEN-CUA-BAN/`
- **GitHub mới:** `https://github.com/ducvualis/xam-sneaker-mvp`

Link trên là **ví dụ định dạng**, chỉ nộp sau khi bạn tạo repository và triển khai thành công. Trong website, Admin ở `/admin/` (cùng domain), **không cần một URL nộp riêng**.

## Triển khai lên hosting PHP + MySQL

1. Tạo hosting có PHP 8.x, phần mở rộng `mysqli`, MySQL/MariaDB.
2. Tạo một database trống và một user MySQL, cấp quyền cho user.
3. Mở phpMyAdmin, chọn database đó, **Import `database/xamsneaker_mvp.sql`**. Script chứa DROP TABLE, chỉ import vào CSDL TRỐNG.
4. Mở file `website/includes/config.example.php`, tạo bản sao `website/includes/config.local.php`, điền host, port, username, password, database name của hosting.
5. Chỉ **upload NỘI DUNG bên trong `website/`** vào `public_html/` hoặc `htdocs/` — KHÔNG upload thư mục `database/` hay README ra web.
6. Mở `https://TEN-MIEN-CUA-BAN/`. Trang chủ phải tải sản phẩm từ MySQL.
7. Đăng ký một tài khoản ở `/register.php`. Sau đó mở phpMyAdmin và cấp quyền Admin cho chính tài khoản mới đăng ký bằng lệnh:

   ```sql
   UPDATE users SET role='admin' WHERE email='EMAIL_CUA_BAN';
   ```

8. Đăng xuất rồi đăng nhập lại, vào `/admin/` và chọn **Kiểm tra CSDL** để xem số bản ghi truy vấn trực tiếp.
9. Trình diễn việc thêm một sản phẩm từ Admin rồi kiểm tra sản phẩm ở trang chủ; đặt thử đơn để kiểm tra `orders` và `order_items`.

**Không đưa `config.local.php` lên GitHub và không chụp màn hình chứa mật khẩu DB.**

## Cách tạo repository GitHub MỚI (không dùng lại repo cũ)

1. Mở https://github.com/new và đặt tên `xam-sneaker-mvp`, chọn **Public**, nhấn **Create repository**. Không chọn tạo README mẫu để khỏi xung đột.
2. Giải nén ZIP dự án. Mở Terminal trong thư mục chứa `README.md`, `website/` và `database/`, chạy:

   ```bash
   git init -b main
   git add .
   git commit -m "Initial XAM SNEAKER PHP MySQL MVP"
   git remote add origin https://github.com/ducvualis/xam-sneaker-mvp.git
   git push -u origin main
   ```

   Nếu `git commit` yêu cầu thông tin tác giả, cấu hình `git config --global user.name "Tên của bạn"` và `git config --global user.email "email-cua-ban@example.com"` rồi commit lại. Nếu tên repository khác, chỉnh URL `origin` tương ứng.

3. Mở repository và kiểm tra đã thấy các file `.php` trong `website/`, không phải chỉ một file ZIP.

## Phạm vi và kiểm thử

- Đây là bản được chuẩn bị triển khai, **chưa có xác nhận website/MySQL đang chạy trên hosting thật**.
- Các file PHP đã được kiểm tra cú pháp với `php -l` trong môi trường xây dựng.
- Kết nối thực tế phụ thuộc vào hosting, thông tin MySQL và cấu hình truy cập.
- Dự án chưa được kiểm toán bảo mật toàn diện; chỉ dùng dữ liệu demo và tài khoản thử nghiệm.
- GitHub Pages không thực thi PHP hay MySQL; không dùng nó làm hosting cho bản này.
