@extends('frontends.ltbs.master')

@section('title', 'Danh sách BĐS Đà Lạt — Đà Lạt BĐS')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/ltbs-listing.css') }}">
@endpush

@php
    $q = request();
    $ptype = $q->input('property_type', '');
    $curWard = $q->input('ward', '');
    $curCat = $q->input('category', '');
    $curPrice = $q->input('price-range2', '');
    $curRoom = $q->input('number_room', '');
    $curLegal = $q->input('legal', '');
    $curSort = $q->input('sort_status', '');
    $priceOptions = [
        '' => 'Tất cả mức giá',
        '0;2000000000' => 'Dưới 2 tỷ',
        '2000000000;5000000000' => '2 – 5 tỷ',
        '5000000000;10000000000' => '5 – 10 tỷ',
        '10000000000;999000000000' => 'Trên 10 tỷ',
    ];
    $sortOptions = [
        '' => 'Nổi bật',
        'price_asc' => 'Giá tăng dần',
        'price_desc' => 'Giá giảm dần',
        'view_count' => 'Xem nhiều nhất',
    ];
@endphp

@section('content')
    <form id="plForm" method="GET" action="{{ route('properties.index') }}">
        <input type="hidden" name="property_type" id="plPtype" value="{{ $ptype }}">

        {{-- ═══ Search bar ═══ --}}
        <div class="searchbar">
            <div class="wrapc searchbar-in">
                <div class="segbar">
                    <button type="button" class="seg {{ $ptype === '0' || $ptype === '' ? 'on' : '' }}" onclick="LTBSList.setType(this,'0')">Mua bán</button>
                    <button type="button" class="seg {{ $ptype === '1' ? 'on' : '' }}" onclick="LTBSList.setType(this,'1')">Cho thuê</button>
                </div>
                <div class="searchfieldWrap">
                    <div class="searchfield">
                        <svg viewBox="0 0 24 24" class="ds-ic ds-ic-18" style="color:var(--text-tertiary)"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                        <input name="text" value="{{ $q->input('text') }}" list="plStreets" placeholder="Tìm theo phường, đường, dự án…" autocomplete="off">
                    </div>
                    <datalist id="plStreets"></datalist>
                </div>
                <button type="submit" class="ds-btn ds-btn-solid ds-btn-lg">
                    <svg viewBox="0 0 24 24" class="ds-ic ds-ic-18"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                    Tìm
                </button>
            </div>
        </div>

        <div class="main">
            <div class="wrapc">
                <div class="trustStrip">
                    <svg viewBox="0 0 24 24" class="ds-ic ds-ic-16"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    Mọi tin đều xác minh pháp lý &amp; quy hoạch trước khi lên sóng
                </div>

                <div class="layout withSidebar">
                    {{-- ═══ Sidebar filters ═══ --}}
                    <aside class="side">
                        <div class="side-head">
                            <span class="side-title">
                                <svg viewBox="0 0 24 24" class="ds-ic ds-ic-18"><path d="M22 3H2l8 9.5V19l4 2v-8.5L22 3z"></path></svg>
                                Bộ lọc
                            </span>
                            <a href="{{ route('properties.index') }}" class="side-clear">Xoá tất cả</a>
                        </div>
                        <div style="padding:16px 18px;display:flex;flex-direction:column;gap:16px">
                            <div class="fgroup">
                                <div class="fglabel">Khu vực / Phường</div>
                                <select name="ward" class="sortsel" style="width:100%">
                                    <option value="">Toàn Đà Lạt</option>
                                    @foreach ($locationsWards as $w)
                                        <option value="{{ $w->code }}" @selected($curWard == $w->code)>{{ $w->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="fgroup">
                                <div class="fglabel">Loại bất động sản</div>
                                <select name="category" class="sortsel" style="width:100%">
                                    <option value="">Tất cả</option>
                                    @foreach ($categories as $c)
                                        <option value="{{ $c->category }}" @selected($curCat == $c->category)>{{ $c->category }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="fgroup">
                                <div class="fglabel">Mức giá</div>
                                <select name="price-range2" class="sortsel" style="width:100%">
                                    @foreach ($priceOptions as $val => $lbl)
                                        <option value="{{ $val }}" @selected($curPrice === $val)>{{ $lbl }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="fgroup">
                                <div class="fglabel">Số phòng ngủ</div>
                                <div class="chipwrap" style="display:flex;gap:6px;flex-wrap:wrap">
                                    @foreach (['' => 'Tất cả', '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5+'] as $val => $lbl)
                                        <button type="button" class="seg {{ (string) $curRoom === (string) $val ? 'on' : '' }}" onclick="LTBSList.setRoom(this,'{{ $val }}')">{{ $lbl }}</button>
                                    @endforeach
                                </div>
                                <input type="hidden" name="number_room" id="plRoom" value="{{ $curRoom }}">
                            </div>
                            <div class="fgroup">
                                <div class="fglabel">Pháp lý</div>
                                <select name="legal" class="sortsel" style="width:100%">
                                    <option value="">Tất cả</option>
                                    @foreach (($legals ?? []) as $lg)
                                        <option value="{{ $lg }}" @selected($curLegal == $lg)>{{ $lg }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="ds-btn ds-btn-solid ds-btn-md ds-btn-block">Áp dụng bộ lọc</button>
                        </div>
                    </aside>

                    {{-- ═══ Results ═══ --}}
                    <div>
                        <div class="rtop">
                            <div>
                                <h1 class="rtitle">{{ $searchResult }}</h1>
                                <div class="rcount">{{ $properties->total() }} tin</div>
                            </div>
                            <div class="rtools">
                                <select name="sort_status" class="sortsel" onchange="document.getElementById('plForm').submit()">
                                    @foreach ($sortOptions as $val => $lbl)
                                        <option value="{{ $val }}" @selected($curSort === $val)>{{ $lbl }}</option>
                                    @endforeach
                                </select>
                                <div class="viewSeg">
                                    <button type="button" class="viewSegBtn on" id="viewListBtn" onclick="LTBSList.view('list')">
                                        <svg viewBox="0 0 24 24" class="ds-ic ds-ic-16"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"></path></svg><span>Danh sách</span>
                                    </button>
                                    <button type="button" class="viewSegBtn" id="viewMapBtn" onclick="LTBSList.view('map')">
                                        <svg viewBox="0 0 24 24" class="ds-ic ds-ic-16"><path d="M9 18l-6 3V6l6-3 6 3 6-3v15l-6 3-6-3zM9 3v15M15 6v15"></path></svg><span>Bản đồ</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- List view --}}
                        <div id="viewList">
                            <div class="rgrid">
                                @forelse ($properties as $property)
                                    @include('frontends.ltbs.components.property_card', ['property' => $property])
                                @empty
                                    <div class="empty">
                                        <div class="empty-ic"><svg viewBox="0 0 24 24" class="ds-ic ds-ic-22"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg></div>
                                        <h3>Không tìm thấy tin phù hợp</h3>
                                        <p>Thử mở rộng khu vực hoặc mức giá. Bạn cũng có thể nhận thông báo khi có tin mới.</p>
                                    </div>
                                @endforelse
                            </div>
                            <div style="margin-top:26px">{{ $properties->links('frontends.ltbs.components.pagination') }}</div>
                        </div>

                        {{-- Map view (Google Maps enhancement; planning layers/draw = TODO) --}}
                        <div id="viewMap" style="display:none">
                            <div class="mapWrap" id="plMap" style="height:calc(100vh - 300px);min-height:460px;border-radius:var(--radius-lg);overflow:hidden;border:1px solid var(--border)"></div>
                            {{-- TODO(map): layer quy hoạch (đất ở/TM-DV/nông nghiệp), vẽ vùng tìm kiếm, cluster nâng cao. --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Property coordinates for the map view --}}
    @php
        $mapData = [];
        foreach ($properties as $p) {
            $mapData[] = [
                'id' => $p->id,
                'title' => $p->title,
                'price' => $p->formatted_prices,
                'lat' => $p->latitude ? (float) $p->latitude : null,
                'lng' => $p->longitude ? (float) $p->longitude : null,
                'url' => route('bds.show', ['slug' => $p->slug]),
            ];
        }
    @endphp
    <script>window.LTBS_PROPS = {!! json_encode($mapData, JSON_UNESCAPED_UNICODE) !!};</script>
@endsection

@push('scripts')
    <script src="{{ asset('js/ltbs-listing.js') }}"></script>
    <script>
        // Street autocomplete via existing endpoint
        (function () {
            const input = document.querySelector('#plForm input[name=text]');
            const dl = document.getElementById('plStreets');
            let t;
            input?.addEventListener('input', function () {
                clearTimeout(t);
                const term = this.value.trim();
                if (term.length < 2) return;
                t = setTimeout(async () => {
                    try {
                        const r = await fetch('{{ route('autocomplete.street') }}?term=' + encodeURIComponent(term));
                        const list = await r.json();
                        dl.innerHTML = (list || []).map(s => `<option value="${s}">`).join('');
                    } catch (e) {}
                }, 250);
            });
        })();
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.place_api_key') }}&libraries=marker&loading=async&callback=LTBSListMapInit" async defer></script>
@endpush
