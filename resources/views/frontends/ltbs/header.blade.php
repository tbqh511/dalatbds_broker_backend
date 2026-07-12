{{-- LTBS desktop header — sticky, blur. Shared across home / listing / detail. --}}
@php
    $tgBot = config('services.telegram.bot_username') ?? env('TELEGRAM_BOT_USERNAME');
    $webappUser = auth('webapp')->user();
@endphp
<header class="hdr">
    <div class="wrapc hdr-in">
        <a href="{{ route('index') }}" class="brand" aria-label="Đà Lạt BĐS">
            <img class="brandlogo" src="{{ asset('images/ltbs-logo.svg') }}" alt="Đà Lạt BĐS">
        </a>
        <nav class="nav">
            <a href="{{ route('properties.index', ['property_type' => 0]) }}">Mua bán</a>
            <a href="{{ route('properties.index', ['property_type' => 1]) }}">Cho thuê</a>
            <a href="{{ route('news.index') }}">Wiki BĐS</a>
            <a href="{{ url('/gioi-thieu') }}">Giới thiệu</a>
            <a href="{{ url('/lien-he') }}">Liên hệ</a>
        </nav>
        <div class="hdr-cta">
            @if ($webappUser)
                <a href="{{ route('webapp.desktop') }}" class="ghostlink">Bảng làm việc</a>
            @else
                <a href="{{ route('properties.index') }}" class="ghostlink">Ký gửi BĐS</a>
            @endif
            <a href="{{ $tgBot ? 'https://t.me/' . $tgBot . '?startapp' : route('webapp') }}"
                class="ds-btn ds-btn-solid ds-btn-md" target="_blank" rel="noopener">
                <svg viewBox="0 0 24 24" class="ds-ic ds-ic-18"><path d="M22 2 11 13"></path><path d="M22 2 15 22l-4-9-9-4 20-7z"></path></svg>
                Mở app
            </a>
        </div>
    </div>
</header>
