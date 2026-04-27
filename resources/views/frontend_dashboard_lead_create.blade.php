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
                            <div class="col-md-12">
                                <label>Ngân sách <span class="dec-icon"><i class="far fa-money-bill-wave"></i></span></label>
                                <div class="listsearch-input-item">
                                    <select id="budget-range-select" class="chosen-select no-search-select">
                                        <option value="">Thỏa thuận</option>
                                        <option value="0:1000000000:Dưới 1 tỷ" {{ old('budget_label') === 'Dưới 1 tỷ' ? 'selected' : '' }}>Dưới 1 tỷ</option>
                                        <option value="1000000000:3000000000:1 - 3 tỷ" {{ old('budget_label') === '1 - 3 tỷ' ? 'selected' : '' }}>1 - 3 tỷ</option>
                                        <option value="3000000000:5000000000:3 - 5 tỷ" {{ old('budget_label') === '3 - 5 tỷ' ? 'selected' : '' }}>3 - 5 tỷ</option>
                                        <option value="5000000000:10000000000:5 - 10 tỷ" {{ old('budget_label') === '5 - 10 tỷ' ? 'selected' : '' }}>5 - 10 tỷ</option>
                                        <option value="10000000000:20000000000:10 - 20 tỷ" {{ old('budget_label') === '10 - 20 tỷ' ? 'selected' : '' }}>10 - 20 tỷ</option>
                                        <option value="20000000000:50000000000:20 - 50 tỷ" {{ old('budget_label') === '20 - 50 tỷ' ? 'selected' : '' }}>20 - 50 tỷ</option>
                                        <option value="50000000000:999999999999:Trên 50 tỷ" {{ old('budget_label') === 'Trên 50 tỷ' ? 'selected' : '' }}>Trên 50 tỷ</option>
                                    </select>
                                </div>
                                <input type="hidden" name="price_min" id="price-min-hidden" value="{{ old('price_min', 0) }}">
                                <input type="hidden" name="price_max" id="price-max-hidden" value="{{ old('price_max', 0) }}">
                                <input type="hidden" name="budget_label" id="budget-label-hidden" value="{{ old('budget_label', '') }}">
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
        document.addEventListener('DOMContentLoaded', function () {
            var sel = document.getElementById('budget-range-select');
            if (!sel) return;
            function applyBudgetRange(val) {
                var parts = val ? val.split(':') : [];
                document.getElementById('price-min-hidden').value = parts[0] || 0;
                document.getElementById('price-max-hidden').value = parts[1] || 0;
                document.getElementById('budget-label-hidden').value = parts.slice(2).join(':') || '';
            }
            sel.addEventListener('change', function () { applyBudgetRange(this.value); });
            applyBudgetRange(sel.value);
        });
    </script>
@endpush
