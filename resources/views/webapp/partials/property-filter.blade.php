<div class="filter-overlay" id="{{ $id }}Overlay" onclick="closeAdvancedFilter('{{ $id }}')"></div>
<div class="filter-sheet" id="{{ $id }}">
  <div class="sheet-handle"></div>
  <div style="display:flex;align-items:center;justify-content:space-between;padding:0 20px 12px;">
    <div style="font-size:16px;font-weight:700;color:var(--text-primary);">Bộ lọc nâng cao</div>
    <button onclick="resetAdvancedFilter('{{ $id }}')" style="font-size:12px;color:var(--primary);background:none;border:none;cursor:pointer;font-weight:600;">Đặt lại</button>
  </div>

  <div class="filter-sheet-body">
    <!-- Loại giao dịch -->
    <div class="fs-section">
      <div class="fs-label">Loại giao dịch</div>
      <div class="fs-chips">
        <div class="fs-chip active" data-filter="property_type" data-value="" onclick="selectAdvancedFilterChip(this)">Tất cả</div>
        <div class="fs-chip" data-filter="property_type" data-value="0" onclick="selectAdvancedFilterChip(this)">Bán</div>
        <div class="fs-chip" data-filter="property_type" data-value="1" onclick="selectAdvancedFilterChip(this)">Cho thuê</div>
      </div>
    </div>

    <!-- Loại BĐS — lấy từ DB -->
    <div class="fs-section">
      <div class="fs-label">Loại BĐS</div>
      <div class="fs-chips">
        <div class="fs-chip active" data-filter="categoryName" data-value="" onclick="selectAdvancedFilterChip(this)">Tất cả</div>
        @foreach($categories as $cat)
        <div class="fs-chip" data-filter="categoryName" data-value="{{ $cat->category }}" onclick="selectAdvancedFilterChip(this)">{{ $cat->category }}</div>
        @endforeach
      </div>
    </div>

    <!-- Khoảng giá -->
    <div class="fs-section">
      <div class="fs-label">Khoảng giá</div>
      <div class="fs-chips">
        <div class="fs-chip active" data-filter="price" data-value="" onclick="selectAdvancedFilterChip(this)">Tất cả</div>
        <div class="fs-chip" data-filter="price" data-value="Dưới 1 tỷ" onclick="selectAdvancedFilterChip(this)">Dưới 1 tỷ</div>
        <div class="fs-chip" data-filter="price" data-value="1–2 tỷ" onclick="selectAdvancedFilterChip(this)">1–2 tỷ</div>
        <div class="fs-chip" data-filter="price" data-value="2–3 tỷ" onclick="selectAdvancedFilterChip(this)">2–3 tỷ</div>
        <div class="fs-chip" data-filter="price" data-value="3–5 tỷ" onclick="selectAdvancedFilterChip(this)">3–5 tỷ</div>
        <div class="fs-chip" data-filter="price" data-value="5–7 tỷ" onclick="selectAdvancedFilterChip(this)">5–7 tỷ</div>
        <div class="fs-chip" data-filter="price" data-value="7–10 tỷ" onclick="selectAdvancedFilterChip(this)">7–10 tỷ</div>
        <div class="fs-chip" data-filter="price" data-value="Trên 10 tỷ" onclick="selectAdvancedFilterChip(this)">Trên 10 tỷ</div>
      </div>
    </div>

    <!-- Khu vực -->
    <div class="fs-section">
      <div class="fs-label">Khu vực</div>
      <div class="fs-chips">
        <div class="fs-chip active" data-filter="location" data-value="" onclick="selectAdvancedFilterChip(this)">Tất cả</div>
        @php
          $hotWards = \App\Models\LocationsWard::where('district_code', config('location.district_code'))->get();
        @endphp
        @foreach($hotWards as $w)
        <div class="fs-chip" data-filter="location" data-value="{{ $w->full_name }}" onclick="selectAdvancedFilterChip(this)">{{ $w->full_name }}</div>
        @endforeach
      </div>
    </div>

    <!-- Diện tích -->
    <div class="fs-section">
      <div class="fs-label">Diện tích</div>
      <div class="fs-chips">
        <div class="fs-chip active" data-filter="area" data-value="" onclick="selectAdvancedFilterChip(this)">Tất cả</div>
        <div class="fs-chip" data-filter="area" data-value="0-100" onclick="selectAdvancedFilterChip(this)">Dưới 100m²</div>
        <div class="fs-chip" data-filter="area" data-value="100-200" onclick="selectAdvancedFilterChip(this)">100–200m²</div>
        <div class="fs-chip" data-filter="area" data-value="200-500" onclick="selectAdvancedFilterChip(this)">200–500m²</div>
        <div class="fs-chip" data-filter="area" data-value="500-1000" onclick="selectAdvancedFilterChip(this)">500–1000m²</div>
        <div class="fs-chip" data-filter="area" data-value="1000+" onclick="selectAdvancedFilterChip(this)">Trên 1000m²</div>
      </div>
    </div>

    <!-- Hướng -->
    <div class="fs-section">
      <div class="fs-label">Hướng</div>
      <div class="fs-chips">
        <div class="fs-chip active" data-filter="direction" data-value="" onclick="selectAdvancedFilterChip(this)">Tất cả</div>
        <div class="fs-chip" data-filter="direction" data-value="Đông" onclick="selectAdvancedFilterChip(this)">Đông</div>
        <div class="fs-chip" data-filter="direction" data-value="Tây" onclick="selectAdvancedFilterChip(this)">Tây</div>
        <div class="fs-chip" data-filter="direction" data-value="Nam" onclick="selectAdvancedFilterChip(this)">Nam</div>
        <div class="fs-chip" data-filter="direction" data-value="Bắc" onclick="selectAdvancedFilterChip(this)">Bắc</div>
        <div class="fs-chip" data-filter="direction" data-value="Đông Nam" onclick="selectAdvancedFilterChip(this)">ĐN</div>
        <div class="fs-chip" data-filter="direction" data-value="Đông Bắc" onclick="selectAdvancedFilterChip(this)">ĐB</div>
        <div class="fs-chip" data-filter="direction" data-value="Tây Nam" onclick="selectAdvancedFilterChip(this)">TN</div>
        <div class="fs-chip" data-filter="direction" data-value="Tây Bắc" onclick="selectAdvancedFilterChip(this)">TB</div>
      </div>
    </div>

    <!-- Pháp lý -->
    <div class="fs-section">
      <div class="fs-label">Pháp lý</div>
      <div class="fs-chips">
        <div class="fs-chip active" data-filter="legal" data-value="" onclick="selectAdvancedFilterChip(this)">Tất cả</div>
        <div class="fs-chip" data-filter="legal" data-value="Sổ đỏ" onclick="selectAdvancedFilterChip(this)">Sổ đỏ</div>
        <div class="fs-chip" data-filter="legal" data-value="Sổ hồng" onclick="selectAdvancedFilterChip(this)">Sổ hồng</div>
        <div class="fs-chip" data-filter="legal" data-value="Giấy tay" onclick="selectAdvancedFilterChip(this)">Giấy tay</div>
        <div class="fs-chip" data-filter="legal" data-value="Hợp đồng" onclick="selectAdvancedFilterChip(this)">Hợp đồng</div>
      </div>
    </div>
  </div>

  <div class="fs-footer">
    <button class="fs-btn-reset" onclick="resetAdvancedFilter('{{ $id }}')">Đặt lại</button>
    <button class="fs-btn-apply" onclick="{!! $onApply !!}">Xem kết quả</button>
  </div>
</div>
