@extends('webapp.layout-form')

@section('title', 'Đăng tin thành công - Đà Lạt BDS')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #F5F7FB;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .success-page-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .success-card {
            background: #fff;
            border-radius: 24px;
            padding: 50px 30px 40px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.05);
            text-align: center;
            max-width: 420px;
            width: 100%;
            margin: 0 auto;
        }
        .success-image-wrap {
            width: 120px;
            height: 120px;
            margin: 0 auto 30px;
        }
        .success-image-wrap img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            animation: bounceIn 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }
        .success-card h1 {
            font-size: 28px;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 12px;
        }
        .success-description {
            font-size: 15px;
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 36px;
        }
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 12px;
            width: 100%;
        }
        .btn-action {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 14px 20px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 15px;
            transition: all 0.2s ease;
            text-decoration: none !important;
            border: 2px solid transparent;
            cursor: pointer;
        }
        .btn-primary-custom {
            background-color: #3270FC;
            color: #fff;
            box-shadow: 0 4px 12px rgba(50, 112, 252, 0.25);
        }
        .btn-primary-custom:hover {
            background-color: #1c5bca;
            color: #fff;
            transform: translateY(-1px);
        }
        .btn-secondary-custom {
            background-color: #fff;
            color: #3270FC;
            border-color: #e0e7ff;
        }
        .btn-secondary-custom:hover {
            border-color: #3270FC;
            background-color: #f0f5ff;
            color: #3270FC;
        }
        .btn-ghost-custom {
            background-color: #f9fafb;
            color: #6b7280;
            border-color: #f3f4f6;
        }
        .btn-ghost-custom:hover {
            background-color: #f3f4f6;
            color: #374151;
            border-color: #e5e7eb;
        }
        .back-home-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 24px;
            color: #9ca3af;
            font-size: 14px;
            text-decoration: none;
            transition: color 0.2s;
        }
        .back-home-link:hover {
            color: #3270FC;
        }
        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); opacity: 1; }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); }
        }
    </style>
@endpush

@section('content')
<div class="success-page-wrap">
    <div class="success-card">
        <div class="success-image-wrap">
            <img src="https://cdn-icons-png.flaticon.com/512/7518/7518748.png" alt="Success">
        </div>

        <h1>Đăng tin thành công!</h1>

        <div class="success-description">
            Tin của bạn đang chờ duyệt.<br>
            Chúng tôi sẽ thông báo sau khi hiển thị.
        </div>

        <div class="action-buttons">
            @if(isset($slug) && $slug)
                <a href="{{ route('bds.show', $slug) }}" class="btn-action btn-primary-custom">
                    <i class="fa-solid fa-eye" style="margin-right: 8px;"></i> Xem tin vừa đăng
                </a>
            @elseif(isset($propertyId) && $propertyId)
                <a href="{{ route('property.showid', ['id' => $propertyId]) }}" class="btn-action btn-primary-custom">
                    <i class="fa-solid fa-eye" style="margin-right: 8px;"></i> Xem tin vừa đăng
                </a>
            @endif

            <a href="{{ route('webapp.add_listing') }}" class="btn-action btn-secondary-custom">
                <i class="fa-solid fa-plus" style="margin-right: 8px;"></i> Đăng tin khác
            </a>

            <a href="/webapp#mybds" class="btn-action btn-ghost-custom">
                <i class="fa-solid fa-list-check" style="margin-right: 8px;"></i> Quản lý tin đăng
            </a>
        </div>

        <a href="/webapp" class="back-home-link">
            <i class="fa-solid fa-arrow-left"></i> Về trang chủ WebApp
        </a>
    </div>
</div>
@endsection
