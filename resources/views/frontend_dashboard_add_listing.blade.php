@extends('frontends.master')

@section('hide_newsletter')@endsection
@section('hide_footer')@endsection

@section('title', 'Đăng tin mới - Đà Lạt BDS')

@push('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        .ts-dropdown { border-radius: 0.75rem; border: 1px solid #E5E7EB; margin-top: 4px; z-index: 1000003 !important; }

        [x-cloak] { display: none !important; }

        .input-field {
            width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid #E5E7EB; outline: none; transition: all 0.2s; background-color: white;
        }
        .input-field:focus { border-color: #22c55e; ring: 2px; ring-color: #bbf7d0; }

        /* Custom number input controls */
        .btn-counter { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 8px; background-color: #F0F5FF; color: #3270FC; font-weight: bold; transition: all 0.2s; border: 1px solid transparent; }
        .btn-counter:hover { background-color: #3270FC; color: white; }
        .btn-counter:active { transform: scale(0.95); }

        /* Hide number input arrows for cleaner appearance */
        input[type='number']::-webkit-inner-spin-button,
        input[type='number']::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type='number'] {
            -moz-appearance: textfield;
        }

        /* Ẩn Header/Navbar chính của Website chỉ khi ở chế độ map picker */
        body.hide-header header, body.hide-header .header, body.hide-header .main-header, body.hide-header .navbar, body.hide-header #floating-footer {
            display: none !important;
        }

        /* Đảm bảo nội dung không bị đẩy xuống do padding của header cũ */
        body {
            padding-top: 0 !important;
        }
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
                isAmenityExpanded: false,
                price: 0,
                formattedPrice: '',
                priceInWords: '0 VNĐ',
                isTypeExpanded: true,
                isWardExpanded: true,
                isLegalExpanded: true,

                // DATA MODEL
                formData: {
                    transactionType: '',
                    type: '',
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
                    amenities: {},
                    parameters: {}
                },

                // IMAGE UPLOAD STATE
                images: {
                    avatar: null,
                    legal: [],
                    others: []
                },

                handleImageUpload(event, type) {
                    const files = event.target.files;
                    if (!files.length) return;

                    const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    const maxSize = 5 * 1024 * 1024; // 5MB

                    const validateFile = (file) => {
                        if (!validTypes.includes(file.type)) return 'Định dạng không hỗ trợ (chỉ JPG, PNG, GIF, WebP)';
                        if (file.size > maxSize) return 'Kích thước file quá lớn (> 5MB)';
                        return null;
                    };

                    if (type === 'avatar') {
                        // Single file logic
                        const file = files[0];
                        const error = validateFile(file);
                        if (error) {
                            alert(error);
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.images.avatar = {
                                file: file,
                                preview: e.target.result,
                                name: file.name,
                                size: (file.size / 1024 / 1024).toFixed(2) + ' MB'
                            };
                        };
                        reader.readAsDataURL(file);
                    } else {
                        // Multi file logic
                        const currentFiles = this.images[type];
                        if (currentFiles.length + files.length > 10) {
                            alert(`Bạn chỉ được chọn tối đa 10 tệp tin. Hiện đã có ${currentFiles.length} tệp.`);
                            return;
                        }

                        Array.from(files).forEach(file => {
                            const error = validateFile(file);
                            if (error) {
                                alert(`File ${file.name}: ${error}`); // Simple alert for now
                                return;
                            }

                            const reader = new FileReader();
                            reader.onload = (e) => {
                                // Push to array
                                this.images[type].push({
                                    id: Date.now() + Math.random().toString(36).substr(2, 9),
                                    file: file,
                                    preview: e.target.result,
                                    name: file.name,
                                    size: (file.size / 1024 / 1024).toFixed(2) + ' MB',
                                    progress: 0,
                                    status: 'pending'
                                });
                            };
                            reader.readAsDataURL(file);
                        });
                    }

                    // Reset input value
                    event.target.value = '';
                },

                removeImage(type, index = null) {
                    if (type === 'avatar') {
                        this.images.avatar = null;
                    } else if (index !== null) {
                        this.images[type].splice(index, 1);
                    }
                },

                streets: @json($streets),
                propertyTypes: @json($propertyTypes),
                wards: @json($wards),
                legalTypes: [
                    {value: 'Sổ riêng xây dựng', name: 'Sổ riêng xây dựng', icon: 'fa-file-contract'},
                    {value: 'Sổ riêng nông nghiệp', name: 'Sổ riêng nông nghiệp', icon: 'fa-file-contract'},
                    {value: 'Sổ phân quyền xây dựng', name: 'Sổ phân quyền xây dựng', icon: 'fa-file-signature'},
                    {value: 'Sổ phân quyền nông nghiệp', name: 'Sổ phân quyền nông nghiệp', icon: 'fa-file-signature'},
                    {value: 'Giấy tay / Vi bằng', name: 'Giấy tay / Vi bằng', icon: 'fa-file-alt'}
                ],
                amenitiesList: @json($facilities),
                parameters: @json($parameters),
                assignParameters: @json($assignParameters),
                directions: ['Đông', 'Tây', 'Nam', 'Bắc', 'Đông Nam', 'Đông Bắc', 'Tây Nam', 'Tây Bắc'],
                locationText: 'Chưa xác định vị trí',
                init() {
                    this.$watch('showMapPicker', (value) => {
                        if (value) {
                            document.body.classList.add('hide-header');
                        } else {
                            document.body.classList.remove('hide-header');
                        }
                    });
                },

                // Scroll helper: ensure the new step is visible at the top
                scrollToTopOfForm() {
                    this.$nextTick(() => {
                        // Delay slightly to wait for x-transition/DOM updates
                        setTimeout(() => {
                            const sc = this.$refs.formContainer;
                            try {
                                if (sc) {
                                    // If the container is scrollable, reset its internal scroll
                                    if (sc.scrollHeight > sc.clientHeight) {
                                        if (typeof sc.scrollTo === 'function') {
                                            sc.scrollTo({ top: 0, behavior: 'smooth' });
                                        } else {
                                            sc.scrollTop = 0;
                                        }
                                    } else {
                                        // Otherwise bring the container into viewport
                                        if (typeof sc.scrollIntoView === 'function') {
                                            sc.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                        } else {
                                            window.scrollTo({ top: 0, behavior: 'smooth' });
                                        }
                                    }
                                } else {
                                    window.scrollTo({ top: 0, behavior: 'smooth' });
                                }
                            } catch (e) {
                                // Fallback to instant jump
                                if (sc) sc.scrollTop = 0;
                                try { window.scrollTo(0,0); } catch (e) {}
                            }
                        }, 250);
                    });
                },

                nextStep() {
                    if (this.step < 4) {
                        this.step++;
                        this.scrollToTopOfForm();
                    }
                },

                prevStep() {
                    if (this.step > 1) {
                        this.step--;
                        this.scrollToTopOfForm();
                    }
                },

                goToDashboardHome() { window.location.href = '/webapp'; },
                getPropertyName() { const type = this.propertyTypes.find(t => t.id === this.formData.type); return type ? type.name : 'Bất động sản'; },
                isHouseType() { const type = this.propertyTypes.find(t => t.id === this.formData.type); return type ? type.isHouse : false; },
                getSelectedType() { return this.propertyTypes.find(t => t.id === this.formData.type) || this.propertyTypes[0]; },
                selectPropertyType(id) { this.formData.type = id; this.isTypeExpanded = false; },
                getFilteredParameters() {
                    if (!this.formData.type) return [];
                    const selectedType = this.propertyTypes.find(t => t.id === this.formData.type);
                    if (!selectedType || !selectedType.parameter_ids) return [];

                    // Lọc bỏ các tham số có tên chính xác là "Diện tích" hoặc "Pháp lý"
                    const excludedNames = ['Diện tích', 'Pháp lý','Giá m2'];

                    return this.parameters.filter(p =>
                        selectedType.parameter_ids.includes(p.id) &&
                        !excludedNames.includes(p.name)
                    );
                },
                getSelectedWard() { return this.wards.find(w => w.id === this.formData.ward) || { name: 'Chọn Khu vực', icon: 'fa-map' }; },
                selectWard(id) { this.formData.ward = id; this.isWardExpanded = false; },
                getSelectedLegal() { return this.legalTypes.find(l => l.value === this.formData.legal) || { name: 'Chọn loại giấy tờ', icon: 'fa-file' }; },
                selectLegal(value) { this.formData.legal = value; this.isLegalExpanded = false; },
                toggleAmenity(id) { if (id in this.formData.amenities) { let temp = {...this.formData.amenities}; delete temp[id]; this.formData.amenities = temp; } else { this.formData.amenities = { ...this.formData.amenities, [id]: '' }; } },
                isAmenitySelected(id) { return id in this.formData.amenities; },
                getAmenityIcon(am) {
                    if (!am || !am.image) return '';
                    
                    // Xác định đường dẫn cơ bản
                    let imageUrl = am.image;
                    if (!imageUrl.includes('http') && !imageUrl.startsWith('/')) {
                        imageUrl = '/images/facility_img/' + imageUrl;
                    }

                    // Nếu đang chọn, thay thế đuôi file
                    if (this.isAmenitySelected(am.id)) {
                        return imageUrl.replace(/\.svg$/i, '-white.svg');
                    }
                    
                    return imageUrl;
                },
                getAmenityImage(id) { const am = this.amenitiesList.find(a => a.id == id); return am ? am.image : ''; },
                getAmenityName(id) { const am = this.amenitiesList.find(a => a.id == id); return am ? am.name : id; },
                handlePriceInput(e) { let value = e.target.value.replace(/[^0-9]/g, ''); if (!value) value = '0'; this.price = parseInt(value); this.formattedPrice = new Intl.NumberFormat('vi-VN').format(this.price); this.priceInWords = this.readMoney(this.price); },
                addZeros() { this.price = this.price * 1000; this.formattedPrice = new Intl.NumberFormat('vi-VN').format(this.price); this.priceInWords = this.readMoney(this.price); },
                calculateCommission() { if(!this.price) return '0 VNĐ'; const commission = this.price * (this.formData.commissionRate / 100); return this.readMoney(commission); },
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
                pickerMarker: null,

                // Open fullscreen map picker
                openMapPicker() {
                    this.showMapPicker = true;
                    this.$nextTick(() => {
                        if (!window.google || !window.google.maps) {
                            alert("Google Maps API chưa tải xong. Vui lòng đợi hoặc tải lại trang.");
                            return;
                        }
                        if (!this.pickerMap) {
                            this.initGoogleMap();
                        } else {
                            // Resize map when modal opens
                            google.maps.event.trigger(this.pickerMap, 'resize');
                            const currentCenter = this.pickerMap.getCenter();
                            if(currentCenter) this.pickerMap.setCenter(currentCenter);
                        }
                    });
                },

                // Initialize Google Map inside picker
                initGoogleMap() {
                    console.log("Start initGoogleMap");
                    const defaultPos = { lat: 11.940419, lng: 108.458313 };

                    if (!document.getElementById("picker-map")) {
                        console.error("Map container #picker-map not found!");
                        return;
                    }

                    this.pickerMap = new google.maps.Map(document.getElementById("picker-map"), {
                        center: defaultPos,
                        zoom: 15,
                        disableDefaultUI: true,
                        clickableIcons: false,
                        gestureHandling: "greedy",
                    });

                    this.pickerMarker = new google.maps.Marker({
                        position: defaultPos,
                        map: this.pickerMap,
                        draggable: true,
                        animation: google.maps.Animation.DROP,
                        icon: {
                            url: "https://maps.gstatic.com/mapfiles/api-3/images/spotlight-poi2.png",
                            scaledSize: new google.maps.Size(27, 43)
                        }
                    });

                    console.log("Map instance created", this.pickerMap);
                    console.log("Marker created", this.pickerMarker);

                    this.pickerGeocoder = new google.maps.Geocoder();

                    // Sự kiện drag cho marker cũ
                    this.pickerMarker.addListener("dragend", (event) => {
                        this.pickerLat = event.latLng.lat();
                        this.pickerLng = event.latLng.lng();
                        this.reverseGeocode(event.latLng);
                    });

                    this.pickerMap.addListener("dragstart", () => {
                        this.isMapDragging = true;
                        this.pickerAddress = "Đang di chuyển...";
                    });

                    this.pickerMap.addListener("idle", () => {
                        this.isMapDragging = false;
                        const center = this.pickerMap.getCenter();
                        this.pickerLat = center.lat();
                        this.pickerLng = center.lng();
                        // Update marker position
                        this.pickerMarker.setPosition(center);
                        this.reverseGeocode(center);
                    });
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
                                    if (this.pickerMarker) {
                                        this.pickerMarker.position = pos;
                                    }
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
                selectStreet(id) {
                    console.log('selectStreet called with id:', id);
                    this.formData.street = id;

                    if (!this.showMapPicker) {
                        console.log('Map picker not open, skipping geocoding');
                        return;
                    }

                    if (!this.pickerMap) {
                        console.log('Map not initialized, skipping geocoding');
                        return;
                    }

                    if (!this.pickerGeocoder) {
                        console.log('Geocoder not initialized, skipping geocoding');
                        return;
                    }

                    const street = this.streets.find(s => s.id == id);
                    if (!street) {
                        console.log('Street not found for id:', id);
                        return;
                    }

                    console.log('Attempting to geocode street:', street.name);

                    // Try multiple address formats for better geocoding success
                    const addressFormats = [
                        street.name + ', Đà Lạt, Lâm Đồng, Vietnam',
                        street.name + ', Đà Lạt, Vietnam',
                        street.name + ', Da Lat, Vietnam',
                        street.name + ', Lam Dong, Vietnam'
                    ];

                    let geocodeAttempt = 0;

                    const tryGeocode = () => {
                        if (geocodeAttempt >= addressFormats.length) {
                            console.warn('All geocoding attempts failed for street:', street.name);
                            return;
                        }

                        const geocodeRequest = {
                            address: addressFormats[geocodeAttempt]
                        };

                        console.log('Geocoding attempt', geocodeAttempt + 1, 'with address:', geocodeRequest.address);

                        this.pickerGeocoder.geocode(geocodeRequest, (results, status) => {
                            if (status === 'OK' && results[0]) {
                                const pos = {
                                    lat: results[0].geometry.location.lat(),
                                    lng: results[0].geometry.location.lng()
                                };

                                console.log('Geocoding successful for', street.name, 'at position:', pos);

                                this.pickerMap.setCenter(pos);
                                this.pickerMap.setZoom(15);
                                if (this.pickerMarker) {
                                    this.pickerMarker.setPosition(pos);
                                }
                                this.pickerLat = pos.lat;
                                this.pickerLng = pos.lng;
                                this.reverseGeocode(pos);
                            } else {
                                console.warn('Geocoding attempt', geocodeAttempt + 1, 'failed for', street.name, 'with status:', status);
                                geocodeAttempt++;
                                tryGeocode(); // Try next format
                            }
                        });
                    };

                    tryGeocode();
                },
                updateMapLocation() { if(this.formData.street && this.formData.houseNumber) { const streetName = this.getStreetName(this.formData.street); this.locationText = `📍 Đã ghim: ${this.formData.houseNumber}, ${streetName}`; } },

                formatCurrency(number) {
                    if (!number) return '0 VNĐ';
                    if (number >= 1000000000) { return (number / 1000000000).toFixed(2).replace('.00', '') + ' Tỷ VNĐ'; }
                    if (number >= 1000000) { return (number / 1000000).toFixed(0) + ' Triệu VNĐ'; }
                    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(number);
                },

                async submitForm() {
                    // Basic validation
                    if (!this.formData.type) return alert("Vui lòng chọn loại bất động sản");
                    if (!this.formData.ward) return alert("Vui lòng chọn khu vực");
                    if (!this.formData.price) return alert("Vui lòng nhập giá");
                    if (!this.formData.area) return alert("Vui lòng nhập diện tích");
                    if (!this.images.avatar) return alert("Vui lòng chọn ảnh đại diện");

                    const submitBtn = document.querySelector('button[x-show="step === 4"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Đang xử lý...';

                    try {
                        const fd = new FormData();
                        fd.append('transactionType', this.formData.transactionType);
                        fd.append('type', this.formData.type);
                        fd.append('ward', this.formData.ward);
                        fd.append('street', this.formData.street || '');
                        fd.append('houseNumber', this.formData.houseNumber || '');
                        fd.append('price', this.formData.price);
                        fd.append('area', this.formData.area);
                        fd.append('commissionRate', this.formData.commissionRate);
                        fd.append('description', this.formData.description || '');
                        
                        fd.append('contact', JSON.stringify(this.formData.contact));
                        fd.append('parameters', JSON.stringify(this.formData.parameters));
                        fd.append('amenities', JSON.stringify(this.formData.amenities));
                        
                        if (this.pickerLat && this.pickerLng) {
                            fd.append('latitude', this.pickerLat);
                            fd.append('longitude', this.pickerLng);
                        }

                        if (this.images.avatar && this.images.avatar.file) {
                            fd.append('avatar', this.images.avatar.file);
                        }
                        
                        this.images.others.forEach((img) => {
                            if (img.file) fd.append('others[]', img.file);
                        });
                        
                        this.images.legal.forEach((img) => {
                            if (img.file) fd.append('legal[]', img.file);
                        });

                        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        
                        const response = await fetch("{{ route('webapp.submit_listing') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token || ''
                            },
                            body: fd
                        });

                        const result = await response.json();

                        if (result.success) {
                            window.location.href = result.redirect_url;
                        } else {
                            alert(result.message || 'Có lỗi xảy ra, vui lòng thử lại.');
                            if (result.errors) console.error(result.errors);
                        }
                    } catch (error) {
                        console.error(error);
                        alert('Lỗi kết nối: ' + error.message);
                    } finally {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                }
            }));
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
@endpush

