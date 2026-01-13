<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Tin - Đà Lạt BĐS</title>
    
    <!-- Libraries -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Config for Brand Colors -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3270FC',
                        bglo: '#F5F7FB',
                    }
                }
            }
        }
    </script>

    <!-- APP LOGIC (Moved to Head to fix ReferenceError) -->
    <script>
        function realEstateForm() {
            return {
                step: 1,
                price: 0,
                formattedPrice: '',
                priceInWords: '0 VNĐ',
                formData: {
                    type: 'dato', // Mặc định Đất ở
                    ward: '',
                    street: '', // Sẽ lưu ID đường
                    houseNumber: '',
                    area: 0,
                    commissionRate: 2, // Mặc định 2%
                    legal: '',
                    description: '',
                    contact: { gender: 'ong', name: '', phone: '', note: '' }
                },
                // Danh sách đường giả lập (Bạn có thể load từ API)
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
                    {id: 'nha', name: 'Nhà ở'},
                    {id: 'bietthu', name: 'Biệt thự'},
                    {id: 'khachsan', name: 'Khách sạn'},
                    {id: 'chungcu', name: 'Chung cư'},
                    {id: 'dato', name: 'Đất ở'},
                    {id: 'datnn', name: 'Đất nông nghiệp'},
                    {id: 'nhaphanq', name: 'Nhà phân quyền'},
                    {id: 'datphanq', name: 'Đất phân quyền'},
                    {id: 'nhagiaytay', name: 'Nhà giấy tay'},
                    {id: 'datgiaytay', name: 'Đất giấy tay'},
                ],
                locationText: 'Chưa xác định vị trí',

                // Logic chuyển bước
                nextStep() {
                    this.step++;
                },

                // Lấy tên loại BĐS để hiển thị ở Bước 3
                getPropertyName() {
                    const type = this.propertyTypes.find(t => t.id === this.formData.type);
                    return type ? type.name : 'Bất động sản';
                },

                // Xử lý nhập giá tiền (Format 1,000,000)
                handlePriceInput(e) {
                    let value = e.target.value.replace(/[^0-9]/g, '');
                    if (!value) value = '0';
                    
                    this.price = parseInt(value);
                    this.formattedPrice = new Intl.NumberFormat('vi-VN').format(this.price);
                    this.priceInWords = this.readMoney(this.price);
                },

                // Nút Shortcut thêm 3 số 0
                addZeros() {
                    this.price = this.price * 1000;
                    this.formattedPrice = new Intl.NumberFormat('vi-VN').format(this.price);
                    this.priceInWords = this.readMoney(this.price);
                },

                // Tính hoa hồng
                calculateCommission() {
                    if(!this.price) return '0 VNĐ';
                    const commission = this.price * (this.formData.commissionRate / 100);
                    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(commission);
                },

                // Tính đơn giá / m2
                calculatePricePerM2() {
                    if(!this.price || !this.formData.area) return '0';
                    const perM2 = this.price / this.formData.area;
                    if(perM2 >= 1000000) {
                        return (perM2 / 1000000).toFixed(1) + ' Triệu';
                    }
                    return new Intl.NumberFormat('vi-VN').format(perM2);
                },

                // Giả lập định vị
                getCurrentLocation() {
                    this.locationText = "Đang lấy vị trí...";
                    setTimeout(() => {
                        this.locationText = "📍 Đã ghim: " + (this.formData.street ? this.getStreetName(this.formData.street) : "Vị trí hiện tại của bạn");
                    }, 1000);
                },
                
                // Helper tìm tên đường
                getStreetName(id) {
                    const st = this.streets.find(s => s.id == id);
                    return st ? st.name : 'Đường đã chọn';
                },

                updateMapLocation() {
                    if(this.formData.street && this.formData.houseNumber) {
                        const streetName = this.getStreetName(this.formData.street);
                        this.locationText = `📍 Đã ghim: ${this.formData.houseNumber}, ${streetName}`;
                    }
                },

                // Hàm đọc số tiền
                readMoney(number) {
                    if (number === 0) return '0 VNĐ';
                    if (number >= 1000000000) {
                        return (number / 1000000000).toFixed(2).replace('.00', '') + ' Tỷ VNĐ';
                    }
                    if (number >= 1000000) {
                        return (number / 1000000).toFixed(0) + ' Triệu VNĐ';
                    }
                    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(number);
                },

                submitForm() {
                    alert("Đang gửi dữ liệu về hệ thống...");
                    console.log(JSON.parse(JSON.stringify(this.formData)));
                    console.log("Price:", this.price);
                }
            }
        }
    </script>

    <!-- Alpine JS (Load after logic defined) -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    
    <!-- Tom Select -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.default.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <style>
        body { background-color: #F5F7FB; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
        
        /* Custom Scrollbar hide */
        ::-webkit-scrollbar { width: 0px; background: transparent; }
        
        /* Tom Select Customization for Tailwind */
        .ts-control { 
            border-radius: 0.75rem; 
            padding: 12px 16px; 
            border: 1px solid #E5E7EB; 
            box-shadow: none; 
            background-color: white;
        }
        .ts-control:focus { border-color: #3270FC; }
        .ts-dropdown { border-radius: 0.75rem; border: 1px solid #E5E7EB; margin-top: 4px; }
        
        /* Step transition */
        [x-cloak] { display: none !important; }
        
        /* Input focus styles */
        .input-field {
            width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid #E5E7EB; outline: none; transition: all 0.2s;
        }
        .input-field:focus { border-color: #3270FC; ring: 2px; ring-color: #3270FC; }
    </style>
</head>
<body class="flex justify-center min-h-screen pb-20">

    <!-- APP CONTAINER -->
    <div x-data="realEstateForm()" class="w-full max-w-md bg-white min-h-screen shadow-2xl relative flex flex-col">
        
        <!-- HEADER: Fixed Top -->
        <div class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 px-5 py-4">
            <div class="flex justify-between items-center mb-2">
                <h1 class="text-lg font-bold text-gray-800">Đăng Tin Mới</h1>
                <span class="text-xs font-bold text-primary bg-blue-50 px-2 py-1 rounded-md">Bước <span x-text="step"></span>/4</span>
            </div>
            <!-- Progress Bar -->
            <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-primary transition-all duration-500 ease-out" :style="'width: ' + (step/4)*100 + '%'"></div>
            </div>
        </div>

        <!-- SCROLLABLE CONTENT -->
        <form class="flex-1 p-5 overflow-y-auto" @submit.prevent="submitForm">
            
            <!-- BƯỚC 1: VỊ TRÍ & LOẠI BĐS -->
            <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <h2 class="text-xl font-bold text-gray-800 mb-1">Thông tin cơ bản</h2>
                <p class="text-sm text-gray-500 mb-6">Xác định loại hình và vị trí bất động sản.</p>

                <!-- Chọn Loại BĐS (Grid Selection) -->
                <label class="block text-sm font-semibold text-gray-700 mb-2">Loại bất động sản</label>
                <div class="grid grid-cols-2 gap-3 mb-6">
                    <template x-for="item in propertyTypes" :key="item.id">
                        <button type="button" 
                            @click="formData.type = item.id"
                            :class="formData.type === item.id ? 'bg-primary text-white border-primary shadow-md shadow-blue-200' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50'"
                            class="py-3 px-2 border rounded-xl text-sm font-medium transition-all text-center truncate">
                            <span x-text="item.name"></span>
                        </button>
                    </template>
                </div>

                <!-- Chọn Phường -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Khu vực</label>
                    <select x-model="formData.ward" class="input-field bg-white appearance-none">
                        <option value="">Chọn Phường/Xã...</option>
                        <option>Phường 1</option>
                        <option>Phường 2</option>
                        <option>Phường 3</option>
                        <option>Phường Cam Ly</option>
                        <option>Phường Lâm Viên</option>
                        <option>Phường Xuân Hương</option>
                        <option>Phường Xuân Trường</option>
                        <option>Xã Xuân Thọ</option>
                        <option>Xã Trạm Hành</option>
                    </select>
                </div>

                <!-- Chọn Đường (Tom Select) -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tên đường</label>
                    <select id="select-street" x-model="formData.street" placeholder="Tìm tên đường..." autocomplete="off">
                        <option value="">Chọn đường...</option>
                        <template x-for="st in streets" :key="st.id">
                            <option :value="st.id" x-text="st.name"></option>
                        </template>
                    </select>
                </div>

                <!-- Số nhà -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Số nhà</label>
                    <input type="text" x-model="formData.houseNumber" @input="updateMapLocation" placeholder="VD: 123/4" class="input-field">
                </div>

                <!-- Google Map Preview -->
                <div class="bg-gray-50 p-3 rounded-2xl border border-gray-200">
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-sm font-bold text-gray-700">📍 Vị trí trên bản đồ</label>
                        <button type="button" @click="getCurrentLocation" class="text-xs text-primary font-bold flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Vị trí của tôi
                        </button>
                    </div>
                    <!-- Map Placeholder -->
                    <div id="map" class="w-full h-48 bg-gray-200 rounded-xl relative overflow-hidden flex items-center justify-center group cursor-pointer">
                        <div class="absolute inset-0 bg-cover bg-center opacity-50" style="background-image: url('https://upload.wikimedia.org/wikipedia/commons/e/ec/Map_of_Dalat.jpg');"></div>
                        <span class="z-10 bg-white/80 px-4 py-2 rounded-full text-xs font-bold shadow-sm backdrop-blur">
                            🗺️ Chạm để chọn vị trí chính xác
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-2 italic" x-text="locationText"></p>
                </div>
            </div>

            <!-- BƯỚC 2: GIÁ & PHÁP LÝ -->
            <div x-show="step === 2" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Giá & Pháp lý</h2>

                <!-- Thông tin chủ nhà -->
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 mb-6">
                    <h3 class="text-sm font-bold text-gray-800 mb-3 uppercase tracking-wide">👤 Chủ sở hữu</h3>
                    <div class="flex gap-4 mb-3">
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="gender" value="ong" x-model="formData.contact.gender" class="text-primary focus:ring-primary">
                            <span class="text-sm">Ông</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="gender" value="ba" x-model="formData.contact.gender" class="text-primary focus:ring-primary">
                            <span class="text-sm">Bà</span>
                        </label>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <input type="text" x-model="formData.contact.name" placeholder="Tên chủ nhà" class="input-field bg-white">
                        <input type="tel" x-model="formData.contact.phone" placeholder="Số điện thoại" class="input-field bg-white">
                    </div>
                    <textarea x-model="formData.contact.note" placeholder="Ghi chú (Gọi giờ nào, v.v.)" class="input-field bg-white h-20 resize-none"></textarea>
                </div>

                <!-- Giá bán (Có đọc số tiền) -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Giá mong muốn (VNĐ)</label>
                    <div class="relative">
                        <input type="text" x-model="formattedPrice" @input="handlePriceInput" placeholder="Nhập giá (VD: 3 ty 5)" class="input-field pr-12 font-bold text-gray-800 text-lg">
                        <button type="button" @click="addZeros" class="absolute right-2 top-2 bg-gray-100 px-2 py-1 rounded text-xs font-bold text-gray-600 hover:bg-gray-200">+000</button>
                    </div>
                    <p class="text-sm text-primary font-bold mt-2" x-text="priceInWords"></p>
                </div>

                <!-- Hoa hồng -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mức hoa hồng (%)</label>
                    <div class="flex gap-2 overflow-x-auto pb-2">
                        <template x-for="rate in [1, 1.5, 2, 2.5, 3]">
                            <button type="button" 
                                @click="formData.commissionRate = rate"
                                :class="formData.commissionRate === rate ? 'bg-green-100 text-green-700 border-green-300' : 'bg-white border-gray-200 text-gray-600'"
                                class="flex-shrink-0 px-4 py-2 border rounded-lg text-sm font-bold transition-all">
                                <span x-text="rate + '%'"></span>
                            </button>
                        </template>
                    </div>
                    <div class="mt-2 text-xs text-gray-500 bg-gray-50 p-2 rounded-lg inline-block">
                        💰 Hoa hồng ước tính: <span class="font-bold text-gray-800" x-text="calculateCommission()"></span>
                    </div>
                </div>

                <!-- Diện tích & Đơn giá -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Diện tích (m²)</label>
                    <input type="number" x-model="formData.area" placeholder="VD: 100" class="input-field">
                    <p class="text-xs text-gray-500 mt-2" x-show="formData.area > 0 && price > 0">
                        📉 Đơn giá: <span class="font-bold text-gray-800" x-text="calculatePricePerM2()"></span> / m²
                    </p>
                </div>

                <!-- Giấy tờ -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Loại giấy tờ</label>
                    <select x-model="formData.legal" class="input-field bg-white appearance-none">
                        <option value="">Chọn loại giấy tờ...</option>
                        <option>Sổ riêng xây dựng</option>
                        <option>Sổ riêng nông nghiệp</option>
                        <option>Sổ phân quyền xây dựng</option>
                        <option>Sổ phân quyền nông nghiệp</option>
                        <option>Giấy tay / Vi bằng</option>
                    </select>
                </div>

                <!-- Mô tả -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mô tả chi tiết</label>
                    <textarea x-model="formData.description" class="input-field h-32 resize-none" placeholder="Mô tả về đường đi, view, nội thất..."></textarea>
                </div>

                <!-- Upload Ảnh -->
                <div class="space-y-4">
                    <!-- Ảnh chính -->
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:border-primary transition-colors cursor-pointer bg-gray-50">
                        <span class="text-2xl">📸</span>
                        <p class="text-xs font-bold text-gray-700 mt-1">Ảnh đại diện (1 tấm)</p>
                        <p class="text-[10px] text-gray-400">Chạm để tải lên</p>
                    </div>
                    
                    <!-- Ảnh giấy tờ (Riêng tư) -->
                    <div class="border-2 border-dashed border-yellow-300 rounded-xl p-4 text-center hover:border-yellow-500 transition-colors cursor-pointer bg-yellow-50">
                        <span class="text-2xl">📑</span>
                        <p class="text-xs font-bold text-yellow-800 mt-1">Ảnh giấy tờ/Sổ (Bảo mật)</p>
                        <p class="text-[10px] text-yellow-600">Chỉ Sale mới thấy</p>
                    </div>

                    <!-- Ảnh khác -->
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:border-primary transition-colors cursor-pointer bg-gray-50">
                        <span class="text-2xl">🖼️</span>
                        <p class="text-xs font-bold text-gray-700 mt-1">Ảnh chi tiết khác</p>
                        <p class="text-[10px] text-gray-400">Tải nhiều ảnh</p>
                    </div>
                </div>
            </div>

            <!-- BƯỚC 3: CHI TIẾT KỸ THUẬT (Dynamic) -->
            <div x-show="step === 3" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Chi tiết kỹ thuật</h2>
                
                <div class="bg-blue-50 border border-blue-100 rounded-lg p-3 mb-4 text-sm text-blue-800">
                    Đang nhập thông tin cho: <strong x-text="getPropertyName()"></strong>
                </div>

                <!-- Các trường chung cho NHÀ / BIỆT THỰ / KHÁCH SẠN -->
                <template x-if="['nha', 'bietthu', 'khachsan', 'chungcu'].includes(formData.type)">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Diện tích sàn (m²)</label>
                            <input type="number" class="input-field" placeholder="0">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Số phòng ngủ</label>
                                <div class="flex items-center">
                                    <button type="button" class="w-8 h-8 rounded bg-gray-200 text-gray-600 font-bold">-</button>
                                    <input type="number" class="w-full text-center bg-transparent border-none outline-none" value="2">
                                    <button type="button" class="w-8 h-8 rounded bg-gray-200 text-gray-600 font-bold">+</button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Số toilet</label>
                                <div class="flex items-center">
                                    <button type="button" class="w-8 h-8 rounded bg-gray-200 text-gray-600 font-bold">-</button>
                                    <input type="number" class="w-full text-center bg-transparent border-none outline-none" value="2">
                                    <button type="button" class="w-8 h-8 rounded bg-gray-200 text-gray-600 font-bold">+</button>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Hướng nhà</label>
                            <select class="input-field bg-white">
                                <option>Đông Nam</option>
                                <option>Tây Nam</option>
                                <option>Đông Bắc</option>
                                <option>Tây Bắc</option>
                                <option>Chính Nam</option>
                                <option>Chính Bắc</option>
                            </select>
                        </div>
                    </div>
                </template>

                <!-- Các trường chung cho ĐẤT -->
                <template x-if="['dato', 'datnn', 'datphanq', 'datgiaytay'].includes(formData.type)">
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Mặt tiền (m)</label>
                                <input type="number" class="input-field" placeholder="VD: 5m">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Dài (m)</label>
                                <input type="number" class="input-field" placeholder="VD: 20m">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lộ giới / Đường rộng (m)</label>
                            <input type="number" class="input-field" placeholder="VD: 5m (Xe hơi)">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mật độ xây dựng (%)</label>
                            <input type="number" class="input-field" placeholder="VD: 50%">
                        </div>
                    </div>
                </template>

            </div>

            <!-- BƯỚC 4: TIỆN ÍCH -->
            <div x-show="step === 4" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Tiện ích xung quanh</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-700 mb-1">
                            🛒 Cách Chợ Đà Lạt / Chợ gần nhất
                        </label>
                        <div class="relative">
                            <input type="number" class="input-field pl-10" placeholder="Khoảng cách (km)">
                            <span class="absolute left-3 top-3 text-lg">🏪</span>
                        </div>
                    </div>
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-700 mb-1">
                            🏫 Cách Trường Học
                        </label>
                        <div class="relative">
                            <input type="number" class="input-field pl-10" placeholder="Khoảng cách (km)">
                            <span class="absolute left-3 top-3 text-lg">🎓</span>
                        </div>
                    </div>
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-700 mb-1">
                            🏥 Cách Bệnh Viện
                        </label>
                        <div class="relative">
                            <input type="number" class="input-field pl-10" placeholder="Khoảng cách (km)">
                            <span class="absolute left-3 top-3 text-lg">🚑</span>
                        </div>
                    </div>
                </div>

                <div class="mt-8 p-4 bg-green-50 rounded-xl border border-green-100 text-center">
                    <p class="text-sm text-green-800 font-medium">Bạn đã điền đầy đủ thông tin!</p>
                    <p class="text-xs text-green-600 mt-1">Bấm hoàn tất để gửi tin về hệ thống.</p>
                </div>
            </div>

        </form>

        <!-- FOOTER: NAVIGATION -->
        <div class="p-4 bg-white border-t border-gray-100 flex justify-between items-center shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
            <button type="button" x-show="step > 1" @click="step--" 
                class="text-gray-500 font-semibold text-sm px-4 py-3 rounded-lg hover:bg-gray-100 transition-colors">
                Quay lại
            </button>
            
            <div x-show="step === 1" class="flex-1"></div> <!-- Spacer for step 1 -->

            <button type="button" x-show="step < 4" @click="nextStep" 
                class="bg-primary text-white px-8 py-3 rounded-xl font-bold text-sm shadow-lg shadow-blue-200 hover:bg-blue-600 transition-transform transform active:scale-95 flex items-center ml-auto">
                Tiếp tục
            </button>

            <button type="button" x-show="step === 4" @click="submitForm" 
                class="bg-green-500 text-white px-8 py-3 rounded-xl font-bold text-sm shadow-lg shadow-green-200 hover:bg-green-600 transition-transform transform active:scale-95 ml-auto">
                ✅ Hoàn Tất Đăng Tin
            </button>
        </div>

    </div>

    <!-- Tom Select Init -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                new TomSelect("#select-street",{
                    create: false,
                    sortField: { field: "text", direction: "asc" },
                    plugins: ['dropdown_input'],
                    maxOptions: null
                });
            }, 100);
        });
    </script>
</body>
</html>