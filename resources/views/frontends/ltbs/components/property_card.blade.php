{{-- LTBS reusable property card. Expects $property (App\Models\Property). --}}
@php
    /** @var \App\Models\Property $property */
    $p = $property ?? $productCard;
    $area = $p->area ?? null;
    $rooms = $p->number_room ?? null;
    $verified = !empty($p->approved_at);
    $isRent = (int) $p->property_type === 1;
@endphp
<a href="{{ route('bds.show', ['slug' => $p->slug]) }}" class="hc-card">
    <div class="hc-img" style="background-image:url('{{ $p->title_image }}')">
        <span class="hc-tag">{{ $p->type }}</span>
        @if ($verified)
            <span class="hc-verify">
                <svg viewBox="0 0 24 24" class="ds-ic ds-ic-14"><path d="M22 11.1V12a10 10 0 1 1-5.9-9.1"></path><path d="M22 4 12 14l-3-3"></path></svg>
                Đã xác minh
            </span>
        @endif
        <button type="button" class="hc-heart" aria-label="Lưu tin"
            onclick="event.preventDefault();LTBS.toggleFav({{ $p->id }}, this)">
            <svg viewBox="0 0 24 24" class="ds-ic ds-ic-18"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"></path></svg>
        </button>
    </div>
    <div class="hc-body">
        <div class="hc-price">
            <span class="hc-pricev">{{ $p->formatted_prices }}</span>
            @if ($p->avg_price_per_m2 ?? false)
                <span class="hc-pricem2">· {{ $p->avg_price_per_m2 }}</span>
            @endif
        </div>
        <div class="hc-title">{{ $p->title }}</div>
        <div class="hc-addr">
            <svg viewBox="0 0 24 24" class="ds-ic ds-ic-14"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            {{ $p->address_location ?: 'TP Đà Lạt' }}
        </div>
        <div class="hc-metarow">
            @if ($area)
                <span class="hc-meta">
                    <svg viewBox="0 0 24 24" class="ds-ic ds-ic-16"><path d="M3 3h18v18H3z"></path><path d="M3 9h18M9 3v18"></path></svg>
                    {{ $area }} m²
                </span>
            @endif
            @if ($rooms)
                <span class="hc-meta">
                    <svg viewBox="0 0 24 24" class="ds-ic ds-ic-16"><path d="M2 4v16M2 8h18a2 2 0 0 1 2 2v10M2 17h20M6 8V6a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2"></path></svg>
                    {{ $rooms }} PN
                </span>
            @endif
            <span class="hc-meta">
                <svg viewBox="0 0 24 24" class="ds-ic ds-ic-16"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>
                {{ $p->created_at?->diffForHumans() }}
            </span>
        </div>
    </div>
</a>
