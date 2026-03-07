@extends('frontends.master')

@section('hide_newsletter')@endsection
@section('hide_footer')@endsection

@section('title', 'Thêm khách hàng - Đà Lạt BDS')

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

    ::-webkit-scrollbar {
        width: 0px;
        background: transparent;
    }

    [x-cloak] {
        display: none !important;
    }

    .input-field {
        width: 100%;
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid #E5E7EB;
        outline: none;
        transition: all 0.2s;
        background-color: white;
    }

    .input-field:focus {
        border-color: #22c55e;
        ring: 2px;
        ring-color: #bbf7d0;
    }
</style>
@endpush

@push('head_scripts')
@if (app()->environment('local'))
@vite(['resources/css/app.css', 'resources/js/app.js'])
@else
@vite(['resources/css/app.css', 'resources/js/app.js'])
@endif
@endpush

@section('content')
<div x-data="addCustomerForm()" x-cloak class="flex items-start justify-center min-h-screen w-full py-2">
    <div x-ref="formContainer"
        class="w-full max-w-md bg-white shadow-2xl relative flex flex-col pb-24 rounded-xl overflow-hidden h-auto max-h-[90vh] overflow-y-auto">

        <!-- HEADER -->
        <div class="sticky top-0 z-49 bg-white/95 backdrop-blur-md border-b border-gray-100 px-6 py-5 shadow-sm">
            <div class="flex justify-between items-center mb-3">
                <div class="flex items-center gap-3">
                    <a href="{{ route('webapp.listings') }}" class="text-gray-600 hover:text-primary transition-colors">
                        <i class="fas fa-arrow-left text-lg"></i>
                    </a>
                    <h1 class="text-xl font-bold text-gray-800">Thông tin khách hàng</h1>
                </div>
            </div>
            <!-- Progress bar styled line for consistency -->
            <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-primary w-full shadow-sm"></div>
            </div>
        </div>

        <!-- SCROLLABLE CONTENT -->
        <form @submit.prevent="submitForm" class="flex-1 px-6 py-6 pb-40">

            <!-- ===================== CONTACT INFO ===================== -->
            <div class="mb-6" x-data="{
                isEditing: true,
                get isHasData() {
                    return $data.form.phone && $data.isPhoneValid && $data.form.name && $data.isNameValid;
                }
            }" @click.outside="if(isHasData) { isEditing = false; }">
                <h3
                    class="text-xs font-bold text-gray-500 mb-3 uppercase tracking-wide flex items-center justify-center border-2 border-dashed border-primary/30 rounded-xl p-2 bg-blue-50/30">
                    <i class="fa-solid fa-user-tag mr-2 text-primary"></i> Nhập thông tin liên hệ
                </h3>

                <!-- VIEW MODE: Thẻ tóm tắt (chỉ hiện khi không edit và đã có data) -->
                <div x-show="!isEditing && isHasData" @click="isEditing = true"
                    class="py-2 px-2 bg-blue-50 rounded-lg border border-blue-100 cursor-pointer hover:bg-blue-100 transition shadow-sm animate-fade-in-up flex flex-col items-center justify-center">
                    <p class="text-lg font-bold text-primary text-center">
                        <span x-text="form.name"></span>
                        <span> - </span>
                        <span class="text-green-600">*******<span
                                x-text="form.phone ? form.phone.slice(-3) : ''"></span></span>
                    </p>
                </div>

                <!-- EDIT MODE: Form nhập liệu -->
                <div x-show="isEditing || !isHasData" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="space-y-3 mt-4">
                        <!-- SĐT Input (ĐẶT LÊN TRƯỚC) -->
                        <div class="relative group">
                            <input type="tel" x-model="form.phone"
                                @focus="$el.scrollIntoView({ behavior: 'smooth', block: 'center' })"
                                placeholder="Số điện thoại (Nhập để bắt đầu)"
                                :class="{'!border-red-500 !bg-red-50 focus:!border-red-500': form.phone && !isPhoneValid}"
                                class="input-field border-green-200 focus:border-green-500 focus:ring-green-200 bg-green-50/30">
                            <p x-show="form.phone && !isPhoneValid"
                                class="text-xs text-red-500 mt-1 text-left ml-1">
                                <i class="fa-solid fa-circle-exclamation mr-1"></i> Số điện thoại không đúng định dạng (VN)
                            </p>
                            <div class="relative text-green-600 font-medium flex items-center opacity-100 transition-opacity text-xs mt-1 ml-1"
                                x-show="form.phone && isPhoneValid">
                                <i class="fa-solid fa-shield-halved mr-1"></i> Thông tin này được bảo mật.
                            </div>
                        </div>

                        <!-- Tên liên hệ Input (CHỈ HIỆN SAU KHI SĐT HỢP LỆ) -->
                        <div class="relative group" x-show="form.phone && isPhoneValid"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0">
                            <input type="text" x-model="form.name"
                                @focus="$el.scrollIntoView({ behavior: 'smooth', block: 'center' })"
                                placeholder="Tên liên hệ"
                                :class="{'!border-red-500 !bg-red-50 focus:!border-red-500': form.name && !isNameValid}"
                                class="input-field border-green-200 focus:border-green-500 focus:ring-green-200 bg-green-50/30">
                            <p x-show="form.name && !isNameValid"
                                class="text-xs text-red-500 mt-1 text-left ml-1">
                                <i class="fa-solid fa-circle-exclamation mr-1"></i> Tên phải có ít nhất 2 ký tự và không chứa ký tự đặc biệt
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===================== NEED TYPE (Cần mua / Cần thuê) ===================== -->
            <div class="mb-6"
                x-show="form.phone && isPhoneValid && form.name && isNameValid"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0">
                <label class="block text-sm font-bold text-gray-800 mb-3">Chọn khách có nhu cầu</label>
                <div class="grid grid-cols-2 gap-3 p-1 bg-gray-100 rounded-xl">
                    <button type="button" @click="form.lead_type = 'buy'"
                        :class="form.lead_type === 'buy' ? 'bg-primary text-white shadow-md' : 'text-gray-500 hover:text-primary'"
                        class="py-3 px-4 rounded-lg text-sm font-bold transition-all flex items-center justify-center">
                        <i class="fas fa-dollar-sign mr-2"></i> Cần mua
                    </button>
                    <button type="button" @click="form.lead_type = 'rent'"
                        :class="form.lead_type === 'rent' ? 'bg-primary text-white shadow-md' : 'text-gray-500 hover:text-primary'"
                        class="py-3 px-4 rounded-lg text-sm font-bold transition-all flex items-center justify-center">
                        <i class="fas fa-tag mr-2"></i> Cần thuê
                    </button>
                </div>
            </div>

            <!-- ===================== PROPERTY TYPES — Collapsible ===================== -->
            <div class="mb-6"
                x-show="form.lead_type"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0">
                <label class="block text-sm font-bold text-gray-800 mb-3 flex justify-between items-center">
                    Chọn loại BĐS
                    <button type="button" x-show="!isCategoryExpanded && form.categories.length > 0"
                        @click="isCategoryExpanded = true"
                        class="text-xs font-normal text-primary hover:underline">
                        Thay đổi
                    </button>
                </label>

                <!-- STATE 1: GRID MỞ RỘNG -->
                <div x-show="isCategoryExpanded" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    class="grid grid-cols-4 gap-3">
                    <template x-for="type in propertyTypes" :key="type.id">
                        <button type="button" @click="toggleCategory(type.id)"
                            :class="form.categories.includes(type.id)
                                ? 'bg-primary text-white border-primary shadow-lg shadow-blue-200 transform scale-105'
                                : 'bg-white text-gray-600 border-gray-200 hover:bg-blue-50 hover:border-blue-100 hover:text-primary'"
                            class="flex flex-col items-center justify-center p-3 border rounded-xl transition-all duration-200 aspect-square group">
                            <i
                                :class="['fas', type.icon, form.categories.includes(type.id) ? 'text-white' : 'text-primary group-hover:text-primary', 'text-xl mb-2']"></i>
                            <span class="text-xs font-medium text-center leading-tight" x-text="type.name"></span>
                        </button>
                    </template>
                </div>

                <!-- NÚT XÁC NHẬN CHỌN (hiện khi grid mở và đã chọn ít nhất 1) -->
                <div x-show="isCategoryExpanded && form.categories.length > 0" class="mt-3 flex justify-center">
                    <button type="button" @click="isCategoryExpanded = false"
                        class="bg-primary/10 text-primary px-4 py-2 rounded-lg text-xs font-bold hover:bg-primary/20 transition-colors">
                        <i class="fa-solid fa-check mr-1"></i> Xác nhận (<span x-text="form.categories.length"></span> đã chọn)
                    </button>
                </div>

                <!-- STATE 2: THẺ TÓM TẮT (thu gọn) -->
                <div x-show="!isCategoryExpanded && form.categories.length > 0"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0">
                    <div @click="isCategoryExpanded = true"
                        class="bg-primary text-white border-primary shadow-lg shadow-blue-200 p-4 rounded-xl flex items-center justify-between cursor-pointer hover:bg-blue-600 transition-colors group">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                <i :class="['fas', getSelectedCategoryIcon(), 'text-lg']"></i>
                            </div>
                            <div class="flex flex-col text-left">
                                <span class="text-xs text-blue-100 font-medium">Đã chọn loại:</span>
                                <span class="font-bold text-sm leading-tight" x-text="getSelectedCategoryNames()"></span>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-down text-white/70 group-hover:translate-y-1 transition-transform"></i>
                    </div>
                </div>
            </div>

            <!-- ===================== FINANCIAL RANGE — Collapsible ===================== -->
            <div class="mb-6"
                x-show="form.categories.length > 0 && !isCategoryExpanded"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0">
                <label class="block text-sm font-bold text-gray-800 mb-3 flex justify-between items-center">
                    Chọn mức tài chính
                    <button type="button" x-show="!isPriceExpanded && hasPriceSelected()"
                        @click="isPriceExpanded = true"
                        class="text-xs font-normal text-primary hover:underline">
                        Thay đổi
                    </button>
                </label>

                <!-- STATE 1: GRID MỞ RỘNG -->
                <div x-show="isPriceExpanded" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    class="grid grid-cols-4 gap-2">
                    <template x-for="range in priceRanges" :key="range.label">
                        <button type="button" @click="setPriceRange(range); isPriceExpanded = false;"
                            :class="isPriceSelected(range)
                                ? 'bg-primary text-white border-primary shadow-lg shadow-blue-200 transform scale-105'
                                : 'bg-white text-gray-600 border-gray-200 hover:bg-blue-50 hover:border-blue-100 hover:text-primary'"
                            class="flex items-center justify-center p-2 border rounded-xl transition-all duration-200 min-h-[50px]">
                            <span x-text="range.label"
                                class="text-[10px] sm:text-xs font-medium text-center leading-tight"></span>
                        </button>
                    </template>
                </div>

                <!-- STATE 2: THẺ TÓM TẮT -->
                <div x-show="!isPriceExpanded && hasPriceSelected()"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0">
                    <div @click="isPriceExpanded = true"
                        class="bg-primary text-white border-primary shadow-lg shadow-blue-200 p-4 rounded-xl flex items-center justify-between cursor-pointer hover:bg-blue-600 transition-colors group">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                <i class="fas fa-coins text-lg"></i>
                            </div>
                            <div class="flex flex-col text-left">
                                <span class="text-xs text-blue-100 font-medium">Mức tài chính:</span>
                                <span class="font-bold text-lg leading-tight" x-text="getSelectedPriceLabel()"></span>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-down text-white/70 group-hover:translate-y-1 transition-transform"></i>
                    </div>
                </div>
            </div>

            <!-- ===================== PURPOSE — Collapsible ===================== -->
            <div class="mb-6"
                x-show="hasPriceSelected() && !isPriceExpanded"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0">
                <label class="block text-sm font-bold text-gray-800 mb-3 flex justify-between items-center">
                    Mục đích giao dịch
                    <button type="button" x-show="!isPurposeExpanded && form.purpose"
                        @click="isPurposeExpanded = true"
                        class="text-xs font-normal text-primary hover:underline">
                        Thay đổi
                    </button>
                </label>

                <!-- STATE 1: GRID MỞ RỘNG -->
                <div x-show="isPurposeExpanded" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    class="grid grid-cols-4 gap-2">
                    <template x-for="p in purposes" :key="p.label">
                        <button type="button" @click="form.purpose = p.value; isPurposeExpanded = false;"
                            :class="form.purpose === p.value
                                ? 'bg-primary text-white border-primary shadow-lg shadow-blue-200 transform scale-105'
                                : 'bg-white text-gray-600 border-gray-200 hover:bg-blue-50 hover:border-blue-100 hover:text-primary'"
                            class="flex flex-col items-center justify-center p-2 border rounded-xl transition-all duration-200 min-h-[60px] group">
                            <i
                                :class="['fas', p.icon, form.purpose === p.value ? 'text-white' : 'text-primary group-hover:text-primary', 'text-lg mb-1']"></i>
                            <span x-text="p.label" class="text-[10px] font-medium text-center leading-tight"></span>
                        </button>
                    </template>
                </div>

                <!-- STATE 2: THẺ TÓM TẮT -->
                <div x-show="!isPurposeExpanded && form.purpose"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0">
                    <div @click="isPurposeExpanded = true"
                        class="bg-primary text-white border-primary shadow-lg shadow-blue-200 p-4 rounded-xl flex items-center justify-between cursor-pointer hover:bg-blue-600 transition-colors group">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                <i :class="['fas', getSelectedPurposeIcon(), 'text-lg']"></i>
                            </div>
                            <div class="flex flex-col text-left">
                                <span class="text-xs text-blue-100 font-medium">Mục đích:</span>
                                <span class="font-bold text-lg leading-tight" x-text="form.purpose"></span>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-down text-white/70 group-hover:translate-y-1 transition-transform"></i>
                    </div>
                </div>
            </div>

            <!-- ===================== AREA (Khu vực) — Collapsible ===================== -->
            <div class="mb-6"
                x-show="form.purpose && !isPurposeExpanded"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0">
                <label class="block text-sm font-bold text-gray-800 mb-3 flex justify-between items-center">
                    Ưu tiên khu vực
                    <button type="button" x-show="!isWardExpanded && form.wards.length > 0"
                        @click="isWardExpanded = true"
                        class="text-xs font-normal text-primary hover:underline">
                        Thay đổi
                    </button>
                </label>

                <!-- STATE 1: GRID MỞ RỘNG -->
                <div x-show="isWardExpanded" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    class="grid grid-cols-4 gap-2">
                    <template x-for="ward in wards" :key="ward.id">
                        <button type="button" @click="toggleWard(ward.id)"
                            :class="form.wards.includes(ward.id)
                                ? 'bg-primary text-white border-primary shadow-lg shadow-blue-200 transform scale-105'
                                : 'bg-white text-gray-600 border-gray-200 hover:bg-blue-50 hover:border-blue-100 hover:text-primary'"
                            class="flex flex-col items-center justify-center p-2 border rounded-xl transition-all duration-200 aspect-[4/3] group">
                            <i
                                :class="['fas', ward.icon, form.wards.includes(ward.id) ? 'text-white' : 'text-primary group-hover:text-primary', 'text-lg mb-1']"></i>
                            <span class="text-[10px] font-medium text-center leading-tight"
                                x-text="ward.name.replace('Phường ', '').replace('Xã ', '')"></span>
                        </button>
                    </template>
                </div>

                <!-- NÚT XÁC NHẬN CHỌN (hiện khi grid mở và đã chọn ít nhất 1) -->
                <div x-show="isWardExpanded && form.wards.length > 0" class="mt-3 flex justify-center">
                    <button type="button" @click="isWardExpanded = false"
                        class="bg-primary/10 text-primary px-4 py-2 rounded-lg text-xs font-bold hover:bg-primary/20 transition-colors">
                        <i class="fa-solid fa-check mr-1"></i> Xác nhận (<span x-text="form.wards.length"></span> khu vực)
                    </button>
                </div>

                <!-- STATE 2: THẺ TÓM TẮT -->
                <div x-show="!isWardExpanded && form.wards.length > 0"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0">
                    <div @click="isWardExpanded = true"
                        class="bg-primary text-white border-primary shadow-lg shadow-blue-200 p-4 rounded-xl flex items-center justify-between cursor-pointer hover:bg-blue-600 transition-colors group">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                <i class="fas fa-map-location-dot text-lg"></i>
                            </div>
                            <div class="flex flex-col text-left">
                                <span class="text-xs text-blue-100 font-medium">Khu vực ưu tiên:</span>
                                <span class="font-bold text-sm leading-tight" x-text="getSelectedWardNames()"></span>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-down text-white/70 group-hover:translate-y-1 transition-transform"></i>
                    </div>
                </div>
            </div>

        </form>

        <!-- FOOTER: FIXED BOTTOM NAVIGATION -->
        <div id="floating-footer"
            class="fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-gray-100 shadow-[0_-4px_20px_rgba(0,0,0,0.05)] z-50 flex justify-center">
            <div class="w-full max-w-md flex justify-between gap-3">
                <!-- Nút Lưu -->
                <button type="button" @click="submitForm" :disabled="loading || !isFormValid"
                    class="w-full bg-success text-white px-6 py-3.5 rounded-xl font-bold text-sm shadow-lg shadow-green-200 hover:bg-green-600 transition-transform transform active:scale-[0.98] flex justify-center items-center disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!loading">Lưu Khách Hàng</span>
                    <span x-show="loading"><i class="fas fa-circle-notch fa-spin mr-2"></i> Đang lưu...</span>
                </button>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    function addCustomerForm() {
        return {
            propertyTypes: @json($propertyTypes),
            wards: @json($wards),
            loading: false,

            // Collapse/Expand flags
            isCategoryExpanded: true,
            isPriceExpanded: true,
            isPurposeExpanded: true,
            isWardExpanded: true,

            form: {
                name: '',
                phone: '',
                lead_type: 'buy',
                categories: [],
                wards: [],
                price_min: 0,
                price_max: 0,
                purpose: ''
            },
            priceRanges: [
                { min: 0, max: 0, label: 'Thỏa thuận' },
                { min: 0, max: 1000000000, label: 'Dưới 1 tỷ' },
                { min: 1000000000, max: 3000000000, label: '1 - 3 tỷ' },
                { min: 3000000000, max: 5000000000, label: '3 - 5 tỷ' },
                { min: 5000000000, max: 10000000000, label: '5 - 10 tỷ' },
                { min: 10000000000, max: 20000000000, label: '10 - 20 tỷ' },
                { min: 20000000000, max: 50000000000, label: '20 - 50 tỷ' },
                { min: 50000000000, max: 999999999999, label: 'Trên 50 tỷ' }
            ],
            purposes: [
                { label: 'Đầu tư', value: 'Đầu tư', icon: 'fas fa-chart-line' },
                { label: 'Định cư', value: 'Định cư', icon: 'fas fa-umbrella' },
                { label: 'Kinh doanh', value: 'Kinh doanh', icon: 'fas fa-store' },
                { label: 'Nghỉ dưỡng', value: 'Nghỉ dưỡng', icon: 'fas fa-piggy-bank' },
                { label: 'Canh tác', value: 'Canh tác', icon: 'fas fa-tractor' },
                { label: 'Khác', value: 'Khác', icon: 'fas fa-question' }
            ],

            // =========== VALIDATION ===========
            get isPhoneValid() {
                let phone = this.form.phone;
                if (!phone) return false;
                phone = phone.replace(/\D/g, '');
                const regex = /^0(3|5|7|8|9)[0-9]{8}$/;
                return regex.test(phone);
            },

            get isNameValid() {
                const name = this.form.name;
                if (!name) return false;
                if (name.length < 2) return false;
                const regex = /^[a-zA-ZÀ-ỹ\s]+$/;
                return regex.test(name);
            },

            get isFormValid() {
                return this.form.name && this.isNameValid &&
                       this.form.phone && this.isPhoneValid &&
                       this.form.lead_type;
            },

            // =========== TOGGLE HELPERS ===========
            toggleCategory(id) {
                if (this.form.categories.includes(id)) {
                    this.form.categories = this.form.categories.filter(c => c !== id);
                } else {
                    this.form.categories.push(id);
                }
            },

            toggleWard(id) {
                if (this.form.wards.includes(id)) {
                    this.form.wards = this.form.wards.filter(w => w !== id);
                } else {
                    this.form.wards.push(id);
                }
            },

            setPriceRange(range) {
                this.form.price_min = range.min;
                this.form.price_max = range.max;
            },

            isPriceSelected(range) {
                return this.form.price_min === range.min && this.form.price_max === range.max;
            },

            hasPriceSelected() {
                return this.form.price_min !== 0 || this.form.price_max !== 0;
            },

            // =========== COLLAPSED CARD HELPERS ===========
            getSelectedCategoryIcon() {
                if (this.form.categories.length === 0) return 'fa-house';
                const first = this.propertyTypes.find(t => t.id === this.form.categories[0]);
                return first ? first.icon : 'fa-house';
            },

            getSelectedCategoryNames() {
                return this.form.categories
                    .map(id => {
                        const t = this.propertyTypes.find(pt => pt.id === id);
                        return t ? t.name : '';
                    })
                    .filter(n => n)
                    .join(', ');
            },

            getSelectedPriceLabel() {
                const found = this.priceRanges.find(r => r.min === this.form.price_min && r.max === this.form.price_max);
                return found ? found.label : '';
            },

            getSelectedPurposeIcon() {
                const found = this.purposes.find(p => p.value === this.form.purpose);
                return found ? found.icon : 'fa-question';
            },

            getSelectedWardNames() {
                return this.form.wards
                    .map(id => {
                        const w = this.wards.find(ward => ward.id === id);
                        return w ? w.name.replace('Phường ', '').replace('Xã ', '') : '';
                    })
                    .filter(n => n)
                    .join(', ');
            },

            // =========== SUBMIT ===========
            async submitForm() {
                if (!this.form.name || !this.isNameValid) {
                    alert('Vui lòng nhập tên hợp lệ (ít nhất 2 ký tự, không chứa ký tự đặc biệt)');
                    return;
                }
                if (!this.form.phone || !this.isPhoneValid) {
                    alert('Vui lòng nhập số điện thoại hợp lệ (10 số, bắt đầu bằng 0)');
                    return;
                }

                this.loading = true;
                try {
                    const response = await axios.post('{{ route("webapp.store_customer") }}', this.form, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    if (response.data.success) {
                        alert(response.data.message);
                        window.location.href = response.data.redirect_url;
                    }
                } catch (error) {
                    console.error(error);
                    let msg = 'Có lỗi xảy ra';
                    if (error.response && error.response.data && error.response.data.errors) {
                        msg = Object.values(error.response.data.errors).flat().join('\n');
                    } else if (error.response && error.response.data && error.response.data.message) {
                        msg = error.response.data.message;
                    }
                    alert(msg);
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>
@endsection