<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng tin BĐS Đà Lạt</title>
    
    <script src="https://cdn.tailwindcss.com"></script> <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script> <style>
        /* Tùy chỉnh Tom Select cho giống giao diện hiện đại */
        .ts-control { border-radius: 0.5rem; padding: 12px; border: 1px solid #e5e7eb; }
        .step-active { background-color: #3b82f6; color: white; border-color: #3b82f6; }
        /* Ẩn thanh scroll cho đẹp */
        ::-webkit-scrollbar { width: 0px; background: transparent; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex justify-center items-start pt-10 font-sans">

    <div x-data="{ step: 1, type: 'sale' }" class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden mb-20">
        
        <div class="bg-gray-100 px-6 py-4 flex justify-between items-center">
            <div class="flex space-x-2">
                <div class="h-2 w-8 rounded-full transition-all duration-300" :class="step >= 1 ? 'bg-blue-500' : 'bg-gray-300'"></div>
                <div class="h-2 w-8 rounded-full transition-all duration-300" :class="step >= 2 ? 'bg-blue-500' : 'bg-gray-300'"></div>
                <div class="h-2 w-8 rounded-full transition-all duration-300" :class="step >= 3 ? 'bg-blue-500' : 'bg-gray-300'"></div>
            </div>
            <span class="text-sm font-semibold text-gray-500">Bước <span x-text="step"></span>/3</span>
        </div>

        <form class="p-6">
            
            <div x-show="step === 1" x-transition>
                <h2 class="text-xl font-bold mb-4 text-gray-800">Loại bất động sản</h2>

                <div class="flex bg-gray-100 p-1 rounded-xl mb-6">
                    <button type="button" @click="type = 'sale'" 
                        :class="type === 'sale' ? 'bg-white shadow text-blue-600' : 'text-gray-500 hover:text-gray-700'"
                        class="flex-1 py-3 rounded-lg text-sm font-bold transition-all">
                        🛒 Cần Bán
                    </button>
                    <button type="button" @click="type = 'rent'" 
                        :class="type === 'rent' ? 'bg-white shadow text-green-600' : 'text-gray-500 hover:text-gray-700'"
                        class="flex-1 py-3 rounded-lg text-sm font-bold transition-all">
                        🔑 Cho Thuê
                    </button>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Khu vực (Phường/Xã)</label>
                    <select class="w-full p-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none appearance-none bg-white">
                        <option>Phường 1</option>
                        <option>Phường 2</option>
                        <option>Phường 10</option>
                        <option>Xã Xuân Thọ</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tên đường</label>
                    <select id="select-street" placeholder="Gõ để tìm đường..." autocomplete="off">
                        <option value="">Chọn đường...</option>
                        <option value="1">Đường Phù Đổng Thiên Vương</option>
                        <option value="2">Đường Bùi Thị Xuân</option>
                        <option value="3">Đường Phan Đình Phùng</option>
                        <option value="4">Đường Mai Anh Đào</option>
                        <option value="5">Đường Trần Phú</option>
                    </select>
                </div>
            </div>

            <div x-show="step === 2" x-transition style="display: none;">
                <h2 class="text-xl font-bold mb-4 text-gray-800">Ghim vị trí chính xác</h2>
                <p class="text-sm text-gray-500 mb-2">Kéo thả ghim đỏ để chọn vị trí đất.</p>
                
                <div class="w-full h-64 bg-gray-200 rounded-xl flex items-center justify-center border-2 border-dashed border-gray-300 relative overflow-hidden">
                    <span class="text-gray-400 z-10">📍 Google Map sẽ hiện ở đây</span>
                    </div>
                
                <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-100">
                    <p class="text-xs text-blue-800 font-semibold">📍 Địa chỉ gợi ý:</p>
                    <p class="text-sm text-gray-700 truncate">123 Phù Đổng Thiên Vương...</p>
                </div>
            </div>

            <div x-show="step === 3" x-transition style="display: none;">
                <h2 class="text-xl font-bold mb-4 text-gray-800">Tiện ích đi kèm</h2>
                
                <div class="flex flex-wrap gap-2 mb-6">
                    <label class="cursor-pointer">
                        <input type="checkbox" class="peer sr-only">
                        <span class="px-4 py-2 rounded-full border border-gray-300 text-sm text-gray-600 peer-checked:bg-blue-500 peer-checked:text-white peer-checked:border-blue-500 transition-all">
                            🚗 Có chỗ đậu xe
                        </span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="checkbox" class="peer sr-only">
                        <span class="px-4 py-2 rounded-full border border-gray-300 text-sm text-gray-600 peer-checked:bg-blue-500 peer-checked:text-white peer-checked:border-blue-500 transition-all">
                            🌳 Gần công viên
                        </span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="checkbox" class="peer sr-only">
                        <span class="px-4 py-2 rounded-full border border-gray-300 text-sm text-gray-600 peer-checked:bg-blue-500 peer-checked:text-white peer-checked:border-blue-500 transition-all">
                            🏫 Gần trường học
                        </span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="checkbox" class="peer sr-only">
                        <span class="px-4 py-2 rounded-full border border-gray-300 text-sm text-gray-600 peer-checked:bg-blue-500 peer-checked:text-white peer-checked:border-blue-500 transition-all">
                            📜 Sổ riêng
                        </span>
                    </label>
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <p class="text-center text-gray-400 text-sm">Hình ảnh & Video upload sau...</p>
                </div>
            </div>

        </form>

        <div class="p-6 bg-white border-t border-gray-100 flex justify-between items-center">
            <button x-show="step > 1" @click="step--" 
                class="text-gray-500 font-semibold text-sm hover:text-gray-800 transition-colors">
                ← Quay lại
            </button>
            <div x-show="step === 1"></div> <button x-show="step < 3" @click="step++" 
                class="bg-black text-white px-6 py-3 rounded-xl font-bold text-sm shadow-lg hover:bg-gray-800 transition-transform transform active:scale-95 flex items-center">
                Tiếp tục →
            </button>

            <button x-show="step === 3" @click="alert('Đăng tin thành công!')" 
                class="bg-green-500 text-white px-8 py-3 rounded-xl font-bold text-sm shadow-lg hover:bg-green-600 transition-transform transform active:scale-95">
                Đăng Tin Ngay 🚀
            </button>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect("#select-street",{
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                plugins: ['dropdown_input'], // Cho phép gõ tìm kiếm
                maxOptions: null // Hiển thị tất cả kết quả tìm được
            });
        });
    </script>
</body>
</html>