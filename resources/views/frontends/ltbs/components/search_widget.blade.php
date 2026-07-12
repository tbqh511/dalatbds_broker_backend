{{-- LTBS hero search widget → GET route('properties.index').
     Maps to existing FrontEndPropertiesController@index params:
     property_type (0 buy / 1 rent), ward, category, price-range2 (min;max). --}}
@php
    $wards = $locationsWards ?? collect();
    $cats = $categories ?? collect();
    $priceOptions = [
        '' => 'Tất cả mức giá',
        '0;2000000000' => 'Dưới 2 tỷ',
        '2000000000;5000000000' => '2 – 5 tỷ',
        '5000000000;10000000000' => '5 – 10 tỷ',
        '10000000000;999000000000' => 'Trên 10 tỷ',
    ];
@endphp
<div class="search" id="heroSearch">
    <div class="search-modebar">
        <div class="tabs" role="tablist">
            <button type="button" class="tab on" data-ptype="0" onclick="LTBSHome.setSearchType(this,0)">Mua bán</button>
            <button type="button" class="tab" data-ptype="1" onclick="LTBSHome.setSearchType(this,1)">Cho thuê</button>
        </div>
    </div>

    {{-- Classic mode --}}
    <form class="fields" method="GET" action="{{ route('properties.index') }}" id="heroSearchForm">
        <input type="hidden" name="property_type" id="heroPtype" value="0">
        <label class="field">
            <span class="fl">Khu vực</span>
            <span class="fv">
                <select name="ward" style="border:none;background:none;font:inherit;font-weight:600;width:100%;outline:none;-webkit-appearance:none;appearance:none">
                    <option value="">Toàn Đà Lạt</option>
                    @foreach ($wards as $w)
                        <option value="{{ $w->code }}">{{ $w->full_name }}</option>
                    @endforeach
                </select>
                <svg viewBox="0 0 24 24" class="ds-ic ds-ic-16" style="color:var(--text-tertiary)"><path d="M6 9l6 6 6-6"></path></svg>
            </span>
        </label>
        <label class="field">
            <span class="fl">Loại BĐS</span>
            <span class="fv">
                <select name="category" style="border:none;background:none;font:inherit;font-weight:600;width:100%;outline:none;-webkit-appearance:none;appearance:none">
                    <option value="">Tất cả</option>
                    @foreach ($cats as $c)
                        <option value="{{ $c->category }}">{{ $c->category }}</option>
                    @endforeach
                </select>
                <svg viewBox="0 0 24 24" class="ds-ic ds-ic-16" style="color:var(--text-tertiary)"><path d="M6 9l6 6 6-6"></path></svg>
            </span>
        </label>
        <label class="field">
            <span class="fl">Mức giá</span>
            <span class="fv">
                <select name="price-range2" style="border:none;background:none;font:inherit;font-weight:600;width:100%;outline:none;-webkit-appearance:none;appearance:none">
                    @foreach ($priceOptions as $val => $lbl)
                        <option value="{{ $val }}">{{ $lbl }}</option>
                    @endforeach
                </select>
                <svg viewBox="0 0 24 24" class="ds-ic ds-ic-16" style="color:var(--text-tertiary)"><path d="M6 9l6 6 6-6"></path></svg>
            </span>
        </label>
        <button type="submit" class="ds-btn ds-btn-solid ds-btn-lg searchbtn">
            <svg viewBox="0 0 24 24" class="ds-ic ds-ic-18"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
            Tìm ngay
        </button>
    </form>

    <button type="button" class="ai-switch" onclick="LTBSHome.toggleAi()" id="aiSwitch">✨ Thử tìm kiếm bằng AI</button>

    {{-- AI/NLP mode (stub: parses text into classic search) --}}
    <div class="nlp-row" id="heroNlp" style="display:none">
        <div class="nlp-bar">
            <span class="nlp-badge">AI</span>
            <span class="nlp-div"></span>
            <input class="nlp-input" type="text" id="nlpInput" placeholder="Ví dụ: “Tìm đất P.8 khoảng 3 tỷ có view”">
            <button type="button" class="nlp-mic" title="Tìm bằng giọng nói" onclick="LTBSHome.mic(this)">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="22"></line></svg>
            </button>
        </div>
        <button type="button" class="ds-btn ds-btn-solid ds-btn-lg nlp-searchbtn" onclick="LTBSHome.aiSearch()">
            <svg viewBox="0 0 24 24" class="ds-ic ds-ic-18"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
            Tìm
        </button>
    </div>
</div>
