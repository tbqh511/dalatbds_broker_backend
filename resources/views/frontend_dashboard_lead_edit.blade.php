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
                            @php
                                $budgetRanges = [
                                    [0, 0, 'Thỏa thuận'],
                                    [0, 1000000000, 'Dưới 1 tỷ'],
                                    [1000000000, 3000000000, '1 - 3 tỷ'],
                                    [3000000000, 5000000000, '3 - 5 tỷ'],
                                    [5000000000, 10000000000, '5 - 10 tỷ'],
                                    [10000000000, 20000000000, '10 - 20 tỷ'],
                                    [20000000000, 50000000000, '20 - 50 tỷ'],
                                    [50000000000, 999999999999, 'Trên 50 tỷ'],
                                ];
                                $leadMin = (float)($lead->demand_rate_min ?? 0);
                                $leadMax = (float)($lead->demand_rate_max ?? 0);
                                $selectedBudgetVal = '';
                                foreach ($budgetRanges as [$rMin, $rMax, $rLabel]) {
                                    if ($leadMin == $rMin && $leadMax == $rMax) {
                                        $selectedBudgetVal = $rMin . ':' . $rMax . ':' . $rLabel;
                                        break;
                                    }
                                }
                            @endphp
                            <div class="col-md-12">
                                <label>Ngân sách <span class="dec-icon"><i class="far fa-money-bill-wave"></i></span></label>
                                <div class="listsearch-input-item">
                                    <select id="budget-range-select" class="chosen-select no-search-select">
                                        <option value="" {{ !$selectedBudgetVal ? 'selected' : '' }}>Thỏa thuận</option>
                                        @foreach ([
                                            ['0', '1000000000', 'Dưới 1 tỷ'],
                                            ['1000000000', '3000000000', '1 - 3 tỷ'],
                                            ['3000000000', '5000000000', '3 - 5 tỷ'],
                                            ['5000000000', '10000000000', '5 - 10 tỷ'],
                                            ['10000000000', '20000000000', '10 - 20 tỷ'],
                                            ['20000000000', '50000000000', '20 - 50 tỷ'],
                                            ['50000000000', '999999999999', 'Trên 50 tỷ'],
                                        ] as [$oMin, $oMax, $oLabel])
                                            @php $oVal = $oMin . ':' . $oMax . ':' . $oLabel; @endphp
                                            <option value="{{ $oVal }}" {{ $selectedBudgetVal === $oVal ? 'selected' : '' }}>{{ $oLabel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="hidden" name="price_min" id="price-min-hidden"
                                    value="{{ number_format((float)($lead->demand_rate_min ?? 0), 0, '.', '') }}">
                                <input type="hidden" name="price_max" id="price-max-hidden"
                                    value="{{ number_format((float)($lead->demand_rate_max ?? 0), 0, '.', '') }}">
                                <input type="hidden" name="budget_label" id="budget-label-hidden"
                                    value="{{ $lead->budget_label ?? '' }}">
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
        });
    </script>
@endpush
