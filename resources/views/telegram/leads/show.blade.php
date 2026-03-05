<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chi Tiết Lead #{{ $lead->id }}</title>
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: var(--tg-theme-bg-color, #f5f5f5);
            color: var(--tg-theme-text-color, #000000);
            font-family: sans-serif;
            padding: 16px;
        }
        .card {
            background-color: var(--tg-theme-secondary-bg-color, #ffffff);
            border-radius: 12px;
            border: none;
            margin-bottom: 16px;
            padding: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .label {
            font-size: 12px;
            color: var(--tg-theme-hint-color, #888);
            margin-bottom: 4px;
            text-transform: uppercase;
        }
        .value {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 12px;
            word-break: break-word;
        }
        .btn-action {
            width: 100%;
            margin-bottom: 8px;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            cursor: pointer;
        }
        .btn-confirm {
            background-color: var(--tg-theme-button-color, #3390ec);
            color: var(--tg-theme-button-text-color, #fff);
        }
        .btn-secondary-custom {
            background-color: transparent;
            border: 1px solid var(--tg-theme-button-color, #3390ec);
            color: var(--tg-theme-button-color, #3390ec);
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="label">KHÁCH HÀNG</div>
        <div class="value">{{ $lead->customer->full_name ?? 'N/A' }}</div>
        
        <div class="label">SỐ ĐIỆN THOẠI</div>
        <div class="value">
            <a href="tel:{{ $lead->customer->contact }}" style="text-decoration: none; color: var(--tg-theme-link-color, #3390ec);">
                {{ $lead->customer->contact }} 📞
            </a>
        </div>
    </div>

    <div class="card">
        <div class="label">NHU CẦU</div>
        <div class="value">
            {{ ucfirst($lead->lead_type) }} 
            @if($lead->demand_rate_min > 0)
                <br>
                <small>{{ number_format($lead->demand_rate_min) }} - {{ number_format($lead->demand_rate_max) }}</small>
            @endif
        </div>
        
        <div class="label">GHI CHÚ</div>
        <div class="value">{{ $lead->note ?? 'Không có ghi chú' }}</div>
        
        <div class="label">TRẠNG THÁI</div>
        <div class="value" id="statusValue" style="color: var(--tg-theme-button-color, #3390ec);">
            {{ ucfirst($lead->status) }}
        </div>
    </div>

    <div id="actions">
        <!-- Buttons rendered via JS -->
    </div>

    <script>
        const tg = window.Telegram.WebApp;
        tg.expand();
        
        const leadId = {{ $lead->id }};
        // Use PHP to inject status safely
        let currentStatus = "{{ $lead->status }}";

        function renderActions() {
            const container = document.getElementById('actions');
            container.innerHTML = '';

            if (currentStatus === 'new') {
                const btn = document.createElement('button');
                btn.className = 'btn-action btn-confirm';
                btn.innerText = '✅ Tiếp nhận (Confirm)';
                btn.onclick = () => updateStatus('contacted');
                container.appendChild(btn);
            } else if (currentStatus === 'contacted') {
                const btnDeal = document.createElement('button');
                btnDeal.className = 'btn-action btn-confirm';
                btnDeal.innerText = '🚀 Tạo Deal';
                btnDeal.onclick = () => createDeal();
                container.appendChild(btnDeal);

                const btnFail = document.createElement('button');
                btnFail.className = 'btn-action btn-secondary-custom';
                btnFail.innerText = '❌ Thất bại / K.O.K';
                btnFail.onclick = () => updateStatus('lost');
                container.appendChild(btnFail);
            }
        }

        function updateStatus(newStatus) {
            if(!confirm('Bạn chắc chắn muốn cập nhật trạng thái?')) return;

            tg.MainButton.showProgress();
            
            // I'll assume POST /telegram/leads/{id}/update works.
            
            fetch(`/telegram/leads/${leadId}/update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(res => res.json())
            .then(data => {
                tg.MainButton.hideProgress();
                if (!data.error && data.success !== false) {
                    tg.showAlert("Đã cập nhật trạng thái!");
                    currentStatus = newStatus;
                    document.getElementById('statusValue').innerText = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                    renderActions();
                } else {
                    tg.showAlert("Lỗi: " + (data.message || 'Unknown error'));
                }
            })
            .catch(err => {
                tg.MainButton.hideProgress();
                tg.showAlert("Lỗi kết nối: " + err.message);
            });
        }

        function createDeal() {
             tg.showAlert("Chuyển sang màn hình tạo Deal...");
             // Logic to open Deal creation form
        }

        renderActions();

    </script>
</body>
</html>