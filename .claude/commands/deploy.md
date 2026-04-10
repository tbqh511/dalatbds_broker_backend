Deploy DalatBDS lên production server.

Flow: commit → push GitHub main → gọi cPanel UAPI → cPanel tự pull & deploy.

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

## Bước 3: Trigger cPanel deploy qua UAPI

Đọc credentials từ file `.claude/cpanel.env`. Nếu file không tồn tại, hướng dẫn user tạo từ `.claude/cpanel.env.example`.

Sau khi load credentials, chạy lệnh curl sau:

```bash
source .claude/cpanel.env && curl -s -k \
  -H "Authorization: cpanel ${CPANEL_USER}:${CPANEL_TOKEN}" \
  -X POST \
  "https://${CPANEL_HOST}:${CPANEL_PORT}/execute/VersionControlDeployment/create" \
  --data-urlencode "repository_root=${CPANEL_REPO_PATH}"
```

## Bước 4: Kiểm tra kết quả

Parse JSON response:
- Nếu `"status": 1` → thành công, báo deploy đang chạy trên server
- Nếu `"status": 0` → thất bại, show field `errors`
- Nếu curl lỗi (không có cpanel.env hoặc network lỗi) → báo user kiểm tra `.claude/cpanel.env`

## Kết quả mong đợi

Báo cáo:
- Branch và commit vừa push
- Trạng thái deploy trên cPanel
- Nhắc: deployment đang chạy trên server, có thể xem tiến trình tại cPanel > Git Version Control
