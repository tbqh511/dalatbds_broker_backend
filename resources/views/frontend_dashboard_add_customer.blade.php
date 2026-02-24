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

            <!-- Contact Info -->
            <div class="mb-6">
                <h3
                    class="text-xs font-bold text-gray-500 mb-3 uppercase tracking-wide flex items-center justify-center border-2 border-dashed border-primary/30 rounded-xl p-2 bg-blue-50/30">
                    <i class="fa-solid fa-user-tag mr-2 text-primary"></i> Nhập thông tin liên hệ
                </h3>

                <div class="space-y-3 mt-4">
                    <div class="relative group">
                        <input type="text" x-model="form.name" placeholder="Tên liên hệ"
                            class="input-field border-green-200 focus:border-green-500 focus:ring-green-200 bg-green-50/30">
                    </div>

                    <div class="relative group">
                        <input type="tel" x-model="form.phone" placeholder="Số điện thoại"
                            class="input-field border-green-200 focus:border-green-500 focus:ring-green-200 bg-green-50/30">
                    </div>
                </div>
            </div>

            <!-- Need Type -->
            <div class="mb-6">
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

            <!-- Property Types -->
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-800 mb-3">Chọn loại BĐS</label>
                <div class="grid grid-cols-4 gap-3">
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
            </div>

            <!-- Financial Range -->
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-800 mb-3">Chọn mức tài chính</label>
                <div class="grid grid-cols-4 gap-2">
                    <template x-for="range in priceRanges" :key="range.label">
                        <button type="button" @click="setPriceRange(range)"
                            :class="isPriceSelected(range)
                                ? 'bg-primary text-white border-primary shadow-lg shadow-blue-200 transform scale-105'
                                : 'bg-white text-gray-600 border-gray-200 hover:bg-blue-50 hover:border-blue-100 hover:text-primary'"
                            class="flex items-center justify-center p-2 border rounded-xl transition-all duration-200 min-h-[50px]">
                            <span x-text="range.label"
                                class="text-[10px] sm:text-xs font-medium text-center leading-tight"></span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Purpose -->
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-800 mb-3">Mục đích giao dịch</label>
                <div class="grid grid-cols-4 gap-2">
                    <template x-for="p in purposes" :key="p.label">
                        <button type="button" @click="form.purpose = p.value"
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
            </div>

            <!-- Area -->
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-800 mb-3">Ưu tiên khu vực</label>
                <div class="grid grid-cols-4 gap-2">
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
            </div>

        </form>

        <!-- FOOTER: FIXED BOTTOM NAVIGATION -->
        <div id="floating-footer"
            class="fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-gray-100 shadow-[0_-4px_20px_rgba(0,0,0,0.05)] z-50 flex justify-center">
            <div class="w-full max-w-md flex justify-between gap-3">
                <!-- Nút Lưu -->
                <button type="button" @click="submitForm" :disabled="loading"
                    class="w-full bg-success text-white px-6 py-3.5 rounded-xl font-bold text-sm shadow-lg shadow-green-200 hover:bg-green-600 transition-transform transform active:scale-[0.98] flex justify-center items-center disabled:opacity-70 disabled:cursor-not-allowed">
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

            async submitForm() {
                if (!this.form.name || !this.form.phone) {
                    alert('Vui lòng nhập tên và số điện thoại');
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