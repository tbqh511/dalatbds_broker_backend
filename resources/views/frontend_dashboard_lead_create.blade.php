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
            @include('components.dashboard.header', ['title' => 'Thêm mới Lead'])
            
            <div class="dasboard-wrapper fl-wrap">
                <div class="dasboard-widget-title fl-wrap">
                    <h5><i class="fal fa-user-plus"></i>Thông tin Lead</h5>
                </div>
                <div class="dasboard-widget-box fl-wrap">
                    <form action="{{ route('webapp.leads.store') }}" method="POST" class="custom-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label>Tên khách hàng <span class="dec-icon"><i class="far fa-user"></i></span></label>
                                <input type="text" name="name" placeholder="Nhập tên khách hàng" value="{{ old('name') }}" required/>
                            </div>
                            <div class="col-md-6">
                                <label>Số điện thoại <span class="dec-icon"><i class="far fa-phone"></i></span></label>
                                <input type="text" name="phone" placeholder="Nhập số điện thoại" value="{{ old('phone') }}" required/>
                            </div>
                            <div class="col-md-6">
                                <label>Loại nhu cầu</label>
                                <div class="listsearch-input-item">
                                    <select name="lead_type" class="chosen-select no-search-select">
                                        <option value="buy" {{ old('lead_type') == 'buy' ? 'selected' : '' }}>Mua</option>
                                        <option value="rent" {{ old('lead_type') == 'rent' ? 'selected' : '' }}>Thuê</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Trạng thái</label>
                                <div class="listsearch-input-item">
                                    <select name="status" class="chosen-select no-search-select">
                                        <option value="new">Mới</option>
                                        <option value="contacted">Đã liên hệ</option>
                                        <option value="converted">Đã chuyển đổi</option>
                                        <option value="lost">Thất bại</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Ngân sách từ (VNĐ) <span class="dec-icon"><i class="far fa-money-bill-wave"></i></span></label>
                                <input type="number" name="price_min" placeholder="0" value="{{ old('price_min') }}"/>
                            </div>
                            <div class="col-md-6">
                                <label>Ngân sách đến (VNĐ) <span class="dec-icon"><i class="far fa-money-bill-wave"></i></span></label>
                                <input type="number" name="price_max" placeholder="0" value="{{ old('price_max') }}"/>
                            </div>
                            <div class="col-md-12">
                                <label>Ghi chú</label>
                                <textarea name="note" cols="40" rows="3" placeholder="Ghi chú thêm...">{{ old('note') }}</textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn float-btn color-bg">Lưu Lead</button>
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
