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
    {{-- Tailwind Play CDN removed: compile Tailwind via Vite for production to avoid CSP/desktop WebView issues --}}
    {{-- If you haven't set up Tailwind, follow instructions: npm install -D tailwindcss postcss autoprefixer && npx tailwindcss init -p, add resources/css/app.css with @tailwind directives, update vite.config input and run npm run build. --}}
    @if (app()->environment('local'))
        {{-- In local/dev you can still use vite dev server --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        {{-- In production ensure compiled CSS is referenced via Vite build --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

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
                    type: @json($propertyTypes->isNotEmpty() ? $propertyTypes->first()['id'] : ''),
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

                streets: @json($streets),
                propertyTypes: @json($propertyTypes),
                wards: @json($wards),
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
                // --- MAP PICKER STATE ---
                showMapPicker: false,
                pickerMap: null,
                pickerGeocoder: null,
                pickerAddress: '',
                pickerLat: null,
                pickerLng: null,
                isMapDragging: false,
                searchBox: null,

                // Open fullscreen map picker
                openMapPicker() {
                    this.showMapPicker = true;
                    this.$nextTick(() => {
                        if (!this.pickerMap && window.google) {
                            this.initGoogleMap();
                        }
                    });
                },

                // Initialize Google Map inside picker
                initGoogleMap() {
                    const defaultPos = { lat: 11.940419, lng: 108.458313 };
                    this.pickerMap = new google.maps.Map(document.getElementById("picker-map"), {
                        center: defaultPos,
                        zoom: 15,
                        disableDefaultUI: true,
                        clickableIcons: false,
                        gestureHandling: "greedy",
                    });

                    this.pickerGeocoder = new google.maps.Geocoder();

                    this.pickerMap.addListener("dragstart", () => {
                        this.isMapDragging = true;
                        this.pickerAddress = "Đang di chuyển...";
                    });

                    this.pickerMap.addListener("idle", () => {
                        this.isMapDragging = false;
                        const center = this.pickerMap.getCenter();
                        this.pickerLat = center.lat();
                        this.pickerLng = center.lng();
                        this.reverseGeocode(center);
                    });

                    const input = document.getElementById("map-search-box");
                    try {
                        this.searchBox = new google.maps.places.Autocomplete(input);
                        this.searchBox.bindTo("bounds", this.pickerMap);
                        this.searchBox.addListener("place_changed", () => {
                            const place = this.searchBox.getPlace();
                            if (!place.geometry || !place.geometry.location) return;
                            if (place.geometry.viewport) {
                                this.pickerMap.fitBounds(place.geometry.viewport);
                            } else {
                                this.pickerMap.setCenter(place.geometry.location);
                                this.pickerMap.setZoom(17);
                            }
                        });
                    } catch (e) {
                        console.warn('Places Autocomplete init failed', e);
                    }
                },

                // Reverse geocode
                reverseGeocode(latlng) {
                    if (!this.pickerGeocoder) return;
                    this.pickerGeocoder.geocode({ location: latlng }, (results, status) => {
                        if (status === "OK" && results[0]) {
                            let address = results[0].formatted_address.replace(', Vietnam', '');
                            this.pickerAddress = address;
                            const route = results[0].address_components.find(c => c.types.includes('route'));
                            if (route) console.log('Đường:', route.long_name);
                        } else {
                            this.pickerAddress = "Vị trí chưa xác định tên";
                        }
                    });
                },

                // Pan to current GPS location inside picker
                panToCurrentLocation() {
                    if (navigator.geolocation) {
                        this.isMapDragging = true;
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                const pos = { lat: position.coords.latitude, lng: position.coords.longitude };
                                if (this.pickerMap) {
                                    this.pickerMap.setCenter(pos);
                                    this.pickerMap.setZoom(17);
                                } else {
                                    // update quick preview text if picker not open
                                    this.locationText = `📍 Đã ghim: Vị trí hiện tại của bạn`;
                                }
                                this.isMapDragging = false;
                            },
                            () => { this.isMapDragging = false; alert("Không lấy được vị trí."); }
                        );
                    }
                },

                // Confirm pick and close
                confirmMapLocation() {
                    this.locationText = this.pickerAddress || this.locationText;
                    // Optional: save lat/lng to formData
                    // this.formData.latitude = this.pickerLat; this.formData.longitude = this.pickerLng;
                    this.showMapPicker = false;
                },
                
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
    <div class="flex items-start justify-center min-h-screen w-full py-10 px-4">
    <div x-data="realEstateForm" class="w-full max-w-md bg-white shadow-2xl relative flex flex-col pb-24 rounded-xl overflow-hidden h-auto max-h-[90vh]">
        
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
        <form class="flex-1 p-5 pb-32 overflow-y-auto" @submit.prevent="submitForm">
            
            <!-- === BƯỚC 1: VỊ TRÍ & LOẠI BĐS === -->
            <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                
                <!-- Hình thức giao dịch (Bán / Cho Thuê) -->
                <div class="mb-6">
                    {{-- <label class="block text-sm font-bold text-gray-800 mb-3">Hình thức giao dịch</label> --}}
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
                    <div x-show="isTypeExpanded" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="grid grid-cols-4 gap-3">
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
                        <div x-show="isWardExpanded" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="grid grid-cols-4 gap-2">
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
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5 text-left">Tên đường</label>
                        <select id="select-street" x-model="formData.street" 
                                x-init="$nextTick(() => {
                                    new TomSelect($el, {
                                        create: false,
                                        sortField: { field: 'text', direction: 'asc' },
                                        plugins: ['dropdown_input'],
                                        maxOptions: null,
                                        onChange: (value) => { formData.street = value; }
                                    });
                                })"
                                placeholder="Tìm tên đường..." autocomplete="off">
                            <option value="">Chọn đường...</option>
                            <template x-for="st in streets" :key="st.id">
                                <option :value="st.id" x-text="st.name"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Số nhà -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5 text-left">Số nhà</label>
                        <input type="text" x-model="formData.houseNumber" @input="updateMapLocation" placeholder="VD: 123/4" class="input-field">
                    </div>

                    <!-- Google Map Preview -->
                    <div class="bg-white p-3 rounded-2xl border border-gray-200 shadow-sm">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-sm font-bold text-gray-700">📍 Vị trí trên bản đồ</label>
                            <button type="button" @click="panToCurrentLocation" class="text-xs text-primary font-bold flex items-center bg-blue-50 px-2 py-1 rounded">
                                <i class="fa-solid fa-crosshairs mr-1"></i> Vị trí của tôi
                            </button>
                        </div>
                        <div id="map-preview" @click="openMapPicker" class="w-full h-40 bg-gray-100 rounded-xl relative overflow-hidden flex items-center justify-center cursor-pointer border border-dashed border-gray-300 group hover:border-primary transition-colors">
                            <div class="absolute inset-0 bg-cover bg-center opacity-60" style="background-image: url('https://upload.wikimedia.org/wikipedia/commons/e/ec/Map_of_Dalat.jpg');"></div>
                            <span class="z-10 bg-white/90 px-4 py-2 rounded-full text-xs font-bold shadow-sm backdrop-blur text-gray-700 border border-gray-200 group-hover:text-primary group-hover:scale-105 transition-all">
                                🗺️ Chạm để chọn vị trí
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2 truncate" x-text="locationText"></p>
                        
                        
                    </div>
                </div>
            </div>

            <!-- === BƯỚC 2: GIÁ & PHÁP LÝ === -->
            <div x-show="step === 2" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                {{-- <h2 class="text-xl font-bold text-gray-700 mb-4 text-center">Giá & Pháp lý</h2> --}}

                <!-- Thông tin chủ nhà (Căn giữa Radio) -->
                <div class="border-2 border-dashed border-primary/30 rounded-xl p-4 text-center hover:bg-blue-50 transition-colors cursor-pointer bg-white group">
                    <h3 class="text-xs font-bold text-gray-500 mb-3 uppercase tracking-wide flex items-center">
                        <i class="fa-solid fa-user-tag mr-2 text-primary"></i> Chủ sở hữu
                    </h3>
                    <!-- Canh giữa Radio buttons -->
                    <div class="flex justify-center gap-8 mb-4 border-b border-gray-100 pb-3">
                        <label class="flex items-center space-x-2 cursor-pointer p-2 hover:bg-gray-50 rounded-lg">
                            <input type="radio" name="gender" value="ong" x-model="formData.contact.gender" class="text-primary focus:ring-primary h-4 w-4">
                            <span class="text-sm font-bold text-gray-700">Ông</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer p-2 hover:bg-gray-50 rounded-lg">
                            <input type="radio" name="gender" value="ba" x-model="formData.contact.gender" class="text-primary focus:ring-primary h-4 w-4">
                            <span class="text-sm font-bold text-gray-700">Bà</span>
                        </label>
                    </div>
                    <div class="space-y-3">
                        <input type="text" x-model="formData.contact.name" placeholder="Họ và tên" class="input-field ">
                        <input type="tel" x-model="formData.contact.phone" placeholder="Số điện thoại" class="input-field ">
                        <textarea x-model="formData.contact.note" placeholder="Ghi chú (Gọi giờ hành chính...)" class="input-field h-20 resize-none"></textarea>
                    </div>
                </div>

                <!-- Giá bán (Căn phải + Màu Primary) -->
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 text-left">Giá mong muốn (VNĐ)</label>
                    <div class="relative">
                        <input type="text" x-model="formattedPrice" @input="handlePriceInput" placeholder="0" class="input-field pr-16 font-bold text-gray-800 text-xl tracking-wide">
                        <button type="button" @click="addZeros" class="absolute right-2 top-2 bg-gray-100 px-2 py-1.5 rounded-lg text-xs font-bold text-gray-600 hover:bg-gray-200 border border-gray-200 shadow-sm active:scale-95 transition-transform">
                            +000
                        </button>
                    </div>
                    <p class="text-sm text-success font-bold mt-1.5 flex justify-end items-center">
                        <i class="fa-solid fa-tag mr-1.5 text-xs"></i> <span x-text="priceInWords"></span>
                    </p>
                </div>

                <!-- Hoa hồng (Màu Primary) -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 text-left">Mức hoa hồng (%)</label>
                    <div class="flex gap-2 overflow-x-auto pb-2 no-scrollbar">
                        <template x-for="rate in [1, 1.5, 2, 2.5, 3]">
                            <button type="button" 
                                @click="formData.commissionRate = rate"
                                :class="formData.commissionRate === rate ? 'bg-primary text-white border-primary ring-1 ring-primary shadow-md' : 'bg-white border-gray-200 text-gray-600'"
                                class="flex-shrink-0 px-4 py-2 border rounded-lg text-sm font-bold transition-all min-w-[60px]">
                                <span x-text="rate + '%'"></span>
                            </button>
                        </template>
                    </div>
                    <div class="mt-1 text-xs text-gray-500 bg-gray-50 p-2.5 rounded-lg border border-gray-100 flex justify-between items-center">
                        <span>Nhận về:</span>
                        <span class="font-bold text-success text-sm" x-text="calculateCommission()"></span>
                    </div>
                </div>

                <!-- Diện tích (Căn phải + Màu Primary) -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 text-left">Diện tích (m²)</label>
                    <div class="relative">
                        <input type="number" x-model="formData.area" placeholder="0" class="input-field pr-10">
                        <span class="absolute right-3 top-3 text-gray-400 font-bold text-sm">m²</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-2 flex justify-end px-1" x-show="formData.area > 0 && price > 0">
                        <span class="mr-2">Đơn giá:</span>
                        <span class="font-bold text-success"><span x-text="calculatePricePerM2()"></span> / m²</span>
                    </p>
                </div>

                <!-- Giấy tờ & Mô tả -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 text-left">Loại giấy tờ</label>
                    <div class="relative">
                        <select x-model="formData.legal" class="input-field bg-white appearance-none">
                            <option value="">Chọn loại giấy tờ...</option>
                            <option>Sổ riêng xây dựng</option>
                            <option>Sổ riêng nông nghiệp</option>
                            <option>Sổ phân quyền xây dựng</option>
                            <option>Sổ phân quyền nông nghiệp</option>
                            <option>Giấy tay / Vi bằng</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-4 top-4 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mô tả chi tiết</label>
                    <textarea x-model="formData.description" class="input-field h-32 resize-none" placeholder="Mô tả về đường đi, view, nội thất, tiện ích..."></textarea>
                </div>

                <!-- Upload Ảnh -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-gray-800 border-l-4 border-primary pl-2 text-left">Hình ảnh & Giấy tờ</h3>
                    
                    <!-- Ảnh chính -->
                    <div class="border-2 border-dashed border-primary/30 rounded-xl p-4 text-center hover:bg-blue-50 transition-colors cursor-pointer bg-white group">
                        <div class="w-10 h-10 bg-blue-100 text-primary rounded-full flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-camera"></i>
                        </div>
                        <p class="text-sm font-bold text-gray-700 text-center">Ảnh đại diện</p>
                        <p class="text-xs text-gray-400 text-center">Bắt buộc 1 tấm đẹp nhất</p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Ảnh giấy tờ -->
                        <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center hover:bg-gray-50 transition-colors cursor-pointer bg-white group">
                            <div class="w-8 h-8 bg-gray-100 text-gray-500 group-hover:text-primary group-hover:bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-2 transition-colors">
                                <i class="fa-solid fa-file-shield"></i>
                            </div>
                            <p class="text-xs font-bold text-gray-700 text-center">Sổ đỏ/Pháp lý</p>
                            <p class="text-[10px] text-gray-400 text-center"><i class="fa-solid fa-lock mr-1"></i>Bảo mật</p>
                        </div>

                        <!-- Ảnh khác -->
                        <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center hover:bg-gray-50 transition-colors cursor-pointer bg-white group">
                            <div class="w-8 h-8 bg-gray-100 text-gray-500 group-hover:text-primary group-hover:bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-2 transition-colors">
                                <i class="fa-regular fa-images"></i>
                            </div>
                            <p class="text-xs font-bold text-gray-700 text-center">Ảnh khác</p>
                            <p class="text-[10px] text-gray-400 text-center">Nội thất, đường đi...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- === BƯỚC 3: CHI TIẾT KỸ THUẬT (TRANG TRÍ LẠI) === -->
            <div x-show="step === 3" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                {{-- <h2 class="text-xl font-bold text-gray-800 mb-4">Chi tiết kỹ thuật</h2> --}}
                
                <div class="bg-blue-50 text-primary px-4 py-3 rounded-xl mb-6 border border-blue-100 flex items-center shadow-sm">
                    <i :class="['fa-solid', getSelectedType().icon, 'mr-3 text-lg']"></i>
                    <div>
                        <p class="text-xs text-blue-400 uppercase font-bold tracking-wide">Loại BĐS</p>
                        <p class="font-bold text-gray-800 text-lg" x-text="getPropertyName()"></p>
                    </div>
                </div>
                
                <!-- FORM CHO NHÀ -->
                <template x-if="isHouseType()">
                    <div class="space-y-6">
                        <!-- Diện tích sàn -->
                        <div class="relative group">
                            <label class="block text-xs font-bold text-primary mb-1 uppercase tracking-wide">Diện tích sàn</label>
                            <div class="flex items-center border border-gray-200 rounded-xl bg-white overflow-hidden group-focus-within:border-primary group-focus-within:ring-1 group-focus-within:ring-primary transition-all">
                                <div class="w-10 h-10 flex items-center justify-center text-gray-400 bg-gray-50 border-r border-gray-100">
                                    <i class="fa-solid fa-ruler-combined"></i>
                                </div>
                                <input type="number" x-model="formData.floorArea" class="flex-1 p-2.5 outline-none font-bold text-gray-700" placeholder="0">
                                <span class="pr-4 text-sm font-bold text-gray-400">m²</span>
                            </div>
                        </div>

                        <!-- Số tầng & Hướng -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-primary mb-1 uppercase tracking-wide">Số tầng</label>
                                <div class="flex items-center justify-between bg-white border border-gray-200 rounded-xl p-1">
                                    <button type="button" @click="if(formData.floors > 1) formData.floors--" class="btn-counter"><i class="fa-solid fa-minus"></i></button>
                                    <span class="font-bold text-lg text-gray-800" x-text="formData.floors"></span>
                                    <button type="button" @click="formData.floors++" class="btn-counter"><i class="fa-solid fa-plus"></i></button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-primary mb-1 uppercase tracking-wide">Hướng nhà</label>
                                <div class="relative">
                                    <select x-model="formData.direction" class="w-full bg-white border border-gray-200 rounded-xl p-2.5 font-bold text-gray-700 outline-none focus:border-primary appearance-none h-[44px]">
                                        <template x-for="d in directions">
                                            <option :value="d" x-text="d"></option>
                                        </template>
                                    </select>
                                    <i class="fa-solid fa-compass absolute right-3 top-3.5 text-gray-400 pointer-events-none"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Phòng ngủ & Toilet -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white p-3 rounded-xl border border-gray-200 shadow-sm">
                                <div class="flex items-center mb-2 text-gray-500">
                                    <i class="fa-solid fa-bed mr-2"></i> <span class="text-xs font-bold uppercase">Phòng ngủ</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <button type="button" @click="if(formData.bedrooms > 0) formData.bedrooms--" class="text-gray-400 hover:text-primary text-xl"><i class="fa-solid fa-circle-minus"></i></button>
                                    <span class="text-2xl font-bold text-gray-800" x-text="formData.bedrooms"></span>
                                    <button type="button" @click="formData.bedrooms++" class="text-primary hover:text-blue-600 text-xl"><i class="fa-solid fa-circle-plus"></i></button>
                                </div>
                            </div>
                            <div class="bg-white p-3 rounded-xl border border-gray-200 shadow-sm">
                                <div class="flex items-center mb-2 text-gray-500">
                                    <i class="fa-solid fa-bath mr-2"></i> <span class="text-xs font-bold uppercase">Toilet</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <button type="button" @click="if(formData.bathrooms > 0) formData.bathrooms--" class="text-gray-400 hover:text-primary text-xl"><i class="fa-solid fa-circle-minus"></i></button>
                                    <span class="text-2xl font-bold text-gray-800" x-text="formData.bathrooms"></span>
                                    <button type="button" @click="formData.bathrooms++" class="text-primary hover:text-blue-600 text-xl"><i class="fa-solid fa-circle-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- FORM CHO ĐẤT -->
                <template x-if="!isHouseType()">
                    <div class="space-y-6">
                        <!-- Kích thước đất -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="relative group">
                                <label class="block text-xs font-bold text-primary mb-1 uppercase tracking-wide">Mặt tiền</label>
                                <div class="flex items-center border border-gray-200 rounded-xl bg-white overflow-hidden group-focus-within:border-primary transition-all">
                                    <input type="number" x-model="formData.frontage" class="flex-1 p-2.5 outline-none font-bold text-gray-700" placeholder="0">
                                    <span class="pr-3 text-sm font-bold text-gray-400">m</span>
                                </div>
                            </div>
                            <div class="relative group">
                                <label class="block text-xs font-bold text-primary mb-1 uppercase tracking-wide">Chiều dài</label>
                                <div class="flex items-center border border-gray-200 rounded-xl bg-white overflow-hidden group-focus-within:border-primary transition-all">
                                    <input type="number" x-model="formData.length" class="flex-1 p-2.5 outline-none font-bold text-gray-700" placeholder="0">
                                    <span class="pr-3 text-sm font-bold text-gray-400">m</span>
                                </div>
                            </div>
                        </div>

                        <!-- Lộ giới & Hướng -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="relative group">
                                <label class="block text-xs font-bold text-primary mb-1 uppercase tracking-wide">Lộ giới</label>
                                <div class="flex items-center border border-gray-200 rounded-xl bg-white overflow-hidden group-focus-within:border-primary transition-all">
                                    <div class="pl-3 text-gray-400"><i class="fa-solid fa-road"></i></div>
                                    <input type="number" x-model="formData.roadWidth" class="flex-1 p-2.5 outline-none font-bold text-gray-700" placeholder="0">
                                    <span class="pr-3 text-sm font-bold text-gray-400">m</span>
                                </div>
                            </div>
                            <div class="relative group">
                                <label class="block text-xs font-bold text-primary mb-1 uppercase tracking-wide">Hướng</label>
                                <div class="relative">
                                    <select x-model="formData.direction" class="w-full bg-white border border-gray-200 rounded-xl p-2.5 font-bold text-gray-700 outline-none focus:border-primary appearance-none h-[42px]">
                                        <template x-for="d in directions">
                                            <option :value="d" x-text="d"></option>
                                        </template>
                                    </select>
                                    <i class="fa-solid fa-compass absolute right-3 top-3.5 text-gray-400 pointer-events-none"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-3 bg-white rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
                             <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center mr-3">
                                    <i class="fa-solid fa-maximize"></i>
                                </div>
                                <span class="font-bold text-gray-700">Đất nở hậu?</span>
                             </div>
                             <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                              </label>
                        </div>
                    </div>
                </template>
            </div>

            <!-- === BƯỚC 4: TIỆN ÍCH XUNG QUANH (LOGIC MỚI - GRID BUTTON) === -->
            <div x-show="step === 4" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <h2 class="text-xl font-bold text-gray-800 mb-1">Tiện ích xung quanh</h2>
                <p class="text-sm text-gray-500 mb-6">Chọn các địa điểm gần BĐS của bạn.</p>
                
                <!-- GRID TIỆN ÍCH (4 Cột) -->
                <div class="grid grid-cols-4 gap-2 mb-6">
                    <template x-for="am in amenitiesList" :key="am.id">
                        <button type="button" 
                            @click="toggleAmenity(am.id)"
                            :class="isAmenitySelected(am.id) 
                                ? 'bg-primary text-white border-primary shadow-md transform scale-105' 
                                : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50'"
                            class="flex flex-col items-center justify-center p-2 border rounded-xl transition-all duration-200 aspect-square">
                            <i :class="['fa-solid', am.icon, 'text-lg mb-1']"></i>
                            <span class="text-[9px] font-bold text-center leading-tight truncate w-full" x-text="am.name"></span>
                        </button>
                    </template>
                </div>

                <!-- LIST INPUT KHOẢNG CÁCH (Chỉ hiện cái đã chọn) -->
                <div class="space-y-3" x-show="Object.keys(formData.amenities).length > 0" x-transition>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Nhập khoảng cách (km)</h3>
                    
                    <template x-for="(dist, id) in formData.amenities" :key="id">
                        <div class="flex items-center bg-white border border-gray-200 rounded-xl p-2 pr-4 shadow-sm animate-fade-in-up">
                            <!-- Icon & Name -->
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-primary flex items-center justify-center mr-3 flex-shrink-0">
                                <i :class="['fa-solid', getAmenityIcon(id)]"></i>
                            </div>
                            <div class="flex-1 mr-3">
                                <p class="text-xs font-bold text-gray-500 uppercase" x-text="getAmenityName(id)"></p>
                                <p class="text-sm font-bold text-gray-800">Cách bao xa?</p>
                            </div>
                            <!-- Input -->
                            <div class="relative w-24">
                                <input type="number" x-model="formData.amenities[id]" class="w-full bg-gray-50 border border-gray-200 rounded-lg py-1.5 pl-2 pr-6 text-right font-bold text-gray-800 text-sm focus:border-primary outline-none" placeholder="0">
                                <span class="absolute right-2 top-1.5 text-xs text-gray-400 font-bold">km</span>
                            </div>
                            <!-- Remove Btn -->
                            <button type="button" @click="toggleAmenity(id)" class="ml-3 text-gray-300 hover:text-red-500">
                                <i class="fa-solid fa-times"></i>
                            </button>
                        </div>
                    </template>
                </div>

                <div x-show="Object.keys(formData.amenities).length === 0" class="py-10 text-center text-gray-400 border-2 border-dashed border-gray-100 rounded-xl">
                    <i class="fa-solid fa-map-location-dot text-4xl mb-2 text-gray-200"></i>
                    <p class="text-xs">Chưa chọn tiện ích nào</p>
                </div>
            </div>

        </form>

        <!-- FOOTER: FIXED BOTTOM NAVIGATION -->
        <div id="floating-footer" class="fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-gray-100 shadow-[0_-4px_20px_rgba(0,0,0,0.05)] z-50 flex justify-center">
            <div class="w-full max-w-md flex justify-between gap-3">
                <!-- Nút Quay lại -->
                <button type="button" x-show="step > 1" @click="prevStep" 
                    class="flex-1 bg-gray-100 text-gray-600 px-4. py-3.5 rounded-xl font-bold text-sm hover:bg-gray-200 transition-colors">
                    Quay lại
                </button>
                
                <!-- Nút Tiếp tục -->
                <button type="button" x-show="step < 4" @click="nextStep" 
                    :class="step === 1 ? 'w-full' : 'flex-[2]'"
                    class="bg-primary text-white px-6 py-3.5 rounded-xl font-bold text-sm shadow-lg shadow-blue-200 hover:bg-blue-600 transition-transform transform active:scale-[0.98] flex justify-center items-center">
                    Tiếp tục <i class="fa-solid fa-arrow-right ml-2"></i>
                </button>

                <!-- Nút Hoàn tất -->
                <button type="button" x-show="step === 4" @click="submitForm" 
                    class="flex-[2] bg-success text-white px-6 py-3.5 rounded-xl font-bold text-sm shadow-lg shadow-green-200 hover:bg-green-600 transition-transform transform active:scale-[0.98] flex justify-center items-center">
                    Đăng Tin <i class="fa-solid fa-paper-plane ml-2"></i>
                </button>
            </div>
        </div>

    <!-- Fullscreen Map Picker -->
    <div x-show="showMapPicker" x-cloak 
         class="fixed inset-0 z-[100] bg-white flex flex-col w-full h-full transition-transform duration-300"
         x-transition:enter="transform transition ease-in-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transform transition ease-in-out duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full">

        <div class="absolute top-0 left-0 right-0 z-10 p-4 pt-safe-top bg-gradient-to-b from-white/90 to-transparent pointer-events-none">
            <div class="flex items-center gap-3 pointer-events-auto">
                <button @click="showMapPicker = false" class="w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center text-gray-600 hover:text-primary active:scale-95 transition-transform">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                
                <div class="flex-1 bg-white rounded-full shadow-md flex items-center px-4 h-10 border border-gray-100">
                    <i class="fa-solid fa-search text-gray-400 mr-2"></i>
                    <input id="map-search-box" type="text" placeholder="Tìm tên đường, khu vực..." 
                           class="flex-1 bg-transparent outline-none text-sm text-gray-700 placeholder-gray-400">
                </div>
            </div>
        </div>

        <div class="relative flex-1 w-full h-full bg-gray-100">
            <div id="picker-map" class="w-full h-full"></div>

            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 -mt-4 pointer-events-none z-0 flex flex-col items-center justify-center">
                <i class="fa-solid fa-location-dot text-4xl text-red-500 drop-shadow-md animate-bounce-short"></i>
                <div class="w-3 h-1.5 bg-black/20 rounded-[100%] mt-1 blur-[1px]"></div>
            </div>

            <button @click="panToCurrentLocation" class="absolute bottom-6 right-4 z-10 w-12 h-12 bg-white rounded-full shadow-lg flex items-center justify-center text-primary active:bg-gray-50">
                <i class="fa-solid fa-crosshairs text-lg"></i>
            </button>
        </div>

        <div class="bg-white p-4 pb-safe-bottom rounded-t-2xl shadow-[0_-4px_20px_rgba(0,0,0,0.1)] z-10 border-t border-gray-100">
            <div class="mb-4">
                <p class="text-xs text-gray-400 uppercase font-bold tracking-wide mb-1">Vị trí đã chọn</p>
                <div class="flex items-start">
                    <i class="fa-solid fa-map-pin text-primary mt-1 mr-2"></i>
                    <p class="text-sm font-medium text-gray-800 line-clamp-2" x-text="pickerAddress || 'Đang xác định vị trí...'"></p>
                </div>
            </div>

            <button @click="confirmMapLocation" 
                    :disabled="!pickerLat || isMapDragging"
                    :class="(!pickerLat || isMapDragging) ? 'bg-gray-300 cursor-not-allowed' : 'bg-primary shadow-lg shadow-blue-200 active:scale-[0.98]'"
                    class="w-full text-white py-3.5 rounded-xl font-bold text-sm transition-all flex items-center justify-center">
                <span x-show="!isMapDragging">Xác nhận vị trí này</span>
                <span x-show="isMapDragging"><i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Đang tải...</span>
            </button>
        </div>
    </div>

    </div>
    </div>
    <style>
        /* Ẩn các thành phần thừa của Google Map để giao diện sạch như App */
        .gmnoprint, .gm-control-active, .gm-style-cc { display: none !important; }
        
        /* Animation cho cái ghim nhảy nhảy */
        @keyframes bounce-short {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        .animate-bounce-short { animation: bounce-short 1s infinite; }
    </style>
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