@section('content')
    <div x-data="realEstateForm" class="flex items-start justify-center min-h-screen w-full py-2 ">
    <div x-ref="formContainer" class="w-full max-w-md bg-white shadow-2xl relative flex flex-col pb-24 rounded-xl overflow-hidden h-auto max-h-[90vh] overflow-y-auto">

        <!-- HEADER -->
        <div class="sticky top-0 z-49 bg-white/95 backdrop-blur-md border-b border-gray-100 px-6 py-5 shadow-sm">
            <div class="flex justify-between items-center mb-3">
                <h1 class="text-xl font-bold text-gray-800">Đăng Tin Mới</h1>
                <span class="text-xs font-bold text-primary bg-blue-50 px-3 py-1.5 rounded-full shadow-sm">Bước <span x-text="step"></span>/4</span>
            </div>
            <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-primary transition-all duration-500 ease-out shadow-sm" :style="'width: ' + (step/4)*100 + '%'" ></div>
            </div>
        </div>

        <!-- SCROLLABLE CONTENT -->
        <form class="flex-1 px-6 py-6 pb-40" @submit.prevent="submitForm">


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
                <!-- Thông tin chủ nhà (Căn giữa Radio) -->
                <div x-show="formData.transactionType" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                     class="border-2 border-dashed border-primary/30 rounded-xl p-4 text-center hover:bg-blue-50 transition-colors cursor-pointer bg-white group mb-6"
                     x-data="{
                         isEditing: false,
                         get isHasData() { return this.formData.contact.name && this.formData.contact.phone; },
                         init() { 
                             this.isEditing = !this.isHasData;
                             this.$watch(() => formData.transactionType, (val) => {
                                 if (val) {
                                     setTimeout(() => {
                                         if(this.$refs.contactName) {
                                             this.$refs.contactName.focus();
                                             this.$refs.contactName.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                         }
                                     }, 400);
                                 }
                             });
                         }
                     }"
                     @click.outside="if(isHasData) isEditing = false">
                    <h3 class="text-xs font-bold text-gray-500 mb-3 uppercase tracking-wide flex items-center justify-center">
                        <i class="fa-solid fa-user-tag mr-2 text-primary"></i> Liên hệ bán
                    </h3>


                    <!-- VIEW MODE: Label (Chỉ hiện khi không edit và đã có data) -->
                    <div x-show="!isEditing && isHasData"
                         @click="isEditing = true"
                         class="py-4 px-2 bg-blue-50 rounded-lg border border-blue-100 cursor-pointer hover:bg-blue-100 transition shadow-sm animate-fade-in-up flex flex-col items-center justify-center">
                        <p class="text-lg font-bold text-primary text-center">
                            <span x-text="formData.contact.gender === 'ong' ? 'Ông' : 'Bà'"></span>
                            <span x-text="formData.contact.name"></span>
                            <span> - </span>
                            <span class="text-gray-500">*******<span x-text="formData.contact.phone ? formData.contact.phone.slice(-3) : ''"></span></span>
                        </p>
                    </div>

                    <!-- EDIT MODE: Form (Hiện khi đang edit hoặc chưa có data) -->
                    <div x-show="isEditing || !isHasData" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
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
                            <input type="text" x-ref="contactName" @focus="$el.scrollIntoView({ behavior: 'smooth', block: 'center' })" x-model="formData.contact.name" placeholder="Họ và tên" class="input-field ">
                            <div class="relative group">
                                <input type="tel"
                                       @focus="$el.scrollIntoView({ behavior: 'smooth', block: 'center' })"
                                       x-model="formData.contact.phone"
                                       placeholder="Số điện thoại"
                                       class="input-field pl-10 border-green-200 focus:border-green-500 focus:ring-green-200 bg-green-50/30">

                                <div class="relative -bottom-5 left-0 text-green-600 font-medium flex items-center opacity-100 transition-opacity" x-show="formData.contact.phone">
                                    <i class="fa-solid fa-shield-halved mr-1"></i> Thông tin này được bảo mật.
                                </div>
                            </div>
                            <textarea @focus="$el.scrollIntoView({ behavior: 'smooth', block: 'center' })" x-model="formData.contact.note" placeholder="Ghi chú (Gọi giờ hành chính...)" class="input-field h-20 resize-none"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Loại BĐS - Collapsible Logic -->
                <div class="mb-6" x-show="formData.transactionType && formData.contact.name && formData.contact.phone" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
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
                <div class="mb-6 space-y-4" x-show="formData.transactionType && formData.contact.name && formData.contact.phone && formData.type" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
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
                                        ? 'bg-primary text-white border-primary shadow-lg shadow-blue-200 transform scale-105'
                                        : 'bg-white text-primary border-gray-200 hover:bg-blue-50 hover:border-blue-100'"
                                    class="flex flex-col items-center justify-center p-2 border rounded-xl transition-all duration-200 aspect-[4/3] group">
                                    <i :class="['fa-solid', ward.icon, 'text-lg mb-1 group-hover:scale-110 transition-transform']"></i>
                                    <span class="text-xs font-medium text-center leading-tight" x-text="ward.name"></span>
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
                    <!-- Số nhà -->
                    {{-- <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5 text-left">Số nhà</label>
                        <input type="text" x-model="formData.houseNumber" @input="updateMapLocation" placeholder="VD: 123/4" class="input-field">
                    </div> --}}
                    <!-- Google Map Preview -->
                    <div x-show="formData.ward" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white p-3 rounded-2xl border border-gray-200 shadow-sm">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-sm font-bold text-gray-700">📍 Vị trí trên bản đồ</label>
                            <button type="button" @click="panToCurrentLocation" class="text-xs text-primary font-bold flex items-center bg-blue-50 px-2 py-1 rounded">
                                <i class="fa-solid fa-crosshairs mr-1"></i> Vị trí của tôi
                            </button>
                        </div>
                        <div id="map-preview" @click="openMapPicker" class="w-full h-40 bg-gray-100 rounded-xl relative overflow-hidden flex items-center justify-center cursor-pointer border border-dashed border-gray-300 group hover:border-primary transition-colors">
                            <div class="absolute inset-0 bg-cover bg-center opacity-60" style="background-image: url('https://upload.wikimedia.org/wikipedia/commons/e/ec/Map_of_Dalat.jpg');"></div>
                            <span class="bg-white/90 px-4 py-2 rounded-full text-xs font-bold shadow-sm backdrop-blur text-gray-700 border border-gray-200 group-hover:text-primary group-hover:scale-105 transition-all">
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
                
                <!-- Giấy tờ -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-800 mb-3 flex justify-between items-center">
                        Loại giấy tờ
                        <button type="button" x-show="!isLegalExpanded" @click="isLegalExpanded = true" class="text-xs font-normal text-primary hover:underline">
                            Thay đổi
                        </button>
                    </label>

                    <!-- STATE 1: DANH SÁCH MỞ RỘNG -->
                    <div x-show="isLegalExpanded" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="grid grid-cols-3 gap-3">
                        <template x-for="legal in legalTypes" :key="legal.value">
                            <button type="button"
                                @click="selectLegal(legal.value)"
                                :class="formData.legal === legal.value
                                    ? 'bg-primary text-white border-primary shadow-lg shadow-blue-200 transform scale-105'
                                    : 'bg-white text-primary border-gray-200 hover:bg-blue-50 hover:border-blue-100'"
                                class="flex flex-col items-center justify-center p-3 border rounded-xl transition-all duration-200 aspect-square">
                                <i :class="['fa-solid', legal.icon, 'text-xl mb-2']"></i>
                                <span class="text-xs font-medium text-center leading-tight" x-text="legal.name"></span>
                            </button>
                        </template>
                    </div>

                    <!-- STATE 2: ĐÃ CHỌN (Thu gọn) -->
                    <div x-show="!isLegalExpanded" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                        <div @click="isLegalExpanded = true" class="bg-primary text-white border-primary shadow-lg shadow-blue-200 p-4 rounded-xl flex items-center justify-between cursor-pointer hover:bg-blue-600 transition-colors group">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                    <i :class="['fa-solid', getSelectedLegal().icon, 'text-lg']"></i>
                                </div>
                                <div class="flex flex-col text-left">
                                    <span class="text-xs text-blue-100 font-medium">Đã chọn loại:</span>
                                    <span class="font-bold text-lg leading-tight" x-text="getSelectedLegal().name"></span>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-down text-white/70 group-hover:translate-y-1 transition-transform"></i>
                        </div>
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
                <!-- Mô tả -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 text-left">Mô tả chi tiết</label>
                    <textarea x-model="formData.description" class="input-field h-32 resize-none" placeholder="Mô tả về đường đi, view, nội thất, tiện ích..."></textarea>
                </div>
                <!-- Upload Ảnh -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-gray-800 border-l-4 border-primary pl-2 text-left">Hình ảnh & Giấy tờ</h3>

                    <!-- Hidden Inputs -->
                    <input type="file" x-ref="avatarInput" class="hidden" accept="image/png, image/jpeg, image/gif, image/webp" @change="handleImageUpload($event, 'avatar')">
                    <input type="file" x-ref="legalInput" class="hidden" multiple accept="image/png, image/jpeg, image/gif, image/webp" @change="handleImageUpload($event, 'legal')">
                    <input type="file" x-ref="othersInput" class="hidden" multiple accept="image/png, image/jpeg, image/gif, image/webp" @change="handleImageUpload($event, 'others')">

                    <!-- Ảnh chính (Single) -->
                    <div>
                        <!-- State 1: Chưa chọn ảnh -->
                        <div x-show="!images.avatar" @click="$refs.avatarInput.click()" class="border-2 border-dashed border-primary/30 rounded-xl p-4 text-center hover:bg-blue-50 transition-colors cursor-pointer bg-white group h-48 flex flex-col items-center justify-center">
                            <div class="w-12 h-12 bg-blue-100 text-primary rounded-full flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-camera text-xl"></i>
                            </div>
                            <p class="text-sm font-bold text-gray-700 text-center">Ảnh đại diện</p>
                            <p class="text-xs text-gray-400 text-center">Bắt buộc 1 tấm đẹp nhất</p>
                        </div>

                        <!-- State 2: Đã chọn ảnh (Preview) -->
                        <div x-show="images.avatar" class="relative border-2 border-primary rounded-xl p-2 bg-white h-full">
                            <img :src="images.avatar?.preview" class="w-full h-48 object-cover rounded-lg mb-2 bg-gray-100">
                            <div class="flex justify-between items-center px-1">
                                <div class="text-xs font-bold text-gray-700 truncate max-w-[200px]" x-text="images.avatar?.name"></div>
                                <div class="text-[10px] font-bold text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded" x-text="images.avatar?.size"></div>
                            </div>
                            <button type="button" @click="removeImage('avatar')" class="absolute top-4 right-4 bg-white/90 text-red-500 rounded-full w-8 h-8 flex items-center justify-center shadow-md hover:scale-110 transition-transform hover:bg-red-50">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Ảnh giấy tờ (Multi) -->
                        <div>
                            <!-- Empty State -->
                            <div x-show="images.legal.length === 0" @click="$refs.legalInput.click()" class="border-2 border-dashed border-[#16a34a] rounded-xl p-4 text-center hover:bg-green-50 transition-colors cursor-pointer bg-white group h-full min-h-[144px] flex flex-col items-center justify-center">
                                <div class="w-10 h-10 bg-green-100 text-[#16a34a] group-hover:bg-green-200 rounded-full flex items-center justify-center mx-auto mb-2 transition-colors">
                                    <i class="fa-solid fa-file-shield text-lg"></i>
                                </div>
                                <p class="text-xs font-bold text-[#16a34a] text-center">Sổ đỏ/Pháp lý</p>
                                <p class="text-[10px] text-[#16a34a] font-bold text-center"><i class="fa-solid fa-lock mr-1"></i>Bảo mật (Max 10)</p>
                            </div>

                            <!-- List State -->
                            <div x-show="images.legal.length > 0" class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold text-gray-700">Pháp lý (<span x-text="images.legal.length"></span>/10)</span>
                                    <button type="button" x-show="images.legal.length < 10" @click="$refs.legalInput.click()" class="text-[10px] bg-blue-50 text-primary px-2 py-1 rounded font-bold hover:bg-blue-100">
                                        <i class="fa-solid fa-plus mr-1"></i>Thêm
                                    </button>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <template x-for="(img, index) in images.legal" :key="img.id">
                                        <div class="relative border border-gray-200 rounded-lg p-1 bg-white group">
                                            <img :src="img.preview" class="w-full h-16 object-cover rounded bg-gray-50">
                                            <div class="mt-1 flex justify-between items-center overflow-hidden">
                                                 <span class="text-[9px] text-gray-500 truncate w-full" x-text="img.name"></span>
                                            </div>
                                            <!-- Remove Button -->
                                            <button type="button" @click="removeImage('legal', index)" class="absolute top-4 right-4 bg-white/90 text-red-500 rounded-full w-8 h-8 flex items-center justify-center shadow-md hover:scale-110 transition-transform hover:bg-red-50">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                            <!-- Progress Bar (Visual only for now) -->
                                            <div class="h-0.5 w-full bg-gray-100 mt-1 rounded-full overflow-hidden" x-show="img.status === 'uploading'">
                                                <div class="h-full bg-blue-500" :style="`width: ${img.progress}%`"></div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Ảnh khác (Multi) -->
                        <div>
                            <!-- Empty State -->
                            <div x-show="images.others.length === 0" @click="$refs.othersInput.click()" class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center hover:bg-gray-50 transition-colors cursor-pointer bg-white group h-full min-h-[144px] flex flex-col items-center justify-center">
                                <div class="w-10 h-10 bg-gray-100 text-gray-500 group-hover:text-primary group-hover:bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-2 transition-colors">
                                    <i class="fa-regular fa-images text-lg"></i>
                                </div>
                                <p class="text-xs font-bold text-gray-700 text-center">Ảnh khác</p>
                                <p class="text-[10px] text-gray-400 text-center">Nội thất... (Max 10)</p>
                            </div>

                            <!-- List State -->
                            <div x-show="images.others.length > 0" class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold text-gray-700">Ảnh khác (<span x-text="images.others.length"></span>/10)</span>
                                    <button type="button" x-show="images.others.length < 10" @click="$refs.othersInput.click()" class="text-[10px] bg-blue-50 text-primary px-2 py-1 rounded font-bold hover:bg-blue-100">
                                        <i class="fa-solid fa-plus mr-1"></i>Thêm
                                    </button>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <template x-for="(img, index) in images.others" :key="img.id">
                                        <div class="relative border border-gray-200 rounded-lg p-1 bg-white group">
                                            <img :src="img.preview" class="w-full h-16 object-cover rounded bg-gray-50">
                                            <div class="mt-1 flex justify-between items-center overflow-hidden">
                                                 <span class="text-[9px] text-gray-500 truncate w-full" x-text="img.name"></span>
                                            </div>
                                            <button type="button" @click="removeImage('others', index)" class="absolute top-4 right-4 bg-white/90 text-red-500 rounded-full w-8 h-8 flex items-center justify-center shadow-md hover:scale-110 transition-transform hover:bg-red-50">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
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


                <!-- DYNAMIC PARAMETERS BASED ON PROPERTY TYPE -->
                <div class="space-y-6" x-show="getFilteredParameters().length > 0">
                    <template x-for="param in getFilteredParameters()" :key="param.id">
                        <div class="relative group">
                            <label class="block text-left text-xs font-bold mb-1 tracking-wide"
                                  x-text="param.type_of_parameter === 'number' ? (param.name + (param.name.includes('Đường rộng') ? '(m)' : (param.name.includes('Số tầng') ? ' (tầng)' : (param.name.includes('Phòng ngủ') ? ' (số phòng)' : '')))) : param.name">
                            </label>
                            <!-- NUMBER INPUT -->
                            <template x-if="param.type_of_parameter === 'number'">
                                <div class="flex items-center justify-between bg-white border border-gray-200 rounded-xl p-1">
                                    <button type="button"
                                        @click="let val = parseInt(formData.parameters[param.id] || 0); if(val > 0) formData.parameters[param.id] = val - 1;"
                                        class="btn-counter flex-shrink-0">
                                        <i class="fa-solid fa-minus"></i>
                                    </button>

                                    {{-- Replaced the inner div (span + span) with a proper input for direct typing --}}
                                    <div class="relative flex-1 h-full flex items-center">
                                        <input type="number"
                                            x-model.number="formData.parameters[param.id]"
                                            @change="if (formData.parameters[param.id] < 0 || formData.parameters[param.id] === null || formData.parameters[param.id] === '') formData.parameters[param.id] = 0"
                                            min="0"
                                            placeholder="0"
                                            class="w-full text-center font-bold text-lg text-gray-800 border-none bg-transparent focus:ring-0 p-0 m-0"
                                            style="padding-right: 35px;"
                                        >
                                        {{-- Unit display, reusing original logic --}}
                                        <span class="absolute right-2 top-1/2 -translate-y-1/2 font-bold text-sm text-gray-400 pointer-events-none"
                                            x-text="param.type_values ? '' : ''"></span>
                                    </div>

                                    <button type="button"
                                        @click="let val = parseInt(formData.parameters[param.id] || 0); formData.parameters[param.id] = val + 1;"
                                        class="btn-counter flex-shrink-0">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>
                            </template>

                            <!-- RADIOBUTTON -->
                            <template x-if="param.type_of_parameter === 'radiobutton'">
                                <div class="grid grid-cols-2 gap-3 p-1 bg-gray-100 rounded-xl">
                                    <template x-for="option in param.type_values" :key="option">
                                        <button type="button"
                                            @click="formData.parameters[param.id] = option"
                                            :class="formData.parameters[param.id] === option ? 'bg-primary text-white shadow-md' : 'text-gray-500 hover:text-primary'"
                                            class="py-3 px-4 rounded-lg text-sm font-bold transition-all flex items-center justify-center"
                                            x-text="option">
                                        </button>
                                    </template>
                                </div>
                            </template>

                            <!-- CHECKBOX -->
                            <template x-if="param.type_of_parameter === 'checkbox'">
                                <div class="space-y-2">
                                    <template x-for="option in param.type_values" :key="option">
                                        <label class="flex items-center space-x-3 cursor-pointer p-2 hover:bg-gray-50 rounded-lg">
                                            <input type="checkbox"
                                                   :value="option"
                                                   x-model="formData.parameters[param.id]"
                                                   class="text-primary focus:ring-primary h-4 w-4">
                                            <span class="text-sm font-bold text-gray-700" x-text="option"></span>
                                        </label>
                                    </template>
                                </div>
                            </template>

                            <!-- DROPDOWN (Button Style) -->
                            <template x-if="param.type_of_parameter === 'dropdown'">
                                <div x-data="{
                                    isExpanded: !formData.parameters[param.id],
                                    init() {
                                        this.$watch(() => formData.parameters[param.id], value => {
                                            if (!value) this.isExpanded = true;
                                        });
                                    }
                                }" class="w-full">

                                    <!-- STATE 1: LIST EXPANDED -->
                                    <div x-show="isExpanded"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        class="grid grid-cols-4 gap-2"> <template x-for="option in param.type_values" :key="option">
                                            <button type="button"
                                                @click="formData.parameters[param.id] = option; isExpanded = false"
                                                :class="formData.parameters[param.id] === option
                                                    ? 'bg-primary text-white border-primary shadow-lg shadow-blue-200 transform scale-105 z-10'
                                                    : 'bg-white text-primary border-gray-200 hover:bg-blue-50 hover:border-blue-100'"
                                                class="py-2 px-1 border rounded-xl text-[10px] sm:text-xs font-bold transition-all duration-200 flex flex-col items-center justify-center text-center leading-tight min-h-[60px]">
                                                
                                                <i class="fa-solid fa-compass text-lg mb-1"></i>
                                                <span x-text="option" class="break-words w-full"></span>
                                            </button>
                                        </template>
                                    </div>

                                    <!-- STATE 2: COLLAPSED (SELECTED) -->
                                    <div x-show="!isExpanded"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 translate-y-2"
                                         x-transition:enter-end="opacity-100 translate-y-0">
                                        <div @click="isExpanded = true"
                                             class="bg-primary text-white border-primary shadow-lg shadow-blue-200 p-3 rounded-xl flex items-center justify-between cursor-pointer hover:bg-blue-600 transition-colors group">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                                    <i class="fa-solid fa-check text-xs"></i>
                                                </div>
                                                <div class="flex flex-col text-left">
                                                    <span class="text-[10px] text-blue-100 font-medium">Đã chọn:</span>
                                                    <span class="font-bold text-sm leading-tight" x-text="formData.parameters[param.id]"></span>
                                                </div>
                                            </div>
                                            <div class="flex items-center">
                                                <span class="text-[10px] text-blue-100 mr-2 group-hover:underline">Thay đổi</span>
                                                <i class="fa-solid fa-chevron-down text-white/70 group-hover:translate-y-1 transition-transform"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>

                <!-- NO PARAMETERS MESSAGE -->
                <div x-show="getFilteredParameters().length === 0" class="py-10 text-center text-gray-400 border-2 border-dashed border-gray-100 rounded-xl">
                    <i class="fa-solid fa-cogs text-4xl mb-2 text-gray-200"></i>
                    <p class="text-sm text-center">Không có thông số kỹ thuật cho loại BĐS này</p>
                </div>
            </div>

            <!-- === BƯỚC 4: TIỆN ÍCH XUNG QUANH (LOGIC MỚI - GRID BUTTON) === -->
            <div x-show="step === 4" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                
                <p class="text-sm text-gray-500 mb-6 text-center">Chọn các địa điểm gần BĐS của bạn.</p>

                <!-- GRID TIỆN ÍCH (4 Cột) -->

                <div class="grid grid-cols-4 gap-2 mb-6">
                    <!-- Loop items: Show all if expanded or count <= 8. Else show first 7. -->

                    <template x-for="am in (isAmenityExpanded || amenitiesList.length <= 8 ? amenitiesList : amenitiesList.slice(0, 7))" :key="am.id">
                        <button type="button"
                            @click="toggleAmenity(am.id)"
                            :class="isAmenitySelected(am.id)
                                ? 'bg-primary text-white border-primary shadow-md transform scale-[1.05]'
                                : 'bg-white text-primary border-gray-100 hover:bg-blue-50 hover:border-blue-100 shadow-sm'"
                            class="flex flex-col items-center justify-center p-2 border rounded-xl transition-all duration-200 aspect-square animate-fade-in-up">
                            <img :src="getAmenityIcon(am)" :alt="am.name" class="w-8 h-8 object-contain mb-1.5">
                            <span class="text-[9px] font-bold text-center leading-tight truncate w-full px-1" x-text="am.name"></span>
                        </button>
                    </template>
                    

                    <!-- View More Button (7 items displayed + 1 button = 8 slots) -->
                    <button x-show="!isAmenityExpanded && amenitiesList.length > 8"
                            type="button"
                            @click="isAmenityExpanded = true"
                            class="flex flex-col items-center justify-center p-2 border border-dashed border-primary/40 bg-blue-50/50 text-primary rounded-xl hover:bg-blue-50 transition-all aspect-square group">
                        <div class="w-8 h-8 flex items-center justify-center rounded-full bg-white shadow-sm mb-1 group-hover:scale-110 transition-transform">
                             <i class="fa-solid fa-plus text-sm"></i>
                        </div>

                        <span class="text-[9px] font-bold text-center leading-tight">Xem thêm</span>
                    </button>
                </div>

                <!-- Thu gọn Button -->
                <div x-show="isAmenityExpanded && amenitiesList.length > 8" class="flex justify-center -mt-4 mb-6">
                    <button type="button" @click="isAmenityExpanded = false" class="text-xs text-gray-400 hover:text-primary flex items-center bg-gray-50 px-3 py-1.5 rounded-full border border-gray-200 shadow-sm transition-colors">
                        <i class="fa-solid fa-chevron-up mr-1.5"></i> Thu gọn
                    </button>
                </div>

                <!-- LIST INPUT KHOẢNG CÁCH (Chỉ hiện cái đã chọn) -->
                <div class="space-y-3" x-show="Object.keys(formData.amenities).length > 0" x-transition>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Nhập khoảng cách (km)</h3>

                    <template x-for="(dist, id) in formData.amenities" :key="id">
                        <div x-data="{ am: amenitiesList.find(a => a.id == id) }" class="flex items-center bg-white border border-gray-200 rounded-xl p-2 pr-4 shadow-sm animate-fade-in-up">
                            <!-- Icon & Name -->
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-primary flex items-center justify-center mr-3 flex-shrink-0 p-1">
                                <img :src="am && am.image && am.image.includes('http') ? am.image : (am ? '/images/facility_img/' + am.image : '')" class="w-full h-full object-contain">
                            </div>
                            <div class="flex-1 mr-3">
                                <p class="text-xs font-bold text-gray-500 uppercase" x-text="am ? am.name : ''"></p>
                                <p class="text-sm font-bold text-gray-800">Cách bao xa?</p>
                            </div>
                            <!-- Input -->
                            <div class="relative w-24">
                                <input type="number" :name="'facilities[' + id + '][distance]'" x-model="formData.amenities[id]" class="w-full bg-gray-50 border border-gray-200 rounded-lg py-1.5 pl-2 pr-6 text-right font-bold text-gray-800 text-sm focus:border-primary outline-none" placeholder="0">
                                <span class="absolute right-2 top-2 text-xs text-gray-400 ">km</span>
                            </div>
                            <!-- Remove Btn -->
                            <button type="button" @click="toggleAmenity(id)" class="ml-3 text-gray-300 hover:text-red-500">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </template>
                </div>

                <div x-show="Object.keys(formData.amenities).length === 0" class="py-10 text-center text-gray-400 border-2 border-dashed border-gray-100 rounded-xl">
                    <i class="fa-solid fa-map-location-dot text-4xl mb-2 text-gray-200"></i>
                    <p class="text-xs text-center">Chưa chọn tiện ích nào</p>
                </div>
            </div>

        </form>

        <!-- FOOTER: FIXED BOTTOM NAVIGATION -->

        <div id="floating-footer" class="fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-gray-100 shadow-[0_-4px_20px_rgba(0,0,0,0.05)] z-50 flex justify-center">
            <div class="w-full max-w-md flex justify-between gap-3">
                <!-- Nút Quay lại -->
                <button type="button" x-show="step >= 1" @click="step === 1 ? goToDashboardHome() : prevStep()"
                    class="flex-1 bg-gray-100 text-gray-600 px-4. py-3.5 rounded-xl font-bold text-sm hover:bg-gray-200 transition-colors">
                    Quay lại
                </button>

                <!-- Nút Tiếp tục -->
                <button type="button" x-show="step < 4" @click="nextStep"
                    class="flex-[2] bg-primary text-white px-6 py-3.5 rounded-xl font-bold text-sm shadow-lg shadow-blue-200 hover:bg-blue-600 transition-transform transform active:scale-[0.98] flex justify-center items-center">
                    Tiếp tục <i class="fa-solid fa-arrow-right ml-2"></i>
                </button>

                <!-- Nút Hoàn tất -->
                <button type="button" x-show="step === 4" @click="submitForm"
                    class="flex-[2] bg-success text-white px-6 py-3.5 rounded-xl font-bold text-sm shadow-lg shadow-green-200 hover:bg-green-600 transition-transform transform active:scale-[0.98] flex justify-center items-center">
                    Đăng Tin <i class="fa-solid fa-paper-plane ml-2"></i>
                </button>
            </div>
        </div>

    </div>

    <!-- Map Picker Modal -->
    <div x-show="showMapPicker" x-cloak
         class="fixed inset-0 z-[100] bg-black/50 flex justify-center"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl h-[80vh] flex flex-col overflow-hidden">

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

            <div class="flex gap-3">
                <button type="button" @click="showMapPicker = false"
                    class="flex-1 bg-gray-100 text-gray-600 px-4 py-3.5 rounded-xl font-bold text-sm hover:bg-gray-200 transition-colors">
                    Quay lại
                </button>

                <button @click="confirmMapLocation"
                        :disabled="!pickerLat || isMapDragging || !formData.street"
                        :class="(!pickerLat || isMapDragging || !formData.street) ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-primary text-white shadow-lg shadow-blue-200 active:scale-[0.98]'"
                        class="flex-[2] py-3.5 rounded-xl font-bold text-sm transition-all flex items-center justify-center">
                    <span x-show="!isMapDragging && formData.street">Xác nhận vị trí này</span>
                    <span x-show="!isMapDragging && !formData.street">Vui lòng chọn đường</span>
                    <span x-show="isMapDragging"><i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Đang tải...</span>
                </button>
            </div>
        </div>

        <div class="absolute top-0 left-0 right-0 z-50 p-4 pt-safe-top">
            <div class="flex items-center gap-3 pointer-events-auto">
                <button @click="showMapPicker = false" style="pointer-events: auto !important; z-index: 1000003;" class="w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center text-gray-600 hover:text-primary active:scale-95 transition-transform relative pointer-events-auto">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>

                <div class="flex-1 z-50 pointer-events-auto shadow-lg">
                    <select id="select-street" x-model="formData.street"
                            data-z-index="1000003"
                            x-init="$watch('showMapPicker', (value) => {
                                if (value) {
                                    $nextTick(() => {
                                        if (!$el.tomselect) {
                                            new TomSelect($el, {
                                                create: false,
                                                sortField: { field: 'text', direction: 'asc' },
                                                plugins: ['dropdown_input'],
                                                maxOptions: null,
                                                dropdownParent: document.body,
                                                onChange: (value) => { selectStreet(value); }
                                            });
                                        }
                                    });
                                }
                            })"
                            placeholder="Tìm tên đường..." autocomplete="off">
                        <option value="">Chọn đường...</option>
                        <template x-for="st in streets" :key="st.id">
                            <option :value="st.id" x-text="st.name"></option>
                        </template>
                    </select>
                </div>
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
