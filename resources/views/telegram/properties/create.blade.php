<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Đăng Tin BĐS</title>
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: var(--tg-theme-bg-color, #f5f5f5);
            color: var(--tg-theme-text-color, #000000);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            padding-bottom: 80px;
        }
        .container {
            padding-top: 20px;
        }
        .card {
            background-color: var(--tg-theme-secondary-bg-color, #ffffff);
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 16px;
        }
        .form-label {
            font-weight: 500;
            color: var(--tg-theme-hint-color, #6c757d);
        }
        .form-control, .form-select {
            background-color: var(--tg-theme-bg-color, #ffffff);
            color: var(--tg-theme-text-color, #000000);
            border: 1px solid #ced4da;
            border-radius: 8px;
            padding: 10px;
        }
        .step-container {
            display: none;
        }
        .step-container.active {
            display: block;
        }
        .btn-main {
            background-color: var(--tg-theme-button-color, #3390ec);
            color: var(--tg-theme-button-text-color, #ffffff);
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            border: none;
        }
        .btn-secondary-tg {
            background-color: transparent;
            color: var(--tg-theme-button-color, #3390ec);
            border: 1px solid var(--tg-theme-button-color, #3390ec);
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 10px;
        }
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 0 10px;
        }
        .step-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #e0e0e0;
        }
        .step-dot.active {
            background-color: var(--tg-theme-button-color, #3390ec);
        }
        .step-dot.completed {
            background-color: var(--tg-theme-button-color, #3390ec);
            opacity: 0.5;
        }
        .preview-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h4 class="mb-4 text-center">Đăng Tin BĐS Mới</h4>
        
        <div class="step-indicator">
            <div class="step-dot active" id="dot-1"></div>
            <div class="step-dot" id="dot-2"></div>
            <div class="step-dot" id="dot-3"></div>
            <div class="step-dot" id="dot-4"></div>
        </div>

        <form id="propertyForm">
            <!-- Step 1: Cơ bản -->
            <div class="step-container active" id="step-1">
                <div class="card p-3">
                    <div class="mb-3">
                        <label class="form-label">Tiêu đề tin</label>
                        <input type="text" class="form-control" name="title" required placeholder="VD: Bán đất nền KQH...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Danh mục</label>
                        <select class="form-select" name="category_id" required>
                            <option value="">Chọn danh mục</option>
                            <!-- Options will be loaded via API -->
                            <option value="1">Đất nền</option>
                            <option value="2">Nhà phố</option>
                            <option value="3">Biệt thự</option>
                            <option value="4">Căn hộ</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Loại tin</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="property_type" value="0" checked>
                                <label class="form-check-label">Bán</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="property_type" value="1">
                                <label class="form-check-label">Cho thuê</label>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-main" onclick="nextStep(2)">Tiếp tục</button>
            </div>

            <!-- Step 2: Giá & Vị trí -->
            <div class="step-container" id="step-2">
                <div class="card p-3">
                    <div class="mb-3">
                        <label class="form-label">Mức giá (VNĐ)</label>
                        <input type="number" class="form-control" name="price" required placeholder="Nhập giá bán/thuê">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Địa chỉ chi tiết</label>
                        <textarea class="form-control" name="address" rows="2" required placeholder="Số nhà, đường, phường..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Diện tích (m2)</label>
                        <input type="number" class="form-control" name="area" placeholder="VD: 100">
                    </div>
                </div>
                <button type="button" class="btn-main" onclick="nextStep(3)">Tiếp tục</button>
                <button type="button" class="btn-secondary-tg" onclick="prevStep(1)">Quay lại</button>
            </div>

            <!-- Step 3: Hình ảnh & Mô tả -->
            <div class="step-container" id="step-3">
                <div class="card p-3">
                    <div class="mb-3">
                        <label class="form-label">Hình ảnh đại diện</label>
                        <input type="file" class="form-control" id="imageInput" accept="image/*">
                        <img id="imagePreview" class="preview-image mt-2">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả chi tiết</label>
                        <textarea class="form-control" name="description" rows="5" required placeholder="Mô tả chi tiết về BĐS..."></textarea>
                    </div>
                </div>
                <button type="button" class="btn-main" onclick="nextStep(4)">Xem lại</button>
                <button type="button" class="btn-secondary-tg" onclick="prevStep(2)">Quay lại</button>
            </div>

            <!-- Step 4: Xác nhận -->
            <div class="step-container" id="step-4">
                <div class="card p-3">
                    <h5 class="mb-3">Xác nhận thông tin</h5>
                    <div id="summaryContent"></div>
                </div>
                <button type="button" class="btn-main" onclick="submitForm()">Đăng Tin</button>
                <button type="button" class="btn-secondary-tg" onclick="prevStep(3)">Chỉnh sửa</button>
            </div>
        </form>
    </div>

    <script>
        const tg = window.Telegram.WebApp;
        tg.expand();

        let currentStep = 1;
        const totalSteps = 4;
        const formData = {};

        function updateDots() {
            for (let i = 1; i <= totalSteps; i++) {
                const dot = document.getElementById(`dot-${i}`);
                if (i < currentStep) {
                    dot.className = 'step-dot completed';
                } else if (i === currentStep) {
                    dot.className = 'step-dot active';
                } else {
                    dot.className = 'step-dot';
                }
            }
        }

        function nextStep(step) {
            // Simple validation
            const currentContainer = document.getElementById(`step-${currentStep}`);
            const inputs = currentContainer.querySelectorAll('input[required], select[required], textarea[required]');
            let valid = true;
            inputs.forEach(input => {
                if (!input.value) {
                    input.classList.add('is-invalid');
                    valid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (!valid) {
                tg.showAlert("Vui lòng điền đầy đủ thông tin bắt buộc.");
                return;
            }

            // Save data
            const formElements = document.getElementById('propertyForm').elements;
            for (let i = 0; i < formElements.length; i++) {
                if (formElements[i].name) {
                    formData[formElements[i].name] = formElements[i].value;
                }
            }

            // Update UI
            document.getElementById(`step-${currentStep}`).classList.remove('active');
            currentStep = step;
            document.getElementById(`step-${currentStep}`).classList.add('active');
            updateDots();

            if (step === 4) {
                renderSummary();
            }
        }

        function prevStep(step) {
            document.getElementById(`step-${currentStep}`).classList.remove('active');
            currentStep = step;
            document.getElementById(`step-${currentStep}`).classList.add('active');
            updateDots();
        }

        // Image Preview
        document.getElementById('imageInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('imagePreview');
                    img.src = e.target.result;
                    img.style.display = 'block';
                    formData['image_file'] = file; // Store file object
                }
                reader.readAsDataURL(file);
            }
        });

        function renderSummary() {
            const summary = document.getElementById('summaryContent');
            summary.innerHTML = `
                <p><strong>Tiêu đề:</strong> ${formData.title}</p>
                <p><strong>Giá:</strong> ${new Intl.NumberFormat('vi-VN').format(formData.price)} VNĐ</p>
                <p><strong>Địa chỉ:</strong> ${formData.address}</p>
                <p><strong>Loại:</strong> ${formData.property_type == 0 ? 'Bán' : 'Cho thuê'}</p>
            `;
        }

        function submitForm() {
            tg.MainButton.showProgress();
            
            // Prepare FormData for API
            const apiData = new FormData();
            for (const key in formData) {
                if (key !== 'image_file') {
                    apiData.append(key, formData[key]);
                }
            }
            
            const fileInput = document.getElementById('imageInput');
            if (fileInput.files[0]) {
                apiData.append('title_image', fileInput.files[0]);
            }

            // Get User Data from Telegram InitData
            // In real app, we verify initData on backend. For demo, we assume session or token.
            // Here we assume the WebApp is opened with a session or we pass initData.
            apiData.append('tg_init_data', tg.initData);

            fetch('/api/properties', {
                method: 'POST',
                headers: {
                    // 'Authorization': 'Bearer ' + userToken, // Handle Auth
                    // Content-Type header should be left out for FormData to set boundary
                    'Accept': 'application/json'
                },
                body: apiData
            })
            .then(response => response.json())
            .then(data => {
                tg.MainButton.hideProgress();
                if (!data.error) {
                    tg.showAlert("Đăng tin thành công! Tin của bạn đang chờ duyệt.", function() {
                        tg.close();
                    });
                } else {
                    tg.showAlert("Lỗi: " + data.message);
                }
            })
            .catch(error => {
                tg.MainButton.hideProgress();
                tg.showAlert("Có lỗi xảy ra khi kết nối server.");
                console.error(error);
            });
        }

        // Initialize
        tg.ready();
    </script>
</body>
</html>
