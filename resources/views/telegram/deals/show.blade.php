<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quản Lý Deal #{{ $deal->id }}</title>
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: var(--tg-theme-bg-color, #f5f5f5);
            color: var(--tg-theme-text-color, #000000);
            font-family: sans-serif;
            padding-bottom: 80px;
        }
        .header-card {
            background: linear-gradient(135deg, #3390ec, #007bff);
            color: white;
            padding: 16px;
            border-radius: 0 0 16px 16px;
            margin-bottom: 16px;
        }
        .product-card {
            background-color: var(--tg-theme-secondary-bg-color, #ffffff);
            border-radius: 12px;
            border: none;
            margin: 0 16px 16px 16px;
            padding: 0;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .product-img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        .product-body {
            padding: 12px;
        }
        .product-title {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 4px;
        }
        .product-meta {
            font-size: 13px;
            color: var(--tg-theme-hint-color, #666);
            margin-bottom: 8px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            background-color: #e9ecef;
            color: #495057;
            margin-bottom: 8px;
        }
        .btn-group-custom {
            display: flex;
            border-top: 1px solid #eee;
        }
        .btn-custom {
            flex: 1;
            padding: 10px;
            text-align: center;
            background: none;
            border: none;
            font-size: 14px;
            font-weight: 500;
            color: var(--tg-theme-button-color, #3390ec);
            border-right: 1px solid #eee;
        }
        .btn-custom:last-child {
            border-right: none;
        }
        .btn-custom:active {
            background-color: rgba(0,0,0,0.05);
        }
        /* Modal styles */
        .modal-content {
            background-color: var(--tg-theme-bg-color, #fff);
            color: var(--tg-theme-text-color, #000);
        }
    </style>
</head>
<body>
    <div class="header-card">
        <h5 class="m-0">Deal #{{ $deal->id }}</h5>
        <div class="mt-2">
            <i class="fas fa-user"></i> {{ $deal->customer->full_name ?? 'N/A' }}
        </div>
        <div class="mt-1">
            <i class="fas fa-money-bill-wave"></i> {{ number_format($deal->amount) }}
        </div>
    </div>

    <div class="container-fluid px-0">
        <h6 class="px-3 mb-2 text-muted">DANH SÁCH BĐS ({{ $deal->products->count() }})</h6>
        
        @foreach($deal->products as $product)
            <div class="product-card" id="card-{{ $product->id }}">
                @if($product->property && $product->property->title_image)
                    <img src="{{ asset($product->property->title_image) }}" class="product-img" alt="Property">
                @else
                    <div class="product-img d-flex align-items-center justify-content-center bg-light text-muted">
                        <i class="fas fa-home fa-3x"></i>
                    </div>
                @endif
                
                <div class="product-body">
                    <div class="product-title">{{ $product->property->title ?? 'BĐS Không xác định' }}</div>
                    <div class="product-meta">
                        {{ number_format($product->property->price ?? 0) }} • {{ $product->property->area ?? 0 }} m²
                    </div>
                    
                    @php
                        $statusLabel = $product->status instanceof \UnitEnum ? $product->status->label() : $product->status;
                        $statusColor = match($product->status->value ?? $product->status) {
                            'sent_info' => 'bg-info text-white',
                            'viewed_success' => 'bg-success text-white',
                            'viewed_failed' => 'bg-danger text-white',
                            'negotiating' => 'bg-warning text-dark',
                            default => 'bg-secondary text-white'
                        };
                    @endphp
                    <span class="status-badge {{ $statusColor }}" id="status-badge-{{ $product->id }}">
                        {{ $statusLabel }}
                    </span>

                    @if($product->bookings->count() > 0)
                        <div class="small text-muted mt-1">
                            <i class="far fa-calendar-check"></i> Lịch xem gần nhất: {{ $product->bookings->first()->booking_date->format('d/m') ?? 'N/A' }}
                        </div>
                    @endif
                </div>

                <div class="btn-group-custom">
                    <button class="btn-custom" onclick="openStatusModal({{ $product->id }}, '{{ $product->status->value ?? $product->status }}')">
                        <i class="fas fa-sync-alt"></i> Cập nhật
                    </button>
                    <button class="btn-custom" onclick="openBookingModal({{ $product->id }})">
                        <i class="far fa-calendar-plus"></i> Đặt lịch
                    </button>
                    <button class="btn-custom" onclick="showFeedback({{ $product->id }})">
                        <i class="far fa-comment-dots"></i> Phản hồi
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Status Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cập nhật trạng thái</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="modalProductId">
                    <div class="mb-3">
                        <label class="form-label">Trạng thái mới</label>
                        <select class="form-select" id="statusSelect">
                            @foreach(\App\Enums\DealsProductStatus::cases() as $status)
                                <option value="{{ $status->value }}">{{ $status->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3" id="noteField">
                        <label class="form-label">Ghi chú (Bắt buộc nếu thất bại)</label>
                        <textarea class="form-control" id="statusNote" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary w-100" onclick="submitStatus()">Lưu thay đổi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Đặt lịch xem BĐS</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="bookingProductId">
                    <div class="mb-3">
                        <label class="form-label">Ngày xem</label>
                        <input type="date" class="form-control" id="bookingDate" min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Giờ xem</label>
                        <input type="time" class="form-control" id="bookingTime">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea class="form-control" id="bookingNote" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary w-100" onclick="submitBooking()">Tạo lịch hẹn</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const tg = window.Telegram.WebApp;
        tg.expand();
        
        // Modal instances
        var statusModalEl = document.getElementById('statusModal');
        var statusModal = new bootstrap.Modal(statusModalEl);
        
        var bookingModalEl = document.getElementById('bookingModal');
        var bookingModal = new bootstrap.Modal(bookingModalEl);

        function openStatusModal(id, currentStatus) {
            document.getElementById('modalProductId').value = id;
            
            // Set current status in select
            var select = document.getElementById('statusSelect');
            for(var i=0; i<select.options.length; i++) {
                if(select.options[i].value == currentStatus) {
                    select.selectedIndex = i;
                    break;
                }
            }
            
            statusModal.show();
        }

        function submitStatus() {
            var id = document.getElementById('modalProductId').value;
            var status = document.getElementById('statusSelect').value;
            var note = document.getElementById('statusNote').value;

            if (status === 'viewed_failed' && !note.trim()) {
                tg.showAlert("Vui lòng nhập ghi chú khi xem thất bại!");
                return;
            }
            
            // Close modal first
            statusModal.hide();

            tg.MainButton.showProgress();

            // Using fetch to call API
            // Note: Since this is blade, we might need CSRF token if we used POST form, 
            // but for API call we need X-CSRF-TOKEN header or just rely on API token if configured.
            // Here we assume session-based auth (since it's a web route view), so CSRF is needed.
            
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/api/deals/products/' + id, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ 
                    status: status, 
                    note: note 
                })
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                tg.MainButton.hideProgress();
                if (data.success) {
                    tg.showAlert("Cập nhật thành công!", function() {
                        location.reload();
                    });
                } else {
                    tg.showAlert("Lỗi: " + (data.message || 'Unknown error'));
                }
            })
            .catch(function(error) {
                tg.MainButton.hideProgress();
                tg.showAlert("Lỗi kết nối: " + error);
            });
        }


        function openBookingModal(id) {
            document.getElementById('bookingProductId').value = id;
            // Default to tomorrow 09:00
            // Not setting default date/time via JS for now, user picks.
            bookingModal.show();
        }

        function submitBooking() {
            var id = document.getElementById('bookingProductId').value;
            var date = document.getElementById('bookingDate').value;
            var time = document.getElementById('bookingTime').value;
            var note = document.getElementById('bookingNote').value;

            if (!date || !time) {
                tg.showAlert("Vui lòng chọn ngày và giờ!");
                return;
            }

            bookingModal.hide();
            tg.MainButton.showProgress();

            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/api/deals/products/' + id + '/bookings', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ 
                    booking_date: date, 
                    booking_time: time,
                    note: note 
                })
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                tg.MainButton.hideProgress();
                if (data.success) {
                    tg.showAlert("Đặt lịch thành công!", function() {
                        location.reload();
                    });
                } else {
                    tg.showAlert("Lỗi: " + (data.message || (data.errors ? JSON.stringify(data.errors) : 'Unknown error')));
                }
            })
            .catch(function(error) {
                tg.MainButton.hideProgress();
                tg.showAlert("Lỗi kết nối: " + error);
            });
        }

        function showFeedback(id) {
             tg.showAlert("Tính năng Xem phản hồi đang phát triển");
        }
    </script>
</body>
</html>