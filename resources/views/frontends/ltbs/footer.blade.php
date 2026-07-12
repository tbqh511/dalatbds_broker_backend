{{-- LTBS desktop footer — 5 columns + newsletter card. --}}
@php
    $tgBot = config('services.telegram.bot_username');
    $chev = '<svg class="ft-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"></path></svg>';
@endphp
<footer class="ft">
    <div class="wrapc">
        {{-- Newsletter card --}}
        <div class="ft-nl">
            <div class="ft-nl-mesh"></div>
            <div class="ft-nl-orb"></div>
            <div class="ft-nl-copy">
                <div class="ft-nl-eyebrow">Bản tin thị trường</div>
                <div class="ft-nl-title">Nhận cập nhật giá & tin BĐS Đà Lạt mỗi tuần</div>
            </div>
            <form class="ft-nl-form" onsubmit="return false;">
                <input class="ft-nl-input" type="email" placeholder="Email của bạn" aria-label="Email">
                <button type="submit" class="ds-btn ds-btn-solid ds-btn-md" onclick="LTBS.toast('Cảm ơn bạn đã đăng ký!')">Đăng ký</button>
            </form>
        </div>

        <div class="ft-cols">
            <div>
                <div class="ft-brand"><img class="ftlogo" src="{{ asset('images/ltbs-logo.svg') }}" alt="Đà Lạt BĐS"></div>
                <p class="ft-desc">Mạng lưới thổ địa am hiểu từng phường Đà Lạt. Mỗi tin đều xác minh pháp lý.</p>
                <div class="ft-hours">Thứ 2 – Thứ 7: 8:00 – 18:00<br>Chủ nhật: 9:00 – 15:00</div>
            </div>
            <div>
                <h4>Liên kết hữu ích</h4>
                <ul>
                    <li><a href="{{ route('index') }}">{!! $chev !!}Trang chủ</a></li>
                    <li><a href="{{ route('news.index') }}">{!! $chev !!}Tin tức</a></li>
                    <li><a href="{{ url('/lien-he') }}">{!! $chev !!}Liên hệ</a></li>
                    <li><a href="{{ route('customer-privacy-policy') }}">{!! $chev !!}Chính sách bảo mật</a></li>
                </ul>
            </div>
            <div>
                <h4>Dành cho môi giới</h4>
                <ul>
                    <li><a href="{{ route('webapp') }}">{!! $chev !!}Đăng ký môi giới</a></li>
                    <li><a href="{{ route('properties.index') }}">{!! $chev !!}Khám phá BĐS</a></li>
                    <li><a href="{{ url('/gioi-thieu') }}">{!! $chev !!}Quy trình duyệt tin</a></li>
                    <li><a href="{{ route('agents.index') }}">{!! $chev !!}Đội ngũ môi giới</a></li>
                </ul>
            </div>
            <div>
                <h4>Thông tin liên hệ</h4>
                <ul>
                    <li class="ft-contact-row"><svg class="ft-contact-ic" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="M2 6l10 7 10-7"></path></svg><span><b>Email:</b> contact@dalatbds.com</span></li>
                    <li class="ft-contact-row"><svg class="ft-contact-ic" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg><span><b>Địa chỉ:</b> 27 Yersin, TP Đà Lạt</span></li>
                    <li class="ft-contact-row"><svg class="ft-contact-ic" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3.1-8.7A2 2 0 0 1 3.6 1.3h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L7.9 8.9a16 16 0 0 0 6 6l1-1a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"></path></svg><span><b>Điện thoại:</b> <a href="tel:0918963878">0918.96.38.78</a></span></li>
                </ul>
            </div>
            <div>
                <h4>Mở trên Telegram</h4>
                <p class="ft-store-desc">Đà Lạt BĐS chạy gọn trong Telegram — không cần cài app riêng.</p>
                <a class="ft-storebtn" href="{{ $tgBot ? 'https://t.me/' . $tgBot . '?startapp' : route('webapp') }}" target="_blank" rel="noopener">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2 11 13"></path><path d="M22 2 15 22l-4-9-9-4 20-7z"></path></svg>
                    Mở {{ $tgBot ?? 'DalatBDSBot' }}
                </a>
            </div>
        </div>
        <div class="ft-bottom">
            <div>© {{ date('Y') }} Đà Lạt BĐS. Đã đăng ký bản quyền.</div>
            <div>Điều khoản · Bảo mật · Cookie</div>
        </div>
    </div>
</footer>
