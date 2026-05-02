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
<div x-data="assignLeadForm()" x-cloak class="flex items-start justify-center min-h-screen w-full">
    <div class="w-full max-w-md bg-white shadow-2xl relative flex flex-col overflow-hidden">

        <!-- HEADER -->
        <div class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-gray-100 px-4 py-4 shadow-sm">
            <div class="flex items-center justify-between">
                <h1 class="text-lg font-bold text-gray-800">Phân công Sale</h1>
            </div>
            <div class="h-1 w-full bg-primary rounded-full mt-3 transition-all duration-300"></div>
        </div>

        <div class="px-4 py-4 pb-32">

            <!-- LEAD SUMMARY CARD -->
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-5">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Thông tin Lead #{{ $lead->id }}</p>

                <!-- Tên khách -->
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                        <i class="fas fa-user text-primary text-xs"></i>
                    </div>
                    <p class="font-bold text-gray-800 text-base leading-tight">{{ $lead->customer?->full_name ?? 'Khách vãng lai' }}</p>
                </div>

                <!-- Nút Gọi điện -->
                @if($lead->customer?->contact)
                <a href="tel:{{ $lead->customer->contact }}"
                   class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 border border-green-200
                          px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-green-100 active:scale-95 transition-all mb-3">
                    <i class="fas fa-phone text-xs"></i> Gọi điện
                </a>
                @endif

                <!-- Divider -->
                <div class="border-t border-blue-100 my-3"></div>

                <!-- Thông tin chi tiết -->
                <div class="space-y-2">
                    <!-- Nhu cầu + Ngân sách -->
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full
                            {{ ($lead->getRawOriginal('lead_type') ?? '') === 'buy' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                            <i class="fas {{ ($lead->getRawOriginal('lead_type') ?? '') === 'buy' ? 'fa-home' : 'fa-key' }} text-[10px]"></i>
                            {{ ($lead->getRawOriginal('lead_type') ?? '') === 'buy' ? 'Cần mua' : 'Cần thuê' }}
                        </span>
                        <span class="inline-flex items-center gap-1 text-xs bg-primary/10 text-primary px-2.5 py-1 rounded-full font-medium">
                            <i class="fas fa-wallet text-[10px]"></i>
                            {{ format_vnd($lead->demand_rate_min) }} – {{ format_vnd($lead->demand_rate_max) }}
                        </span>
                    </div>

                    <!-- Loại BĐS -->
                    @if(!empty($categoryNames))
                    <div class="flex items-start gap-2 text-sm text-gray-600">
                        <i class="fas fa-building text-primary/50 mt-0.5 w-4 text-center shrink-0 text-xs"></i>
                        <span>{{ implode(', ', $categoryNames) }}</span>
                    </div>
                    @endif

                    <!-- Khu vực -->
                    @if(!empty($wardNames))
                    <div class="flex items-start gap-2 text-sm text-gray-600">
                        <i class="fas fa-map-marker-alt text-primary/50 mt-0.5 w-4 text-center shrink-0 text-xs"></i>
                        <span>{{ implode(', ', $wardNames) }}</span>
                    </div>
                    @endif

                    <!-- Mục đích -->
                    @if($lead->purpose && count((array) $lead->purpose) > 0)
                    <div class="flex items-start gap-2 text-sm text-gray-600">
                        <i class="fas fa-bullseye text-primary/50 mt-0.5 w-4 text-center shrink-0 text-xs"></i>
                        <span>{{ is_array($lead->purpose) ? implode(', ', $lead->purpose) : $lead->purpose }}</span>
                    </div>
                    @endif

                    <!-- Ghi chú -->
                    @if($lead->note)
                    <div class="flex items-start gap-2 text-sm text-gray-600">
                        <i class="fas fa-sticky-note text-primary/50 mt-0.5 w-4 text-center shrink-0 text-xs"></i>
                        <span class="italic">{{ $lead->note }}</span>
                    </div>
                    @endif
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
                    <i class="fas fa-users text-3xl mb-2 block"></i>
                    <p class="text-sm">Chưa có nhân viên sale nào</p>
                </div>
                @else
                <div class="space-y-2">
                    @foreach($salesList as $sale)
                    <div @click="selectSale({{ $sale->id }}, '{{ addslashes($sale->name) }}')"
                        :class="selectedSaleId === {{ $sale->id }}
                            ? 'border-primary bg-blue-50 shadow-sm scale-[1.01]'
                            : 'border-gray-200 bg-white hover:border-primary/40 hover:bg-blue-50/40'"
                        class="flex items-center justify-between p-4 border-2 rounded-xl cursor-pointer transition-all duration-150">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <!-- Avatar -->
                            <div :class="selectedSaleId === {{ $sale->id }} ? 'bg-primary text-white' : 'bg-gray-100 text-gray-500'"
                                class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 transition-colors duration-150">
                                <i class="fas fa-user text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-800 truncate text-sm">{{ $sale->name }}</p>
                                <span class="text-xs px-2 py-0.5 rounded-full font-medium mt-0.5 inline-block
                                    {{ $sale->role === 'sale_admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $sale->role === 'sale_admin' ? 'Sale Admin' : 'Sale' }}
                                </span>
                            </div>
                        </div>
                        <!-- Radio circle -->
                        <div :class="selectedSaleId === {{ $sale->id }}
                                ? 'bg-primary border-primary'
                                : 'bg-white border-gray-300'"
                            class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all ml-3 shrink-0">
                            <i x-show="selectedSaleId === {{ $sale->id }}"
                               class="fas fa-check text-white text-[10px]"></i>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

        </div>

        <!-- STICKY FOOTER BUTTON -->
        <div x-show="!success && !alreadyAssigned"
            class="fixed bottom-0 left-0 right-0 max-w-md mx-auto px-4 py-4 bg-white/95 backdrop-blur-md border-t border-gray-100">
            <button @click="submitAssign"
                :disabled="!selectedSaleId || loading"
                :class="selectedSaleId && !loading
                    ? 'bg-primary hover:bg-blue-600 text-white shadow-lg shadow-blue-200 active:scale-[0.98]'
                    : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
                class="w-full py-4 rounded-xl font-bold text-base transition-all duration-150 flex items-center justify-center gap-2">
                <template x-if="loading">
                    <span><i class="fas fa-spinner fa-spin mr-1"></i> Đang xử lý...</span>
                </template>
                <template x-if="!loading">
                    <span>
                        <i :class="selectedSaleId ? 'fas fa-user-check' : 'fas fa-user'" class="mr-2"></i>
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
            window.Telegram.WebApp.setHeaderColor('#3270FC');
            window.Telegram.WebApp.setBackgroundColor('#ffffff');
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
