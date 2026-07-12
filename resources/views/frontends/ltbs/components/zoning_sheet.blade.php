{{-- LTBS "Kiểm tra quy hoạch" widget.
     UI is final; the check result is a CLIENT-SIDE STUB.
     TODO(backend): thay bằng API tra cứu quy hoạch thật (số tờ/số thửa/toạ độ → kết quả). --}}
@php $wards = $locationsWards ?? collect(); @endphp
<section class="qh-sec" id="quyhoach">
    <div class="qh-contour"></div>
    <div class="hero-glow" style="width:460px;height:460px;background:#3270fc;top:-140px;right:-100px;opacity:.35"></div>
    <div class="wrapc">
        <div class="qh-body">
            <span class="hero-eyebrow">CÔNG CỤ MIỄN PHÍ</span>
            <h2 class="qh-title">Đất này có dính quy hoạch không?</h2>
            <p class="qh-sub">Soi trong 30 giây · Dữ liệu quy hoạch công bố của Đà Lạt · Miễn phí, không giới hạn lượt</p>
            <div class="qh-panel">
                <div class="tabs">
                    <button type="button" class="tab on" data-qhtab="tothua" onclick="LTBSHome.qhTab(this,'tothua')">Tờ / Thửa</button>
                    <button type="button" class="tab" data-qhtab="link" onclick="LTBSHome.qhTab(this,'link')">Toạ độ / Link</button>
                </div>
                <div class="qh-tabbody">
                    <div data-qhpane="tothua">
                        <div class="qh-fields">
                            <div class="qh-field"><span class="fl">Số tờ</span><input type="text" inputmode="numeric" placeholder="VD: 42" id="qhTo"></div>
                            <div class="qh-field"><span class="fl">Số thửa</span><input type="text" inputmode="numeric" placeholder="VD: 115" id="qhThua"></div>
                            <div class="qh-field"><span class="fl">Phường</span>
                                <div class="qh-selectrow">
                                    <select id="qhWard" style="border:none;background:none;font:inherit;font-weight:600;width:100%;outline:none;-webkit-appearance:none;appearance:none">
                                        @foreach ($wards as $w)
                                            <option value="{{ $w->full_name }}">{{ $w->full_name }}</option>
                                        @endforeach
                                    </select>
                                    <svg viewBox="0 0 24 24" class="ds-ic ds-ic-16" style="color:var(--text-tertiary)"><path d="M6 9l6 6 6-6"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div data-qhpane="link" style="display:none">
                        <div class="qh-linkbar">
                            <svg viewBox="0 0 24 24" class="ds-ic ds-ic-16" style="color:var(--text-tertiary)"><path d="M10 13a5 5 0 0 0 7 0l3-3a5 5 0 0 0-7-7l-1 1"></path><path d="M14 11a5 5 0 0 0-7 0l-3 3a5 5 0 0 0 7 7l1-1"></path></svg>
                            <input type="text" id="qhLink" placeholder="Dán link Google Maps hoặc toạ độ…">
                        </div>
                        <span class="qh-linkhint">Nhận cả tọa độ VN-2000 trên sổ đỏ</span>
                    </div>
                </div>
                <div class="qh-submitrow">
                    <button type="button" class="ds-btn ds-btn-solid ds-btn-lg ds-btn-block" onclick="LTBSHome.qhCheck()">Kiểm tra quy hoạch</button>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Result sheet --}}
<div class="qhs-overlay" id="qhsOverlay" style="display:none" onclick="LTBSHome.qhClose(event)">
    <div class="qhs-sheet" onclick="event.stopPropagation()">
        <div class="qhs-grabber"></div>
        <div class="qhs-head">
            <span class="qhs-headtitle">Phiếu quy hoạch</span>
            <button type="button" class="qhs-iconbtn" onclick="LTBSHome.qhClose()" aria-label="Đóng">
                <svg viewBox="0 0 24 24" class="ds-ic ds-ic-16"><path d="M18 6 6 18M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="qhs-scroll" id="qhsScroll">
            <div class="qhs-loading" id="qhsLoading">
                <div class="qhs-spinner"></div>
                <p class="qhs-loading-text">Đang định vị thửa…<br>Đang đối chiếu quy hoạch công bố…</p>
            </div>
            <div id="qhsResult" style="display:none">
                <div class="qhs-banner clean" id="qhsBanner">
                    <div class="qhs-banner-ic"><svg viewBox="0 0 24 24" class="ds-ic ds-ic-18" style="stroke:#fff"><path d="M20 6 9 17l-5-5"></path></svg></div>
                    <div>
                        <div class="qhs-banner-label" id="qhsBannerLabel">CHƯA GHI NHẬN CHỒNG LẤN</div>
                        <div class="qhs-banner-text" id="qhsBannerText">Thửa đất chưa ghi nhận chồng lấn quy hoạch trong dữ liệu công bố hiện có.</div>
                    </div>
                </div>
                <div class="qhs-table">
                    <div class="qhs-row"><span class="qhs-row-label">Số tờ – Số thửa</span><span class="qhs-row-val" id="qhsToThua">—</span></div>
                    <div class="qhs-row"><span class="qhs-row-label">Phường</span><span class="qhs-row-val" id="qhsWardVal">—</span></div>
                    <div class="qhs-row"><span class="qhs-row-label">Cập nhật dữ liệu</span><span class="qhs-row-val">07/2026</span></div>
                </div>
                <p class="qhs-disclaimer">Dữ liệu tham khảo từ quy hoạch công bố của TP. Đà Lạt. Không thay thế văn bản trả lời quy hoạch chính thức. <b>(Dữ liệu demo — chưa nối API thật.)</b></p>
            </div>
        </div>
        <div class="qhs-actions">
            <a class="ds-btn ds-btn-solid ds-btn-lg ds-btn-block" href="{{ route('properties.index') }}">BĐS đã xác thực gần thửa này</a>
        </div>
    </div>
</div>
