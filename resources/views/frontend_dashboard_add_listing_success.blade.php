@extends('frontends.master')

@section('title', 'Đăng tin thành công - Đà Lạt BDS')

@push('styles')
    <style>
        .success-page-wrap {
            min-height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #F5F7FB;
            padding: 40px 0;
        }
        .success-card {
            background: #fff;
            border-radius: 24px;
            padding: 50px 40px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.05);
            text-align: center;
            max-width: 600px;
            width: 100%;
            margin: 0 auto;
            position: relative;
            overflow: hidden;
        }
        
        /* Icon Image Animation */
        .success-image-wrap {
            width: 120px;
            height: 120px;
            margin: 0 auto 30px;
            position: relative;
        }
        .success-image-wrap img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            animation: bounceIn 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        .success-card h1 {
            font-size: 32px;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 16px;
            letter-spacing: -0.5px;
        }
        
        /* Centered Description */
        .success-description {
            font-size: 16px;
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 40px;
            max-width: 480px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Button Grid Layout */
        .action-buttons-grid {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Row 1: 2 buttons */
            gap: 16px;
            width: 100%;
        }
        
        /* Row 2: Full width button */
        .action-buttons-grid .btn-full-width {
            grid-column: span 2;
        }

        .btn-action {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px 20px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 15px;
            transition: all 0.2s ease;
            text-decoration: none !important;
            border: 2px solid transparent;
        }
        
        /* Button Styles */
        .btn-primary-custom {
            background-color: #3270FC;
            color: #fff;
            box-shadow: 0 4px 12px rgba(50, 112, 252, 0.25);
        }
        .btn-primary-custom:hover {
            background-color: #1c5bca;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(50, 112, 252, 0.35);
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
            transform: translateY(-2px);
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
        
        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); opacity: 1; }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); }
        }

        /* Mobile Adjustments */
        @media (max-width: 500px) {
            .success-card {
                padding: 30px 20px;
                border-radius: 0;
                box-shadow: none;
                background: transparent;
            }
            .success-page-wrap {
                background: #fff;
                align-items: flex-start;
            }
            .action-buttons-grid {
                grid-template-columns: 1fr; /* Stack buttons on mobile */
            }
            .action-buttons-grid .btn-full-width {
                grid-column: span 1;
            }
        }
    </style>
@endpush

@section('content')
<div class="success-page-wrap">
    <div class="container">
        <div class="success-card">
            <!-- Emoticon Image -->
            <div class="success-image-wrap">
                <!-- Using a party popper or success illustration -->
                <img src="https://cdn-icons-png.flaticon.com/512/7518/7518748.png" alt="Success">
            </div>
            
            <h1>Đăng tin thành công!</h1>
            
            <div class="success-description">
                Tin của bạn đang chờ duyệt.<br>
                Chúng tôi sẽ thông báo sau khi hiển thị.
            </div>
            
            <div class="action-buttons-grid">
                <!-- Row 1: 2 Buttons -->
                @if(isset($slug) && $slug)
                    <a href="{{ route('bds.show', $slug) }}" class="btn-action btn-primary-custom">
                        <i class="fa-solid fa-eye mr-2"></i> Xem tin vừa đăng
                    </a>
                @else
                    <!-- Fallback if no slug (should rarely happen) -->
                     <a href="{{ route('webapp.listings') }}" class="btn-action btn-primary-custom">
                        <i class="fa-solid fa-list-check mr-2"></i> Danh sách tin
                    </a>
                @endif
                
                <a href="{{ route('webapp.add_listing') }}" class="btn-action btn-secondary-custom">
                    <i class="fa-solid fa-plus mr-2"></i> Đăng tin khác
                </a>
                
                <!-- Row 2: 1 Button (Full Width) -->
                <a href="{{ route('webapp.listings') }}" class="btn-action btn-ghost-custom btn-full-width">
                    <i class="fa-solid fa-list-check mr-2"></i> Quản lý tin đăng
                </a>
            </div>

            <!-- Removed "Back to Home" link as requested -->
        </div>
    </div>
</div>
@endsection
