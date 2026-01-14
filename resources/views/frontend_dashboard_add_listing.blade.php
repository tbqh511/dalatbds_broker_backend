@extends('frontends.master')

@section('hide_newsletter')@endsection
@section('hide_footer')@endsection

@section('title', 'Đăng tin mới - Đà Lạt BDS')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.default.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/webapp.css') }}">

    <style>
        body { background-color: #F5F7FB; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
        ::-webkit-scrollbar { width: 0px; background: transparent; }
        
        .ts-control { 
            border-radius: 0.75rem; 
            padding: 12px 16px; 
            border: 1px solid #E5E7EB; 
            box-shadow: none; 
            background-color: white;
            font-size: 1rem;
        }
        .ts-control:focus { border-color: #3270FC; }
        .ts-dropdown { border-radius: 0.75rem; border: 1px solid #E5E7EB; margin-top: 4px; }
        
        [x-cloak] { display: none !important; }
        
        .input-field {
            width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid #E5E7EB; outline: none; transition: all 0.2s; background-color: white;
        }
        .input-field:focus { border-color: #3270FC; ring: 2px; ring-color: #3270FC; }
        
        /* Custom number input controls */
        .btn-counter { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 8px; background-color: #F0F5FF; color: #3270FC; font-weight: bold; transition: all 0.2s; border: 1px solid transparent; }
        .btn-counter:hover { background-color: #3270FC; color: white; }
        .btn-counter:active { transform: scale(0.95); }
    </style>
@endpush

@push('head_scripts')
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3270FC', // Màu xanh thương hiệu
                        bglo: '#F5F7FB',
                        success: '#16A34A', // Màu xanh lá cho tài chính
                    }
                }
            }
        }
    </script>

    <!-- APP LOGIC (Alpine data) -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('realEstateForm', () => ({
                step: 1,
                price: 0,
                formattedPrice: '',
                priceInWords: '0 VNĐ',
                isTypeExpanded: true, 
                isWardExpanded: true,
                
                // DATA MODEL
                formData: {
                    transactionType: 'sale',
                    type: 'dato',
                    ward: '',
                    street: '',
                    houseNumber: '',
                    contact: { gender: 'ong', name: '', phone: '', note: '' },
                    area: 0,
                    commissionRate: 2,
                    legal: '',
                    description: '',
                    floors: 1,
                    bedrooms: 2,
                    bathrooms: 2,
                    floorArea: 0,
                    frontage: 0,
                    length: 0,
                    roadWidth: 0,
                    direction: 'DongNam',
                    amenities: {}
                },

                streets: [
                    {id: '1', name: 'Đường Phù Đổng Thiên Vương'},
                    {id: '2', name: 'Đường Bùi Thị Xuân'},
                    {id: '3', name: 'Đường Phan Đình Phùng'},
                    {id: '4', name: 'Đường Mai Anh Đào'},
                    {id: '5', name: 'Đường Trần Phú'},
                    {id: '6', name: 'Đường Hai Bà Trưng'},
                    {id: '7', name: 'Đường Ba Tháng Tư'},
                    {id: '8', name: 'Đường Yersin'}
                ],
                propertyTypes: [
                    {id: 'nha', name: 'Nhà ở', icon: 'fa-house', isHouse: true},
                    {id: 'bietthu', name: 'Biệt thự', icon: 'fa-hotel', isHouse: true},
                    {id: 'khachsan', name: 'Khách sạn', icon: 'fa-bell-concierge', isHouse: true},
                    {id: 'chungcu', name: 'Chung cư', icon: 'fa-building', isHouse: true},
                    {id: 'dato', name: 'Đất ở', icon: 'fa-map-location-dot', isHouse: false},
                    {id: 'datnn', name: 'Đất NN', icon: 'fa-seedling', isHouse: false}, 
                    {id: 'nhaphanq', name: 'Nhà PQ', icon: 'fa-file-signature', isHouse: true}, 
                    {id: 'datphanq', name: 'Đất PQ', icon: 'fa-file-contract', isHouse: false},
                    {id: 'nhagiaytay', name: 'Nhà GT', icon: 'fa-file-pen', isHouse: true}, 
                    {id: 'datgiaytay', name: 'Đất GT', icon: 'fa-note-sticky', isHouse: false},
                ],
                wards: [
                    {id: 'p1', name: 'Phường 1', icon: 'fa-map-pin'},
                    {id: 'p2', name: 'Phường 2', icon: 'fa-map-pin'},
                    {id: 'p3', name: 'Phường 3', icon: 'fa-map-pin'},
                    {id: 'p4', name: 'Phường 4', icon: 'fa-map-pin'},
                    {id: 'p5', name: 'Phường 5', icon: 'fa-map-pin'},
                    {id: 'p6', name: 'Phường 6', icon: 'fa-map-pin'},
                    {id: 'p7', name: 'Phường 7', icon: 'fa-map-pin'},
                    {id: 'p8', name: 'Phường 8', icon: 'fa-map-pin'},
                    {id: 'p9', name: 'Phường 9', icon: 'fa-map-pin'},
                    {id: 'p10', name: 'Phường 10', icon: 'fa-map-pin'},
                    {id: 'p11', name: 'Phường 11', icon: 'fa-map-pin'},
                    {id: 'p12', name: 'Phường 12', icon: 'fa-map-pin'},
                    {id: 'xxuantho', name: 'Xã Xuân Thọ', icon: 'fa-tree'},
                    {id: 'xtramhanh', name: 'Xã Trạm Hành', icon: 'fa-mountain-sun'},
                ],
                amenitiesList: [
                    {id: 'market', name: 'Chợ', icon: 'fa-basket-shopping'},
                    {id: 'school', name: 'Trường học', icon: 'fa-graduation-cap'},
                    {id: 'hospital', name: 'Bệnh viện', icon: 'fa-hospital'},
                    {id: 'park', name: 'Công viên', icon: 'fa-tree'},
                    {id: 'supermarket', name: 'Siêu thị', icon: 'fa-cart-shopping'},
                    {id: 'airport', name: 'Sân bay', icon: 'fa-plane'},
                    {id: 'ho_xuan_huong', name: 'Hồ Xuân Hương', icon: 'fa-water'},
                    {id: 'quang_truong', name: 'Quảng trường', icon: 'fa-users'},
                ],
                directions: ['Đông', 'Tây', 'Nam', 'Bắc', 'Đông Nam', 'Đông Bắc', 'Tây Nam', 'Tây Bắc'],
                locationText: 'Chưa xác định vị trí',
                nextStep() { if(this.step < 4) this.step++; },
                prevStep() { if(this.step > 1) this.step--; },
                getPropertyName() { const type = this.propertyTypes.find(t => t.id === this.formData.type); return type ? type.name : 'Bất động sản'; },
                isHouseType() { const type = this.propertyTypes.find(t => t.id === this.formData.type); return type ? type.isHouse : false; },
                getSelectedType() { return this.propertyTypes.find(t => t.id === this.formData.type) || this.propertyTypes[0]; },
                selectPropertyType(id) { this.formData.type = id; this.isTypeExpanded = false; },
                getSelectedWard() { return this.wards.find(w => w.id === this.formData.ward) || { name: 'Chọn Khu vực', icon: 'fa-map' }; },
                selectWard(id) { this.formData.ward = id; this.isWardExpanded = false; },
                toggleAmenity(id) { if (id in this.formData.amenities) { let temp = {...this.formData.amenities}; delete temp[id]; this.formData.amenities = temp; } else { this.formData.amenities = { ...this.formData.amenities, [id]: '' }; } },
                isAmenitySelected(id) { return id in this.formData.amenities; },
                getAmenityIcon(id) { const am = this.amenitiesList.find(a => a.id === id); return am ? am.icon : 'fa-circle'; },
                getAmenityName(id) { const am = this.amenitiesList.find(a => a.id === id); return am ? am.name : id; },
                handlePriceInput(e) { let value = e.target.value.replace(/[^0-9]/g, ''); if (!value) value = '0'; this.price = parseInt(value); this.formattedPrice = new Intl.NumberFormat('vi-VN').format(this.price); this.priceInWords = this.readMoney(this.price); },
                addZeros() { this.price = this.price * 1000; this.formattedPrice = new Intl.NumberFormat('vi-VN').format(this.price); this.priceInWords = this.readMoney(this.price); },
                calculateCommission() { if(!this.price) return '0 VNĐ'; const commission = this.price * (this.formData.commissionRate / 100); return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(commission); },
                calculatePricePerM2() { if(!this.price || !this.formData.area) return '0'; const perM2 = this.price / this.formData.area; if(perM2 >= 1000000) { return (perM2 / 1000000).toFixed(1) + ' Triệu'; } return new Intl.NumberFormat('vi-VN').format(perM2); },
                getCurrentLocation() { this.locationText = "Đang lấy vị trí..."; setTimeout(() => { this.locationText = "📍 Đã ghim: " + (this.formData.street ? this.getStreetName(this.formData.street) : "Vị trí hiện tại của bạn"); }, 1000); },
                getStreetName(id) { const st = this.streets.find(s => s.id == id); return st ? st.name : 'Đường đã chọn'; },
                updateMapLocation() { if(this.formData.street && this.formData.houseNumber) { const streetName = this.getStreetName(this.formData.street); this.locationText = `📍 Đã ghim: ${this.formData.houseNumber}, ${streetName}`; } },
                readMoney(number) { if (number === 0) return '0 VNĐ'; if (number >= 1000000000) { return (number / 1000000000).toFixed(2).replace('.00', '') + ' Tỷ VNĐ'; } if (number >= 1000000) { return (number / 1000000).toFixed(0) + ' Triệu VNĐ'; } return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(number); },
                submitForm() { alert("Đang gửi dữ liệu về hệ thống..."); console.log(JSON.parse(JSON.stringify(this.formData))); }
            }));
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
@endpush

@section('content')
    <div x-data="realEstateForm" class="w-full max-w-md bg-white min-h-screen shadow-2xl relative flex flex-col pb-24">
        
        <!-- HEADER -->
        <div class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 px-5 py-4">
            <div class="flex justify-between items-center mb-2">
                <h1 class="text-lg font-bold text-gray-800">Đăng Tin Mới</h1>
                <span class="text-xs font-bold text-primary bg-blue-50 px-2 py-1 rounded-md">Bước <span x-text="step"></span>/4</span>
            </div>
            <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-primary transition-all duration-500 ease-out" :style="'width: ' + (step/4)*100 + '%'" ></div>
            </div>
        </div>

        <!-- SCROLLABLE CONTENT -->
        <form class="flex-1 p-5 overflow-y-auto" @submit.prevent="submitForm">
            
            <!-- === BƯỚC 1: VỊ TRÍ & LOẠI BĐS === -->
            <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                
                <!-- Hình thức giao dịch (Bán / Cho Thuê) -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-800 mb-3">Hình thức giao dịch</label>
                    <div class="grid grid-cols-2 gap-3 p-1 bg-gray-100 rounded-xl">
                        <button type="button" @click="formData.transactionType = 'sale'"
                            :class="formData.transactionType === 'sale' ? 'bg-primary text-white shadow-md' : 'text-gray-500 hover:text-primary'"
                            class="py-3 px-4 rounded-lg text-sm font-bold transition-all flex items-center justify-center">
                            <i class="fa-solid fa-tag mr-2"></i> Cần Bán
                        </button>
                        <button type="button" @click="formData.transactionType = 'rent'"
                            :class="formData.transactionType === 'rent' ? 'bg-primary text-white shadow-md' : 'text-gray-500 hover:text-primary'"
                            class="py-3 px-4 rounded-lg text-sm font-bold transition-all flex items-center justify-center">
                            <i class="fa-solid fa-key mr-2"></i> Cho Thuê
                        </button>
                    </div>
                </div>

                <!-- Loại BĐS - Collapsible Logic -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-800 mb-3 flex justify-between items-center">
                        Loại bất động sản
                        <button type="button" x-show="!isTypeExpanded" @click="isTypeExpanded = true" class="text-xs font-normal text-primary hover:underline">
                            Thay đổi
                        </button>
                    </label>

                    <!-- STATE 1: DANH SÁCH MỞ RỘNG -->
                    <div x-show="isTypeExpanded" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="grid grid-cols-3 gap-3">
                        <template x-for="item in propertyTypes" :key="item.id">
                            <button type="button" 
                                @click="selectPropertyType(item.id)"
                                :class="formData.type === item.id 
                                    ? 'bg-primary text-white border-primary shadow-lg shadow-blue-200 transform scale-105' 
                                    : 'bg-white text-primary border-gray-200 hover:bg-blue-50 hover:border-blue-100'"
                                class="flex flex-col items-center justify-center p-3 border rounded-xl transition-all duration-200 aspect-square">
                                <i :class="['fa-solid', item.icon, 'text-xl mb-2']"></i>
                                <span class="text-xs font-medium text-center leading-tight" x-text="item.name"></span>
                            </button>
                        </template>
                    </div>

                    <!-- STATE 2: ĐÃ CHỌN (Thu gọn) -->
                    <div x-show="!isTypeExpanded" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                        <div @click="isTypeExpanded = true" class="bg-primary text-white border-primary shadow-lg shadow-blue-200 p-4 rounded-xl flex items-center justify-between cursor-pointer hover:bg-blue-600 transition-colors group">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                    <i :class="['fa-solid', getSelectedType().icon, 'text-lg']"></i>
                                </div>
                                <div class="flex flex-col text-left">
                                    <span class="text-xs text-blue-100 font-medium">Đã chọn loại:</span>
                                    <span class="font-bold text-lg leading-tight" x-text="getSelectedType().name"></span>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-down text-white/70 group-hover:translate-y-1 transition-transform"></i>
                        </div>
                    </div>
                </div>

                <!-- Khu vực - Collapsible Logic -->
                <div class="mb-6 space-y-4">
                    <!-- Chọn Phường -->
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-3 flex justify-between items-center">
                            Khu vực
                            <button type="button" x-show="!isWardExpanded" @click="isWardExpanded = true" class="text-xs font-normal text-primary hover:underline">
                                Thay đổi
                            </button>
                        </label>

                        <!-- STATE 1: DANH SÁCH MỞ RỘNG (Grid 3 cột) -->
                        <div x-show="isWardExpanded" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="grid grid-cols-3 gap-2">
                            <template x-for="ward in wards" :key="ward.id">
                                <button type="button" 
                                    @click="selectWard(ward.id)"
                                    :class="formData.ward === ward.id 
                                        ? 'bg-primary text-white border-primary shadow-md' 
                                        : 'bg-white text-primary border-gray-200 hover:bg-blue-50'"
                                    class="flex flex-col items-center justify-center p-2 border rounded-xl transition-all duration-200 aspect-[4/3] group">
                                    <i :class="['fa-solid', ward.icon, 'text-lg mb-1 group-hover:scale-110 transition-transform']"></i>
                                    <span class="text-[10px] font-bold text-center leading-tight" x-text="ward.name"></span>
                                </button>
                            </template>
                        </div>

                        <!-- STATE 2: ĐÃ CHỌN (Thu gọn) -->
                        <div x-show="!isWardExpanded" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                            <div @click="isWardExpanded = true" class="bg-primary text-white border-primary shadow-lg shadow-blue-200 p-4 rounded-xl flex items-center justify-between cursor-pointer hover:bg-blue-600 transition-colors group">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                        <i :class="['fa-solid', getSelectedWard().icon, 'text-lg']"></i>
                                    </div>
                                    <div class="flex flex-col text-left">
                                        <span class="text-xs text-blue-100 font-medium">Đã chọn khu vực:</span>
                                        <span class="font-bold text-lg leading-tight" x-text="getSelectedWard().name"></span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-chevron-down text-white/70 group-hover:translate-y-1 transition-transform"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Chọn Đường -->
                    <!-- (Phần tiếp theo của form có thể được thêm ở đây; giữ nguyên logic và binding của Alpine) -->
                </div>

            </div>

            <!-- TODO: Các bước 2-4 tiếp tục giống template temp (đã giữ logic trong Alpine) -->

            <div class="mt-6 flex items-center justify-between">
                <div>
                    <button type="button" @click.prevent="prevStep" class="px-3 py-2 rounded-md bg-gray-100 text-gray-700">Quay lại</button>
                </div>
                <div>
                    <button type="button" @click.prevent="nextStep" class="px-4 py-2 rounded-md bg-primary text-white">Tiếp tục</button>
                </div>
            </div>

        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script>
        // Global Telegram WebApp Logic (Run on every page load)
        if (window.Telegram && window.Telegram.WebApp) {
            const tg = window.Telegram.WebApp;
            tg.expand();
            try {
                tg.setHeaderColor('#3270FC');
                tg.setBackgroundColor('#ffffff');
            } catch (e) {
                console.warn('Telegram WebApp setHeaderColor failed:', e);
            }
        }
    </script>
@endpush
