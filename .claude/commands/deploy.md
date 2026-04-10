Deploy DalatBDS lên production server.

Flow: commit → push GitHub main → gọi webhook trên server → server tự git pull & rebuild cache.

## Bước 1: Kiểm tra và commit

Chạy `git status`. Nếu có file chưa commit:
- Hỏi commit message (hoặc dùng $ARGUMENTS nếu có)
- `git add -u`
- Commit với message đó

## Bước 2: Push lên GitHub main

Kiểm tra branch hiện tại (`git branch --show-current`).

Nếu không ở `main`:
- `git checkout main`
- `git pull origin main`
- `git merge <branch-trước> --no-ff -m "Deploy: merge <branch> into main"`

Chạy: `git push -u origin main`

## Bước 3: Trigger deploy qua webhook

Đọc credentials từ file `.claude/cpanel.env`. Nếu file không tồn tại, hướng dẫn user tạo từ `.claude/cpanel.env.example`.

Sau khi load credentials, chạy lệnh curl sau:

```bash
source .claude/cpanel.env && curl -s \
  "${WEBHOOK_URL}?token=${WEBHOOK_SECRET}"
```

## Bước 4: Kiểm tra kết quả

Parse JSON response:
- Nếu `"status": 1` → thành công, báo deploy hoàn tất
- Nếu `"status": 0` → thất bại, show field `error` và `steps` để debug
- Nếu curl lỗi (network, 403, 404) → kiểm tra:
  - `.claude/cpanel.env` có đủ `WEBHOOK_URL` và `WEBHOOK_SECRET` không
  - `WEBHOOK_SECRET` trên server (`.env` của Laravel) có khớp không
  - File `public/webhook/deploy.php` đã được deploy lên server chưa

## Kết quả mong đợi

Báo cáo:
- Branch và commit vừa push
- Từng bước deploy trên server (git pull, cache clear/rebuild)
- Trạng thái cuối: thành công hay thất bại
