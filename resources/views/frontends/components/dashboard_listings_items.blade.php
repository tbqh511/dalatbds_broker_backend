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

                <div style="display: flex; gap: 4px; flex-wrap: wrap; justify-content: flex-end; margin-top: 5px; position: absolute; right: 20px; bottom: 70px;">
                    @if($property->is_private)
                    <span style="background: #f59e0b; color: white; padding: 3px 8px; font-size: 10px; border-radius: 4px; font-weight: bold;"><i class="fas fa-lock" style="margin-right: 3px;"></i>Riêng tư</span>
                    @endif
                    @if($property->status == 1)
                    <span style="background: #4db7fe; color: white; padding: 3px 8px; font-size: 10px; border-radius: 4px; font-weight: bold;">Đang hiển thị</span>
                    @elseif($property->status == 2)
                    <span style="background: #999; color: white; padding: 3px 8px; font-size: 10px; border-radius: 4px; font-weight: bold;">Đang ẩn</span>
                    @else
                    <span style="background: #fbc54f; color: white; padding: 3px 8px; font-size: 10px; border-radius: 4px; font-weight: bold;"><i class="fas fa-hourglass-half" style="margin-right:3px;"></i>Chờ duyệt</span>
                    @endif
                </div>

                <div style="font-weight: 600; color: #3270FC; font-size: 14px; margin-top: 10px; text-align: left;">
                    {{ $property->formatted_prices }}
                </div>

                @if($property->status == 0)
                <div style="background: #fff8e1; border-left: 3px solid #fbc54f; border-radius: 4px; padding: 7px 10px; margin: 6px 0; font-size: 12px; display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                    <div>
                        <div style="color: #f59e0b; font-weight: 600; margin-bottom: 2px;">
                            <i class="fas fa-hourglass-half" style="margin-right: 4px;"></i>Đang chờ Admin duyệt
                        </div>
                        <div style="color: #888;">Gửi {{ $property->created_at->format('d/m/Y') }} &middot; Thường duyệt trong 24h</div>
                    </div>
                    <button onclick="deleteListing({{ $property->id }})"
                        style="background: #f59e0b; color: white; border: none; border-radius: 4px; padding: 4px 10px; font-size: 12px; font-weight: 600; cursor: pointer; white-space: nowrap;">
                        Rút tin
                    </button>
                </div>
                @endif

                <div class="dashboard-listings-item_opt">
                    <span class="viewed-counter" style="display:flex; align-items:center; gap:8px;">
                        <span><i class="fas fa-eye"></i> {{ $property->total_click ?? 0 }}</span>
                        <span style="font-size:12px; color:#aaa;"><i class="far fa-calendar" style="margin-right:3px;"></i>{{ $property->created_at->format('d/m/Y') }}</span>
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
                        <li>
                            <a href="javascript:void(0);" onclick="togglePrivateListing({{ $property->id }})" class="tolt"
                                data-microtip-position="top-left"
                                data-tooltip="{{ $property->is_private ? 'Bỏ riêng tư' : 'Đặt riêng tư' }}"
                                style="{{ $property->is_private ? 'color: #f59e0b;' : '' }}">
                                <i class="fas {{ $property->is_private ? 'fa-lock' : 'fa-lock-open' }}"></i>
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
        <div class="alert alert-info">Không có tin đăng nào trong mục này.</div>
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
