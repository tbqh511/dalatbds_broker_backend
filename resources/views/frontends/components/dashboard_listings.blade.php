<div class="content">
    <div class="dashbard-menu-overlay"></div>
    @include('components.dashboard.sidebar')

    <!-- dashboard content -->
    <div class="dashboard-content">
        @include('components.dashboard.mobile_btn')
        <div class="container dasboard-container">
            <!-- dashboard-title -->
            @include('components.dashboard.header', ['title' => 'Tin đăng của bạn'])
            <!-- dashboard-title end -->
            
            <div class="dasboard-wrapper fl-wrap">
                <div class="dasboard-listing-box fl-wrap">
                    <div class="dasboard-opt sl-opt fl-wrap">
                        <div class="dashboard-search-listing">
                            <input type="text" onclick="this.select()" placeholder="Tìm kiếm..." value="">
                            <button type="submit"><i class="far fa-search"></i></button>
                        </div>
                        <a href="{{ route('webapp.add_listing') }}" class="gradient-bg dashboard-addnew_btn">Đăng tin mới <i class="fal fa-plus"></i></a>
                        <!-- price-opt-->
                        <div class="price-opt">
                            <span class="price-opt-title">Sắp xếp theo:</span>
                            <div class="listsearch-input-item">
                                <select data-placeholder="Mới nhất" class="chosen-select no-search-select" >
                                    <option>Mới nhất</option>
                                    <option>Cũ nhất</option>
                                    <option>Giá: Thấp đến Cao</option>
                                    <option>Giá: Cao đến Thấp</option>
                                </select>
                            </div>
                        </div>
                        <!-- price-opt end-->
                    </div>
                    <!-- dashboard-listings-wrap-->
                    <div class="dashboard-listings-wrap fl-wrap">
                        <div class="row">
                            @if(isset($properties) && $properties->count())
                                @foreach($properties as $property)
                                    <div class="col-md-6">
                                        <div class="dashboard-listings-item fl-wrap">
                                            <div class="dashboard-listings-item_img">
                                                <div class="bg-wrap">
                                                    <div class="bg" data-bg="{{ $property->title_image ?: asset('images/all/1.jpg') }}"></div>
                                                </div>
                                                <div class="overlay"></div>
                                                <a href="{{ isset($property->slug) ? route('bds.show', $property->slug) : '#' }}" class="color-bg">Xem</a>
                                            </div>
                                            <div class="dashboard-listings-item_content">
                                                <h4><a href="{{ isset($property->slug) ? route('bds.show', $property->slug) : '#' }}">{{ $property->title }}</a></h4>
                                                <div class="geodir-category-location">
                                                    <a href="#"><i class="fas fa-map-marker-alt"></i>
                                                        <span> {{ $property->address_location ?? $property->address }}</span></a>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="listing-rating card-popup-rainingvis tolt" data-microtip-position="right" data-tooltip="" data-starrating2="{{ $property->rating ?? 0 }}"></div>
                                                <div class="dashboard-listings-item_opt">
                                                    <span class="viewed-counter"><i class="fas fa-eye"></i> Lượt xem - {{ $property->total_click ?? 0 }} </span>
                                                    <ul>
                                                        <li><a href="#" class="tolt" data-microtip-position="top-left" data-tooltip="Chỉnh sửa"><i class="far fa-edit"></i></a></li>
                                                        <li><a href="#" class="tolt" data-microtip-position="top-left" data-tooltip="Ẩn/Hiện"><i class="far fa-signal-alt-slash"></i></a></li>
                                                        <li><a href="#" class="tolt" data-microtip-position="top-left" data-tooltip="Xóa"><i class="far fa-trash-alt"></i></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12">
                                    <div class="alert alert-info">Bạn chưa có tin đăng nào.</div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- dashboard-listings-wrap end-->
                </div>
                <!-- pagination-->
                @if(isset($properties) && $properties->hasPages())
                    <div class="pagination float-pagination">
                        @if($properties->onFirstPage())
                            <a class="prevposts-link disabled"><i class="fa fa-caret-left"></i></a>
                        @else
                            <a href="{{ $properties->previousPageUrl() }}" class="prevposts-link"><i class="fa fa-caret-left"></i></a>
                        @endif

                        @foreach(range(1, $properties->lastPage()) as $page)
                            @if($page == $properties->currentPage())
                                <a href="{{ $properties->url($page) }}" class="current-page">{{ $page }}</a>
                            @else
                                <a href="{{ $properties->url($page) }}">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($properties->hasMorePages())
                            <a href="{{ $properties->nextPageUrl() }}" class="nextposts-link"><i class="fa fa-caret-right"></i></a>
                        @else
                            <a class="nextposts-link disabled"><i class="fa fa-caret-right"></i></a>
                        @endif
                    </div>
                @endif
                <!-- pagination end-->
            </div>    
        </div>
        <!-- dashboard-footer -->
        @include('components.dashboard.footer')
        <!-- dashboard-footer end -->
    </div>
    <div class="dashbard-bg gray-bg"></div>
</div>
