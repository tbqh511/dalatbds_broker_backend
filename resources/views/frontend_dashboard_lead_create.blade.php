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
                            @php
                                $oldMin = (float) old('price_min', 0);
                                $oldMax = (float) old('price_max', 0);
                                $tyMin = $oldMin > 0 ? rtrim(rtrim(number_format($oldMin / 1000000000, 3, '.', ''), '0'), '.') : '';
                                $tyMax = ($oldMax > 0 && $oldMax < 999999999999) ? rtrim(rtrim(number_format($oldMax / 1000000000, 3, '.', ''), '0'), '.') : '';
                            @endphp
                            <div class="col-md-12">
                                <label>Ngân sách <span class="dec-icon"><i class="far fa-money-bill-wave"></i></span></label>
                                <div class="listsearch-input-item">
                                    <select id="budget-range-select" class="chosen-select no-search-select">
                                        <option value="">Thỏa thuận / Tùy chỉnh</option>
                                        <option value="0:1000000000:Dưới 1 tỷ" {{ old('budget_label') === 'Dưới 1 tỷ' ? 'selected' : '' }}>Dưới 1 tỷ</option>
                                        <option value="1000000000:3000000000:1 - 3 tỷ" {{ old('budget_label') === '1 - 3 tỷ' ? 'selected' : '' }}>1 - 3 tỷ</option>
                                        <option value="3000000000:5000000000:3 - 5 tỷ" {{ old('budget_label') === '3 - 5 tỷ' ? 'selected' : '' }}>3 - 5 tỷ</option>
                                        <option value="5000000000:10000000000:5 - 10 tỷ" {{ old('budget_label') === '5 - 10 tỷ' ? 'selected' : '' }}>5 - 10 tỷ</option>
                                        <option value="10000000000:20000000000:10 - 20 tỷ" {{ old('budget_label') === '10 - 20 tỷ' ? 'selected' : '' }}>10 - 20 tỷ</option>
                                        <option value="20000000000:50000000000:20 - 50 tỷ" {{ old('budget_label') === '20 - 50 tỷ' ? 'selected' : '' }}>20 - 50 tỷ</option>
                                        <option value="50000000000:999999999999:Trên 50 tỷ" {{ old('budget_label') === 'Trên 50 tỷ' ? 'selected' : '' }}>Trên 50 tỷ</option>
                                    </select>
                                </div>
                                {{-- Nhập khoảng chính xác (tỷ) — đồng bộ với preset --}}
                                <div class="row" style="margin-top:8px;">
                                    <div class="col-md-6">
                                        <label style="font-weight:400;font-size:13px;">Từ (tỷ)</label>
                                        <input type="number" id="budget-ty-min" min="0" step="0.1" placeholder="0"
                                            value="{{ $tyMin }}" oninput="onBudgetManualInput()">
                                    </div>
                                    <div class="col-md-6">
                                        <label style="font-weight:400;font-size:13px;">Đến (tỷ) <span style="color:#aaa;">(tuỳ chọn)</span></label>
                                        <input type="number" id="budget-ty-max" min="0" step="0.1" placeholder="—"
                                            value="{{ $tyMax }}" oninput="onBudgetManualInput()">
                                    </div>
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
        var UNCAPPED = 999999999999;
        function vndToTy(vnd) {
            var v = Number(vnd) || 0;
            if (v <= 0) return '';
            return parseFloat((v / 1e9).toFixed(3));
        }
        function tyToVnd(str) {
            if (str === '' || str == null) return 0;
            var n = parseFloat(String(str).replace(',', '.'));
            return (isFinite(n) && n > 0) ? Math.round(n * 1e9) : 0;
        }
        // Chọn preset -> set hidden + fill ô Từ/Đến + giữ label
        function applyBudgetRange(val) {
            var parts = val ? val.split(':') : [];
            var min = Number(parts[0]) || 0;
            var max = Number(parts[1]) || 0;
            document.getElementById('price-min-hidden').value = min;
            document.getElementById('price-max-hidden').value = max;
            document.getElementById('budget-label-hidden').value = parts.slice(2).join(':') || '';
            var minTy = document.getElementById('budget-ty-min');
            var maxTy = document.getElementById('budget-ty-max');
            if (minTy) minTy.value = min > 0 ? vndToTy(min) : '';
            if (maxTy) maxTy.value = (max > 0 && max < UNCAPPED) ? vndToTy(max) : '';
        }
        // Gõ tay -> set hidden, xóa label + bỏ chọn dropdown
        function onBudgetManualInput() {
            var minTy = document.getElementById('budget-ty-min');
            var maxTy = document.getElementById('budget-ty-max');
            document.getElementById('price-min-hidden').value = minTy ? tyToVnd(minTy.value) : 0;
            document.getElementById('price-max-hidden').value = maxTy ? tyToVnd(maxTy.value) : 0;
            document.getElementById('budget-label-hidden').value = '';
            var sel = document.getElementById('budget-range-select');
            if (sel) { sel.value = ''; if (window.jQuery && jQuery(sel).trigger) jQuery(sel).trigger('chosen:updated'); }
        }
        document.addEventListener('DOMContentLoaded', function () {
            var sel = document.getElementById('budget-range-select');
            if (sel) {
                sel.addEventListener('change', function () { applyBudgetRange(this.value); });
                // Chỉ áp preset khi đang chọn 1 preset; nếu trống thì giữ giá trị nhập tay (old input).
                if (sel.value) applyBudgetRange(sel.value);
            }
        });
    </script>
@endpush
