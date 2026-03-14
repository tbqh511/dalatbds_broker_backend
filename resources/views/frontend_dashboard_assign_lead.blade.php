@extends('frontends.master')

@section('hide_newsletter')@endsection
@section('hide_footer')@endsection
@section('hide_secondary_nav')@endsection

@section('title', 'Phân công Sale - Đà Lạt BDS')

@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/dashboard-style.css') }}">
<link rel="stylesheet" href="{{ asset('css/webapp.css') }}">
<style>
    body {
        background-color: #F5F7FB;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }
    [x-cloak] { display: none !important; }
</style>
@endpush

@push('head_scripts')
<script src="https://telegram.org/js/telegram-web-app.js"></script>
@vite(['resources/css/app.css', 'resources/js/app.js'])
@endpush

@section('content')
<div x-data="assignLeadForm()" x-cloak class="flex items-start justify-center min-h-screen w-full py-2">
    <div class="w-full max-w-md bg-white shadow-2xl relative flex flex-col rounded-xl overflow-hidden">

        <!-- HEADER -->
        <div class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-gray-100 px-6 py-5 shadow-sm">
            <h1 class="text-xl font-bold text-gray-800">Phân công Sale</h1>
            <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden mt-3">
                <div class="h-full bg-green-500 w-full transition-all duration-300"></div>
            </div>
        </div>

        <div class="px-6 py-4 pb-28">

            <!-- LEAD SUMMARY CARD -->
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-5">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Thông tin Lead #{{ $lead->id }}</p>
                <p class="font-bold text-gray-800 text-base">{{ $lead->customer?->full_name ?? 'Khách vãng lai' }}</p>
                @if($lead->customer?->contact)
                <p class="text-sm text-gray-500 mt-0.5">{{ $lead->customer->contact }}</p>
                @endif
                <div class="flex gap-2 mt-2 flex-wrap">
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full
                        {{ ($lead->getRawOriginal('lead_type') ?? '') === 'buy' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                        {{ ($lead->getRawOriginal('lead_type') ?? '') === 'buy' ? 'Cần mua' : 'Cần thuê' }}
                    </span>
                    <span class="text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full">
                        {{ format_vnd($lead->demand_rate_min) }} – {{ format_vnd($lead->demand_rate_max) }}
                    </span>
                </div>
            </div>

            <!-- ALREADY ASSIGNED STATE -->
            <div x-show="alreadyAssigned" x-cloak class="text-center py-10">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check-circle text-green-500 text-3xl"></i>
                </div>
                <p class="font-bold text-gray-800 text-lg">Đã phân công rồi</p>
                <p class="text-gray-500 mt-1 text-sm">
                    Lead đã được gán cho<br>
                    <span class="font-semibold text-green-600 text-base" x-text="assignedSaleName"></span>
                </p>
            </div>

            <!-- SUCCESS STATE -->
            <div x-show="success" x-cloak class="text-center py-10">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-check text-green-500 text-3xl"></i>
                </div>
                <p class="font-bold text-gray-800 text-lg">Phân công thành công!</p>
                <p class="text-gray-500 mt-1 text-sm">
                    Đã gán lead cho<br>
                    <span class="font-semibold text-green-600 text-base" x-text="selectedSaleName"></span>
                </p>
                <p class="text-xs text-gray-400 mt-4">Bạn có thể đóng cửa sổ này.</p>
            </div>

            <!-- SALES LIST -->
            <div x-show="!success && !alreadyAssigned">
                <p class="text-sm font-bold text-gray-700 mb-3">Chọn nhân viên phụ trách:</p>

                @if($salesList->isEmpty())
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-users text-3xl mb-2"></i>
                    <p class="text-sm">Chưa có nhân viên sale nào</p>
                </div>
                @else
                <div class="space-y-2">
                    @foreach($salesList as $sale)
                    <div @click="selectSale({{ $sale->id }}, '{{ addslashes($sale->name) }}')"
                        :class="selectedSaleId === {{ $sale->id }}
                            ? 'border-green-500 bg-green-50 shadow-sm scale-[1.01]'
                            : 'border-gray-200 bg-white hover:border-green-300 hover:bg-green-50/40'"
                        class="flex items-center justify-between p-4 border-2 rounded-xl cursor-pointer transition-all duration-150">
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 truncate">{{ $sale->name }}</p>
                            @if($sale->mobile)
                            <p class="text-xs text-gray-400 mt-0.5">{{ $sale->mobile }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 ml-3 shrink-0">
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                {{ $sale->role === 'sale_admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ $sale->role === 'sale_admin' ? 'Sale Admin' : 'Sale' }}
                            </span>
                            <div :class="selectedSaleId === {{ $sale->id }}
                                    ? 'bg-green-500 border-green-500'
                                    : 'bg-white border-gray-300'"
                                class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all">
                                <i x-show="selectedSaleId === {{ $sale->id }}"
                                   class="fas fa-check text-white text-xs"></i>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

        </div>

        <!-- STICKY FOOTER BUTTON -->
        <div x-show="!success && !alreadyAssigned"
            class="fixed bottom-0 left-0 right-0 max-w-md mx-auto px-6 py-4 bg-white/95 backdrop-blur-md border-t border-gray-100">
            <button @click="submitAssign"
                :disabled="!selectedSaleId || loading"
                :class="selectedSaleId && !loading
                    ? 'bg-green-500 hover:bg-green-600 text-white shadow-lg shadow-green-200 active:scale-[0.98]'
                    : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
                class="w-full py-4 rounded-xl font-bold text-base transition-all duration-150 flex items-center justify-center gap-2">
                <template x-if="loading">
                    <span><i class="fas fa-spinner fa-spin mr-1"></i> Đang xử lý...</span>
                </template>
                <template x-if="!loading">
                    <span>
                        <i class="fas fa-user-check mr-2"></i>
                        <span x-text="selectedSaleId ? 'Xác nhận phân công' : 'Chọn một nhân viên'"></span>
                    </span>
                </template>
            </button>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js" defer></script>
<script>
    // Init Telegram WebApp
    if (window.Telegram && window.Telegram.WebApp) {
        window.Telegram.WebApp.expand();
        try {
            window.Telegram.WebApp.setHeaderColor('#22c55e');
            window.Telegram.WebApp.setBackgroundColor('#F5F7FB');
        } catch (e) {}
    }

    function assignLeadForm() {
        return {
            selectedSaleId: null,
            selectedSaleName: '',
            loading: false,
            success: false,
            alreadyAssigned: {{ $lead->sale_id ? 'true' : 'false' }},
            assignedSaleName: '{{ addslashes($lead->sale?->name ?? '') }}',

            selectSale(id, name) {
                this.selectedSaleId = id;
                this.selectedSaleName = name;
            },

            async submitAssign() {
                if (!this.selectedSaleId || this.loading) return;
                this.loading = true;

                try {
                    const response = await axios.post(
                        '{{ $postUrl }}',
                        { sale_id: this.selectedSaleId },
                        { headers: { 'X-Requested-With': 'XMLHttpRequest' } }
                    );

                    if (response.data.success) {
                        this.success = true;
                        setTimeout(() => {
                            if (window.Telegram && window.Telegram.WebApp && window.Telegram.WebApp.close) {
                                window.Telegram.WebApp.close();
                            } else {
                                window.close();
                            }
                        }, 2000);
                    }
                } catch (err) {
                    const data = err.response?.data;
                    if (data?.already_assigned) {
                        this.alreadyAssigned = true;
                        this.assignedSaleName = data.sale_name;
                    } else {
                        alert('Có lỗi xảy ra. Vui lòng thử lại.');
                    }
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>
@endpush
