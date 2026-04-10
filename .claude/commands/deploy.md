Deploy DalatBDS lên production server (host cPanel đã cấu hình auto-deploy).

Chỉ cần push lên nhánh `main` trên GitHub — cPanel sẽ tự động kéo code mới về và chạy `.cpanel.yml`.

## Các bước thực hiện

1. Chạy `git status` để kiểm tra trạng thái working tree.

2. Nếu có file chưa commit:
   - Hỏi người dùng commit message (hoặc dùng message từ $ARGUMENTS nếu được cung cấp)
   - Stage các file đã thay đổi: `git add -u`
   - Commit với message đó

3. Kiểm tra branch hiện tại bằng `git branch --show-current`.

4. Nếu không ở nhánh `main`:
   - Thông báo sẽ merge vào main
   - `git checkout main`
   - `git pull origin main` (đảm bảo main local up-to-date)
   - `git merge <tên-branch-trước> --no-ff -m "Deploy: merge <branch> into main"`

5. Push lên GitHub: `git push -u origin main`

6. Báo kết quả cho người dùng:
   - Branch nào vừa được push
   - Bao nhiêu commit mới
   - Nhắc nhở: cPanel sẽ tự động pull code và chạy `.cpanel.yml` trên server
