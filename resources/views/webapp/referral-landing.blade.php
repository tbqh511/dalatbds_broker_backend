<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tham gia Đà Lạt BĐS</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f8fafc; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { background: #fff; border-radius: 20px; padding: 40px 28px; max-width: 420px; width: 100%; text-align: center; box-shadow: 0 4px 32px rgba(0,0,0,0.10); }
        .logo { font-size: 36px; margin-bottom: 8px; }
        .title { font-size: 22px; font-weight: 800; color: #1e293b; margin-bottom: 6px; }
        .subtitle { font-size: 14px; color: #64748b; margin-bottom: 24px; }
        .referrer-box { background: #3270FC; color: #fff; border-radius: 14px; padding: 16px 20px; margin-bottom: 24px; }
        .referrer-label { font-size: 11px; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
        .referrer-name { font-size: 18px; font-weight: 700; }
        .referrer-code { font-size: 13px; opacity: 0.85; margin-top: 4px; font-family: monospace; }
        .benefit { display: flex; align-items: flex-start; gap: 10px; text-align: left; margin-bottom: 12px; }
        .benefit-icon { font-size: 20px; flex-shrink: 0; }
        .benefit-text { font-size: 13px; color: #374151; line-height: 1.5; }
        .benefit-text strong { color: #1e293b; }
        .cta { background: #3270FC; color: #fff; border: none; border-radius: 12px; padding: 14px 28px; font-size: 16px; font-weight: 700; cursor: pointer; width: 100%; margin-top: 20px; text-decoration: none; display: block; }
        .cta:hover { opacity: 0.92; }
        .note { font-size: 11px; color: #94a3b8; margin-top: 12px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">🏡</div>
        <div class="title">Đà Lạt BĐS</div>
        <div class="subtitle">Nền tảng môi giới bất động sản Đà Lạt</div>

        <div class="referrer-box">
            <div class="referrer-label">Bạn được giới thiệu bởi</div>
            <div class="referrer-name">{{ $referrer->name ?? 'Thành viên' }}</div>
            <div class="referrer-code">Mã: {{ $code }}</div>
        </div>

        <div class="benefit">
            <div class="benefit-icon">💰</div>
            <div class="benefit-text"><strong>Nhận hoa hồng hấp dẫn</strong> từ mỗi giao dịch bất động sản thành công tại Đà Lạt.</div>
        </div>
        <div class="benefit">
            <div class="benefit-icon">🤝</div>
            <div class="benefit-text"><strong>Xây dựng mạng lưới</strong> môi giới chuyên nghiệp — giới thiệu người khác và nhận 5% thu nhập của họ.</div>
        </div>
        <div class="benefit">
            <div class="benefit-icon">📱</div>
            <div class="benefit-text"><strong>Quản lý dễ dàng</strong> qua Telegram — leads, deals, hoa hồng ngay trên điện thoại.</div>
        </div>

        <a href="https://t.me/{{ config('services.telegram.bot_username', 'DalatBDSBot') }}?start={{ $code }}" class="cta">
            Tham gia qua Telegram
        </a>
        <div class="note">Mã giới thiệu sẽ được ghi nhận tự động khi bạn đăng ký.</div>
    </div>
</body>
</html>
