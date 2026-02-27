@if(isset($properties) && $properties->count())
@foreach($properties as $property)
<div class="col-md-12">
    <div class="dashboard-listings-item fl-wrap" id="listing-item-{{ $property->id }}">
        <div class="dashboard-listings-item_img">
            <div class="bg-wrap">
                <div class="bg"
                    style="background-image: url('{{ $property->title_image ?: asset('images/all/1.jpg') }}');">
                </div>
            </div>
            <div class="overlay"></div>
            <a href="{{ isset($property->slug) ? route('bds.show', $property->slug) : '#' }}" class="color-bg">Xem</a>
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
                    <!-- Left blank for standard layout, can add heart/save icons here if needed -->
                </ul>
            </div>
        </div>
    </div>
</div>
@endforeach

@if(!$properties->hasMorePages())
<!-- Hidden flag to signal JS that this is the last page -->
<span class="no-more-items-flag" style="display: none;"></span>
@endif
@else
@if($properties->currentPage() == 1)
<div class="col-12">
    <div class="alert alert-info" style="margin-top: 20px;">Hiện tại chưa có tin đăng nào.</div>
</div>
@endif
@endif