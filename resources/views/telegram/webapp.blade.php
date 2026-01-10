<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đà Lạt BDS WebApp</title>
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
            text-align: center;
        }
        #status {
            margin-top: 20px;
            font-weight: bold;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <h1>Đà Lạt BDS</h1>
    <div id="loading">Đang đăng nhập...</div>
    <div id="content" class="hidden">
        <p>Xin chào, <span id="user_name"></span></p>
        <p>Token đã được lưu.</p>
    </div>
    <div id="status"></div>

    <script>
        // 1. Lấy Telegram WebApp Object
        const tg = window.Telegram.WebApp;
        tg.expand(); // Mở full màn hình

        // 2. Hàm Auto Login
        async function seamlessLogin() {
            // initData là chuỗi chứa chữ ký hash của Telegram
            const initData = tg.initData;

            if (!initData) {
                console.log("Không chạy trong môi trường Telegram!");
                document.getElementById('loading').innerText = "Không chạy trong môi trường Telegram!";
                return;
            }

            try {
                // 3. Gửi initData về Laravel để "soi"
                const response = await axios.post('/api/webapp/login', {
                    initData: initData
                });

                const data = response.data;

                if (data.status === 'authenticated') {
                    // --- THÀNH CÔNG ---
                    console.log("Đăng nhập thành công!", data.user);
                    
                    // 4. Lưu JWT vào LocalStorage để dùng cho các request sau
                    localStorage.setItem('auth_token', data.access_token);
                    
                    // Hiển thị tên user lên giao diện
                    document.getElementById('user_name').innerText = data.user.name;
                    document.getElementById('loading').classList.add('hidden');
                    document.getElementById('content').classList.remove('hidden');
                    document.getElementById('status').innerText = "Đăng nhập thành công!";
                    document.getElementById('status').style.color = "green";
                    
                    // Ẩn màn hình loading, hiện Dashboard...
                    
                } else if (data.status === 'guest') {
                    // --- CHƯA CÓ TÀI KHOẢN ---
                    // Hiển thị thông báo: "Vui lòng quay lại Bot chat và bấm nút Chia sẻ số điện thoại để kích hoạt tài khoản"
                    alert("Bạn chưa có tài khoản. Vui lòng chat với Bot và chia sẻ số điện thoại trước.");
                    document.getElementById('status').innerText = "Bạn chưa có tài khoản.";
                    document.getElementById('status').style.color = "red";
                    tg.close(); // Đóng Mini App
                }

            } catch (error) {
                console.error("Lỗi xác thực:", error);
                document.getElementById('loading').classList.add('hidden');
                document.getElementById('status').innerText = "Lỗi xác thực!";
                document.getElementById('status').style.color = "red";
                if(error.response && error.response.status === 403) {
                     alert("Lỗi bảo mật: Chữ ký không hợp lệ!");
                }
            }
        }

        // Chạy ngay khi mở App
        window.onload = seamlessLogin;
    </script>
</body>
</html>
