<div class="listing-item">
    <article class="geodir-category-listing fl-wrap">
        <div class="geodir-category-img fl-wrap">
                <a href="{{ route('bds.show', ['slug' => $productCard->slug]) }}" class="geodir-category-img_item">
                    <img src="{{ $productCard->title_image }}" alt="{{ $productCard->title }}">
                    <div class="overlay"></div>
                </a>
                <div class="geodir-category-location">
                    <a href="{{ route('bds.show', ['slug' => $productCard->slug]) }}"><i class="fas fa-map-marker-alt"></i>
                        <span>
                            {{ $productCard->address_location }}
                        </span></a>
                </div>
                <ul class="list-single-opt_header_cat">
                    <li><a href="{{ route('properties.index') }}" class="cat-opt blue-bg">{{ $productCard->category->category }}</a></li>
                    <li><a href="{{ route('properties.index') }}" class="cat-opt color-bg">{{ $productCard->created_at->diffForHumans() }}</a></li>
                </ul>
                <div class="geodir-category-listing_media-list">
                    <span><i class="fas fa-camera"></i> {{ $productCard->imagesCount }}</span>
                </div>
        </div>
        <div class="geodir-category-content fl-wrap">
            <h3 class="title-sin_item">
                <a href="{{ route('bds.show', ['slug' => $productCard->slug]) }}">{{ $productCard->title }}</a>
            </h3>
            <div class="geodir-category-content_price">{{ $productCard->formatted_prices }}</div>
            <p> {{ $productCard->description }}</p>
            <div class="geodir-category-content-details">
                <ul>
                    @if ($productCard->area)
                        <li>
                            <i class="fas fa-arrows-alt"></i>
                            <span>{{ number_format((float) $productCard->area) }} m²</span>
                        </li>
                    @endif
                    @if ($productCard->number_room)
                        <li><i class="fas fa-door-open"></i><span>{{ number_format((float) $productCard->number_room) }}</span></li>
                    @endif
                    @if ($productCard->number_floor)
                        <li><i class="fas fa-layer-group"></i><span>{{ number_format((float) $productCard->number_floor) }}</span></li>
                    @endif
                </ul>
            </div>
            <div class="geodir-category-footer fl-wrap">
                <a href="{{ route('agent.showid', ['id' => $productCard->added_by]) }}" class="gcf-company">
                        <img src="{{ $productCard->agent ? ($productCard->agent->profile ? $productCard->agent->profile : 'https://dalatbds.com/images/users/1693209486.1303.png') : 'https://dalatbds.com/images/users/1693209486.1303.png' }}" alt="Đà Lạt BDS">

                        <span>{{ $productCard->agent ? ($productCard->agent->name ?: 'Đà Lạt BDS') : 'Đà Lạt BDS' }}</span>
                    </a>
                <div class="listing-rating card-popup-rainingvis tolt" data-microtip-position="top" data-tooltip="Good"
                    data-starrating2="5"></div>
            </div>
        </div>
    </article>
</div>
