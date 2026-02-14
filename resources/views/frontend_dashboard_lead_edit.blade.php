@extends('frontends.master')

@section('hide_newsletter')@endsection
@section('hide_footer')@endsection
@section('hide_secondary_nav')@endsection

@section('content')
<div class="content">
    <div class="dashbard-menu-overlay"></div>
    @include('components.dashboard.sidebar')

    <div class="dashboard-content">
        @include('components.dashboard.mobile_btn')
        <div class="container dasboard-container">
            @include('components.dashboard.header', ['title' => 'Chỉnh sửa Lead'])
            
            <div class="dasboard-wrapper fl-wrap">
                <div class="dasboard-widget-title fl-wrap">
                    <h5><i class="fal fa-edit"></i>Chỉnh sửa Lead</h5>
                </div>
                <div class="dasboard-widget-box fl-wrap">
                    <form action="{{ route('webapp.leads.update', $lead->id) }}" method="POST" class="custom-form">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <label>Tên khách hàng <span class="dec-icon"><i class="far fa-user"></i></span></label>
                                <input type="text" name="name" value="{{ $lead->customer ? $lead->customer->full_name : '' }}" required/>
                            </div>
                            <div class="col-md-6">
                                <label>Số điện thoại <span class="dec-icon"><i class="far fa-phone"></i></span></label>
                                <input type="text" name="phone" value="{{ $lead->customer ? $lead->customer->contact : '' }}" required/>
                            </div>
                            <div class="col-md-6">
                                <label>Loại nhu cầu</label>
                                <div class="listsearch-input-item">
                                    <select name="lead_type" class="chosen-select no-search-select">
                                        <option value="buy" {{ $lead->lead_type == 'buy' ? 'selected' : '' }}>Mua</option>
                                        <option value="rent" {{ $lead->lead_type == 'rent' ? 'selected' : '' }}>Thuê</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Trạng thái</label>
                                <div class="listsearch-input-item">
                                    <select name="status" class="chosen-select no-search-select">
                                        <option value="new" {{ $lead->status == 'New' ? 'selected' : '' }}>Mới</option>
                                        <option value="contacted" {{ $lead->status == 'Contacted' ? 'selected' : '' }}>Đã liên hệ</option>
                                        <option value="converted" {{ $lead->status == 'Converted' ? 'selected' : '' }}>Đã chuyển đổi</option>
                                        <option value="lost" {{ $lead->status == 'Lost' ? 'selected' : '' }}>Thất bại</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Ngân sách từ (VNĐ) <span class="dec-icon"><i class="far fa-money-bill-wave"></i></span></label>
                                <input type="number" name="price_min" value="{{ $lead->demand_rate_min }}"/>
                            </div>
                            <div class="col-md-6">
                                <label>Ngân sách đến (VNĐ) <span class="dec-icon"><i class="far fa-money-bill-wave"></i></span></label>
                                <input type="number" name="price_max" value="{{ $lead->demand_rate_max }}"/>
                            </div>
                            <div class="col-md-12">
                                <label>Ghi chú</label>
                                <textarea name="note" cols="40" rows="3">{{ $lead->source_note }}</textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn float-btn color-bg">Cập nhật Lead</button>
                    </form>
                </div>
            </div>
        </div>
        @include('components.dashboard.footer')
    </div>
    <div class="dashbard-bg gray-bg"></div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/webapp.css') }}">
@endpush
@push('head_scripts')
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
@endpush
@push('scripts')
    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script>
        if (window.Telegram && window.Telegram.WebApp) {
            const tg = window.Telegram.WebApp;
            tg.expand();
        }
    </script>
@endpush
