@extends('frontends.master')
@section('content')
<div class="content">
    <section class="gray-bg small-padding ">
        <div class="container">
            <div class="mob-nav-content-btn  color-bg show-list-wrap-search ntm fl-wrap">Tìm kiếm thêm</div>
            <div class="list-searh-input-wrap box_list-searh-input-wrap lws_mobile fl-wrap">
                <div class="list-searh-input-wrap-title fl-wrap"><i class="far fa-sliders-h"></i><span>Bộ Lọc Tìm
                        Kiếm</span></div>
                <div class="custom-form fl-wrap">
                    <form id="searchForm" action="{{ route('properties.index') }}" method="GET">
                        @csrf
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="listsearch-input-item">
                                    <input name="text" type="text" placeholder="Tìm BDS" value="{{ request()->input('text') }}" />
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="listsearch-input-item">
                                    <select name="property_type" data-placeholder="Tình trạng"
                                        class="chosen-select on-radius no-search-select">
                                        <option value="">Cho thuê & Bán</option>
                                        <option value="0" {{ request()->input('property_type') == '0' ? 'selected' :
                                            ''}}>Bán</option>
                                        <option value="1" {{ request()->input('property_type') == '1' ? 'selected' :
                                            ''}}>Cho Thuê</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="listsearch-input-item">
                                    <select name="ward" data-placeholder="Phường Xã"
                                        class="chosen-select on-radius no-search-select">
                                        <option value="">Phường Xã</option>
                                        @foreach ($locationsWards as $locationsWard)
                                        <option value="{{$locationsWard->code}}" {{ request()->input('ward') ==
                                            $locationsWard->code ? 'selected' : '' }}>
                                            {{$locationsWard->full_name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="listsearch-input-item">
                                    <select name="street" data-placeholder="All Categories" class="chosen-select">
                                        <option value="">Đường</option>
                                        @foreach ($locationsStreets as $locationsStreet)
                                        <option value="{{$locationsStreet->code}}" {{ request()->input('street') ==
                                            $locationsStreet->code ? 'selected' : '' }}>
                                            {{$locationsStreet->street_name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="clearfix"></div>
                            <div class="col-sm-3">
                                <div class="listsearch-input-item">
                                    <select name="category" data-placeholder="Loại BDS"
                                        class="chosen-select on-radius no-search-select">
                                        <option value="">Loại BDS</option>
                                        @foreach ($categories as $categorie)
                                        <option value="{{ $categorie->category }}" {{ request()->input('category') ==
                                            $categorie->category ? 'selected' : '' }}>
                                            {{ $categorie->category }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="listsearch-input-item">
                                    <div class="price-range-item fl-wrap">
                                        <span class="pr_title">Giá:</span>
                                        <input type="text" class="price-range-double" data-min="100000000"
                                            data-max="{{config('global.max_price')}}" name="price-range2"
                                            data-step="100000000" value="{{ request()->input('price-range2') }}"
                                            max_postfix="+">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="listsearch-input-item">
                                    <button type="submit" class="btn color-bg fw-btn float-btn small-btn">Tìm
                                        kiếm</button>
                                </div>
                            </div>
                            </div>
                        <div class="clearfix"></div>
                        <div class="hidden-listing-filter fl-wrap">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="listsearch-input-item">
                                        <label for="legal">Pháp lý</label>
                                        <select name="legal" data-placeholder="Chọn pháp lý"
                                            class="chosen-select on-radius no-search-select">
                                            <option value="">Chọn pháp lý</option>
                                            @foreach ($legals as $key => $value)
                                            <option value="{{ $value }}" {{ Request::input('legal')==$value ? 'selected'
                                                : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="listsearch-input-item">
                                        <label for="direction">Hướng</label>
                                        <select name='direction' data-placeholder="Chọn hướng"
                                            class="chosen-select on-radius no-search-select">
                                            <option value="">Chọn hướng</option>
                                            @foreach ($directions as $key => $value)
                                            <option value="{{ $value }}" {{ Request::input('direction')==$value
                                                ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="listsearch-input-item">
                                        <label>Diện tích (m²)</label>
                                        <div class="price-rage-item pr-nopad fl-wrap">
                                            <input name="area" type="text" class="area-range-double" data-min="1"
                                                data-max="{{config('global.max_area')}}" data-step="10" data-prefix=""
                                                value="{{ request()->input('area') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="listsearch-input-item">
                                        <label>Số tầng</label>
                                        <select name='number_floor' data-placeholder="Số tầng"
                                            class="chosen-select on-radius no-search-select">
                                            <option value="0">Chọn số tầng</option>
                                            @for ($i = 1; $i <= 10; $i++) <option value="{{ $i }}" {{ request()->
                                                input('number_floor') == $i ?
                                                'selected' : ''}}>
                                                {{ $i == 10 ? '10+' : $i }}
                                                </option>
                                                @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="listsearch-input-item">
                                        <label>Số phòng</label>
                                        <select name='number_room' data-placeholder="Số phòng ngủ"
                                            class="chosen-select on-radius no-search-select">
                                            <option value="0">Chọn số phòng</option>
                                            @for ($i = 1; $i <= 10; $i++) <option value="{{ $i }}" {{ request()->
                                                input('number_room') == $i ? 'selected' : ''}}>{{ $i }}</option>
                                                @endfor
                                                <option value="10" {{ request()->input('number_room') == '10' ?
                                                    'selected' : ''}}>10+</option>
                                        </select>
                                    </div>
                                </div>
                                </div>
                            <div class="clearfix"></div>
                        </div>
                    </form>
                </div>
                <div class="more-filter-option-wrap">
                    <div class="more-filter-option-btn more-filter-option act-hiddenpanel"> <span>Tìm kiếm nâng
                            cao</span> <i class="fas fa-caret-down"></i></div>
                    <div class="reset-form reset-btn"> <i class="far fa-sync-alt"></i> Đặt lại bộ lọc</div>
                </div>
            </div>
            <div class="list-main-wrap-header box-list-header fl-wrap">
                <div class="list-main-wrap-title">
                    <h2>{{ $searchResult }}
                        <strong>{{ $properties->total() }}</strong>
                    </h2>
                </div>
                <div class="list-main-wrap-opt">
                    <div class="price-opt">
                        <span class="price-opt-title">Sắp xếp theo:</span>
                        <div class="listsearch-input-item">
                            <select name="sort_status" class="chosen-select no-search-select">
                                <option value="">Bình thường</option>
                                <option value="view_count" {{ Request::input('sort_status')=='view_count' ? 'selected'
                                    : '' }}>Phổ biến</option>
                                {{-- <option>Điểm đánh giá trung bình</option> --}}
                                <option value="price_asc" {{ Request::input('sort_status')=='price_asc' ? 'selected'
                                    : '' }}>Giá: thấp đến cao</option>
                                <option value="price_desc" {{ Request::input('sort_status')=='price_desc' ? 'selected'
                                    : '' }}>Giá: cao đến thấp</option>
                            </select>
                        </div>
                    </div>
                    </div>
                </div>
            <div class="listing-item-container three-columns-grid  box-list_ic fl-wrap">
                @foreach($properties as $productItem )
                @include('frontends.components.product_card',['productCard'=>$productItem ])
                @endforeach
            </div>
            <div class="pagination">
                @php
                    $current = $properties->currentPage();
                    // Đảm bảo lastPage bao gồm currentPage
                    $end = max($properties->lastPage(), $current);
                    $window = 2; // Số trang hiển thị quanh trang hiện tại
                @endphp

                @if ($properties->onFirstPage())
                    <a href="#" class="prevposts-link disabled"><i class="fa fa-caret-left"></i></a>
                @else
                    <a href="{{ $properties->previousPageUrl() }}" class="prevposts-link"><i class="fa fa-caret-left"></i></a>
                @endif

                {{-- Logic phân trang nâng cao (giữ lại từ nhánh mới) --}}
                @if ($end <= 7)
                    {{-- Hiển thị tất cả nếu số trang nhỏ --}}
                    @foreach (range(1, $end) as $page)
                        @if ($page == $current)
                            <a href="#" class="current-page">{{ $page }}</a>
                        @else
                            <a href="{{ $properties->url($page) }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @else
                    {{-- Hiển thị cửa sổ và dấu chấm --}}
                    @php
                        $pages = [];
                        $pages[] = 1;
                        $rangeStart = max(2, $current - $window);
                        $rangeEnd = min($end - 1, $current + $window);

                        if ($rangeStart > 2) {
                            $pages[] = '...';
                        }
                        for ($i = $rangeStart; $i <= $rangeEnd; $i++) {
                            $pages[] = $i;
                        }
                        if ($rangeEnd < $end - 1) {
                            $pages[] = '...';
                        }
                        if ($end > 1) {
                            $pages[] = $end;
                        }
                    @endphp

                    @foreach ($pages as $page)
                        @if ($page === '...')
                            <a href="#" class="disabled">...</a>
                        @else
                            @if ($page == $current)
                                <a href="#" class="current-page">{{ $page }}</a>
                            @else
                                <a href="{{ $properties->url($page) }}">{{ $page }}</a>
                            @endif
                        @endif
                    @endforeach
                @endif
                {{-- Kết thúc logic phân trang nâng cao --}}

                @if ($properties->hasMorePages())
                    <a href="{{ $properties->nextPageUrl() }}" class="nextposts-link"><i class="fa fa-caret-right"></i></a>
                @else
                    <a href="#" class="nextposts-link disabled"><i class="fa fa-caret-right"></i></a>
                @endif
            </div>
            
            </div>
    </section>
    <div class="limit-box fl-wrap"></div>
</div>
@endsection
