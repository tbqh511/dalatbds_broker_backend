<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quản Lý Hoa Hồng</title>
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: var(--tg-theme-bg-color, #f5f5f5);
            color: var(--tg-theme-text-color, #000000);
            font-family: sans-serif;
            padding-bottom: 20px;
        }
        .header {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 20px;
            border-radius: 0 0 20px 20px;
            margin-bottom: 20px;
            text-align: center;
        }
        .total-amount {
            font-size: 24px;
            font-weight: bold;
        }
        .card {
            background-color: var(--tg-theme-secondary-bg-color, #ffffff);
            border-radius: 12px;
            border: none;
            margin-bottom: 12px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .card-body {
            padding: 12px;
        }
        .status-badge {
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: 600;
        }
        .bg-pending_deposit { background-color: #ffc107; color: #000; }
        .bg-deposited { background-color: #17a2b8; color: #fff; }
        .bg-notarizing { background-color: #6f42c1; color: #fff; }
        .bg-completed { background-color: #28a745; color: #fff; }
        .bg-cancelled { background-color: #dc3545; color: #fff; }
        
        .filter-bar {
            overflow-x: auto;
            white-space: nowrap;
            padding: 0 10px 10px 10px;
        }
        .filter-chip {
            display: inline-block;
            padding: 6px 12px;
            background-color: #e9ecef;
            border-radius: 20px;
            margin-right: 8px;
            font-size: 13px;
            color: #495057;
            cursor: pointer;
        }
        .filter-chip.active {
            background-color: var(--tg-theme-button-color, #3390ec);
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <div>Tổng Hoa Hồng</div>
        <div class="total-amount" id="totalAmount">0 VNĐ</div>
        <div class="small mt-1" id="totalDeals">0 Giao dịch</div>
    </div>

    <div class="filter-bar">
        <span class="filter-chip active" onclick="filterStatus('all', this)">Tất cả</span>
        <span class="filter-chip" onclick="filterStatus('pending_deposit', this)">Chờ cọc</span>
        <span class="filter-chip" onclick="filterStatus('completed', this)">Hoàn tất</span>
    </div>

    <div class="container" id="commissionList">
        <div class="text-center mt-5">
            <div class="spinner-border text-primary" role="status"></div>
        </div>
    </div>

    <script>
        const tg = window.Telegram.WebApp;
        tg.expand();

        let currentFilter = 'all';

        function loadData() {
            // Get InitData for Auth (Assuming backend handles it or we use session)
            // For now, simple fetch assuming session or public for demo
            
            let url = '/api/commissions/report';
            if (currentFilter !== 'all') {
                url += `?status=${currentFilter}`;
            }

            fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    // 'Authorization': 'Bearer ...'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    renderHeader(data.data.summary);
                    renderList(data.data.details);
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById('commissionList').innerHTML = '<div class="text-center text-danger">Lỗi tải dữ liệu</div>';
            });
        }

        function renderHeader(summary) {
            // Format currency
            const formatter = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' });
            document.getElementById('totalAmount').innerText = formatter.format(summary.total_amount);
            document.getElementById('totalDeals').innerText = `${summary.total_deals} Giao dịch`;
        }

        function renderList(items) {
            const container = document.getElementById('commissionList');
            container.innerHTML = '';

            if (items.length === 0) {
                container.innerHTML = '<div class="text-center text-muted mt-4">Không có dữ liệu</div>';
                return;
            }

            items.forEach(item => {
                const formatter = new Intl.NumberFormat('vi-VN');
                const statusClass = `bg-${item.status}`;
                
                // Status label mapping (simple)
                const labels = {
                    'pending_deposit': 'Chờ cọc',
                    'deposited': 'Đã cọc',
                    'notarizing': 'Công chứng',
                    'completed': 'Hoàn tất',
                    'cancelled': 'Hủy'
                };

                const html = `
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="m-0 text-truncate" style="max-width: 70%;">${item.property ? item.property.title : 'BĐS #' + item.property_id}</h6>
                                <span class="status-badge ${statusClass}">${labels[item.status] || item.status}</span>
                            </div>
                            <div class="mb-1 text-muted small">
                                <i class="fas fa-map-marker-alt"></i> ${item.property ? item.property.address : 'N/A'}
                            </div>
                            <div class="d-flex justify-content-between align-items-end mt-3">
                                <div>
                                    <div class="small text-muted">Hoa hồng Sale</div>
                                    <div class="fw-bold text-success">${formatter.format(item.sale_commission)}</div>
                                </div>
                                <div class="text-end">
                                    <div class="small text-muted">Ngày tạo</div>
                                    <div class="small">${new Date(item.created_at).toLocaleDateString('vi-VN')}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.innerHTML += html;
            });
        }

        function filterStatus(status, element) {
            currentFilter = status;
            
            // Update UI
            document.querySelectorAll('.filter-chip').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
            
            // Reload
            document.getElementById('commissionList').innerHTML = '<div class="text-center mt-5"><div class="spinner-border text-primary"></div></div>';
            loadData();
        }

        // Init
        loadData();

    </script>
</body>
</html>