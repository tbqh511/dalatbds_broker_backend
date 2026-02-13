<div class="row" id="listings-container">
    @if(isset($properties) && $properties->count())
    @foreach($properties as $property)
    <div class="col-md-6">
        <div class="dashboard-listings-item fl-wrap" id="listing-item-{{ $property->id }}">
            <div class="dashboard-listings-item_img">
                <div class="bg-wrap">
                    <div class="bg"
                        style="background-image: url('{{ $property->title_image ?: asset('images/all/1.jpg') }}');">
                    </div>
                </div>
                <div class="overlay"></div>
                <a href="{{ isset($property->slug) ? route('bds.show', $property->slug) : '#' }}"
                    class="color-bg">Xem</a>
            </div>
            <div class="dashboard-listings-item_content">
                <h4><a href="{{ isset($property->slug) ? route('bds.show', $property->slug) : '#' }}">{{
                        $property->title }}</a></h4>
                <div class="geodir-category-location">
                    <a href="#"><i class="fas fa-map-marker-alt"></i>
                        <span> {{ $property->address_location ?? $property->address }}</span></a>
                </div>
                <div class="clearfix"></div>

                <div style="text-align: right; margin-top: 5px; position: absolute; right: 20px; bottom: 70px;">
                    @if($property->status == 1)
                    <span
                        style="background: #4db7fe; color: white; padding: 3px 8px; font-size: 10px; border-radius: 4px; font-weight: bold;">Đang
                        hiển thị</span>
                    @elseif($property->status == 2)
                    <span
                        style="background: #999; color: white; padding: 3px 8px; font-size: 10px; border-radius: 4px; font-weight: bold;">Đang
                        ẩn</span>
                    @else
                    <span
                        style="background: #fbc54f; color: white; padding: 3px 8px; font-size: 10px; border-radius: 4px; font-weight: bold;">Chờ
                        duyệt</span>
                    @endif
                </div>

                <div style="font-weight: 600; color: #3270FC; font-size: 14px; margin-top: 10px; text-align: left;">
                    {{ $property->formatted_prices }}
                </div>
                <div class="dashboard-listings-item_opt">
                    <span class="viewed-counter"><i class="fas fa-eye"></i> Lượt xem - {{ $property->total_click ?? 0 }}
                    </span>
                    <ul>
                        <li><a href="{{ route('webapp.edit_listing', $property->id) }}" class="tolt"
                                data-microtip-position="top-left" data-tooltip="Chỉnh sửa"><i
                                    class="far fa-edit"></i></a></li>
                        <li>
                            <a href="javascript:void(0);" onclick="toggleListing({{ $property->id }})" class="tolt"
                                data-microtip-position="top-left"
                                data-tooltip="{{ ($property->status == 1) ? 'Ẩn tin' : 'Hiện tin' }}">
                                <i class="far {{ ($property->status == 1) ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                            </a>
                        </li>
                        <li><a href="javascript:void(0);" onclick="deleteListing({{ $property->id }})" class="tolt"
                                data-microtip-position="top-left" data-tooltip="Xóa"><i
                                    class="far fa-trash-alt"></i></a></li>
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

<!-- Pagination -->
@if(isset($properties) && $properties->hasPages())
<div class="pagination" style="margin-top: 30px;">
    @if($properties->onFirstPage())
    <a class="prevposts-link disabled"><i class="fa fa-caret-left"></i></a>
    @else
    <a href="javascript:void(0);" onclick="fetchListings('{{ $properties->previousPageUrl() }}')"
        class="prevposts-link"><i class="fa fa-caret-left"></i></a>
    @endif

    @foreach(range(1, $properties->lastPage()) as $page)
    @if($page == $properties->currentPage())
    <a href="javascript:void(0);" class="current-page">{{ $page }}</a>
    @else
    <a href="javascript:void(0);" onclick="fetchListings('{{ $properties->url($page) }}')">{{ $page }}</a>
    @endif
    @endforeach

    @if($properties->hasMorePages())
    <a href="javascript:void(0);" onclick="fetchListings('{{ $properties->nextPageUrl() }}')" class="nextposts-link"><i
            class="fa fa-caret-right"></i></a>
    @else
    <a class="nextposts-link disabled"><i class="fa fa-caret-right"></i></a>
    @endif
</div>
@endif