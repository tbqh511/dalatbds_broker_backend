@extends('frontends.master')

@section('title', 'Chính sách bảo mật - Đà Lạt BDS')

@section('content')
<div class="container" style="padding-top: 150px; padding-bottom: 80px;">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="privacy-policy-content">
                <h1 class="text-center mb-5" style="font-weight: 700; color: #2E3F6E;">CHÍNH SÁCH BẢO MẬT</h1>

                <div class="intro mb-4">
                    <p>Chào mừng bạn đến với <strong>Đà Lạt BDS</strong>. Chúng tôi cam kết bảo vệ thông tin cá nhân của bạn và tôn trọng quyền riêng tư của bạn. Chính sách bảo mật này giải thích cách chúng tôi thu thập, sử dụng, bảo vệ và chia sẻ thông tin cá nhân của bạn khi bạn truy cập hoặc sử dụng dịch vụ trên website của chúng tôi.</p>
                </div>

                <div class="policy-section mb-4">
                    <h3 style="font-weight: 600; color: #3270FC; margin-bottom: 15px;">1. Thông tin chúng tôi thu thập</h3>
                    <p>Chúng tôi có thể thu thập các loại thông tin sau đây khi bạn sử dụng dịch vụ:</p>
                    <ul style="list-style-type: disc; padding-left: 20px; line-height: 1.6;">
                        <li><strong>Thông tin cá nhân:</strong> Họ tên, địa chỉ email, số điện thoại, địa chỉ liên hệ khi bạn đăng ký tài khoản, đăng tin hoặc liên hệ với chúng tôi.</li>
                        <li><strong>Thông tin tài sản:</strong> Các thông tin liên quan đến bất động sản bạn đăng bán hoặc cho thuê (địa chỉ, hình ảnh, giá cả, giấy tờ pháp lý...).</li>
                        <li><strong>Thông tin kỹ thuật:</strong> Địa chỉ IP, loại trình duyệt, thời gian truy cập và các dữ liệu cookie để cải thiện trải nghiệm người dùng.</li>
                    </ul>
                </div>

                <div class="policy-section mb-4">
                    <h3 style="font-weight: 600; color: #3270FC; margin-bottom: 15px;">2. Mục đích sử dụng thông tin</h3>
                    <p>Thông tin của bạn được sử dụng cho các mục đích sau:</p>
                    <ul style="list-style-type: disc; padding-left: 20px; line-height: 1.6;">
                        <li>Cung cấp và quản lý các dịch vụ đăng tin, tìm kiếm bất động sản.</li>
                        <li>Liên hệ, hỗ trợ và giải đáp thắc mắc của khách hàng.</li>
                        <li>Gửi thông báo về các thay đổi chính sách, cập nhật dịch vụ hoặc các chương trình khuyến mãi (nếu bạn đồng ý nhận).</li>
                        <li>Phân tích và cải thiện chất lượng dịch vụ, giao diện website.</li>
                        <li>Ngăn chặn các hành vi gian lận, lừa đảo hoặc vi phạm pháp luật.</li>
                    </ul>
                </div>

                <div class="policy-section mb-4">
                    <h3 style="font-weight: 600; color: #3270FC; margin-bottom: 15px;">3. Bảo mật thông tin</h3>
                    <p>Chúng tôi áp dụng các biện pháp an ninh kỹ thuật và tổ chức phù hợp để bảo vệ thông tin cá nhân của bạn khỏi việc truy cập, sử dụng hoặc tiết lộ trái phép. Dữ liệu của bạn được lưu trữ trên các máy chủ bảo mật và chỉ những nhân viên có thẩm quyền mới được phép truy cập.</p>
                </div>

                <div class="policy-section mb-4">
                    <h3 style="font-weight: 600; color: #3270FC; margin-bottom: 15px;">4. Chia sẻ thông tin với bên thứ ba</h3>
                    <p>Chúng tôi cam kết <strong>không bán, trao đổi hoặc chuyển giao</strong> thông tin cá nhân của bạn cho bên thứ ba, ngoại trừ các trường hợp sau:</p>
                    <ul style="list-style-type: disc; padding-left: 20px; line-height: 1.6;">
                        <li>Các đối tác cung cấp dịch vụ hỗ trợ (như thanh toán, gửi email) nhưng buộc phải tuân thủ cam kết bảo mật.</li>
                        <li>Khi có yêu cầu từ cơ quan pháp luật có thẩm quyền.</li>
                        <li>Để bảo vệ quyền lợi, tài sản hoặc sự an toàn của Đà Lạt BDS và người dùng khác.</li>
                    </ul>
                </div>

                <div class="policy-section mb-4">
                    <h3 style="font-weight: 600; color: #3270FC; margin-bottom: 15px;">5. Quyền lợi của người dùng</h3>
                    <p>Bạn có các quyền sau đối với thông tin cá nhân của mình:</p>
                    <ul style="list-style-type: disc; padding-left: 20px; line-height: 1.6;">
                        <li><strong>Truy cập và chỉnh sửa:</strong> Bạn có thể xem và cập nhật thông tin cá nhân của mình bất cứ lúc nào thông qua trang quản lý tài khoản.</li>
                        <li><strong>Yêu cầu xóa:</strong> Bạn có quyền yêu cầu chúng tôi xóa thông tin cá nhân của bạn khỏi hệ thống, trừ khi pháp luật có quy định khác.</li>
                        <li><strong>Hủy đăng ký:</strong> Bạn có thể từ chối nhận các email tiếp thị bất cứ lúc nào bằng cách nhấp vào liên kết hủy đăng ký trong email.</li>
                    </ul>
                </div>

                <div class="policy-section mb-4">
                    <h3 style="font-weight: 600; color: #3270FC; margin-bottom: 15px;">6. Cookie và công nghệ theo dõi</h3>
                    <p>Website sử dụng cookie để ghi nhớ sở thích của bạn và cung cấp trải nghiệm tốt hơn. Bạn có thể tùy chỉnh cài đặt trình duyệt để từ chối cookie, tuy nhiên điều này có thể ảnh hưởng đến một số tính năng của website.</p>
                </div>

                <div class="policy-section mb-4">
                    <h3 style="font-weight: 600; color: #3270FC; margin-bottom: 15px;">7. Thay đổi chính sách</h3>
                    <p>Chúng tôi có thể cập nhật chính sách bảo mật này theo thời gian. Mọi thay đổi sẽ được thông báo trên website và có hiệu lực ngay khi đăng tải. Bạn nên thường xuyên kiểm tra trang này để cập nhật các thay đổi mới nhất.</p>
                </div>

                <div class="policy-section mb-4">
                    <h3 style="font-weight: 600; color: #3270FC; margin-bottom: 15px;">8. Thông tin liên hệ</h3>
                    <p>Nếu bạn có bất kỳ thắc mắc nào về Chính sách bảo mật này, vui lòng liên hệ với chúng tôi qua:</p>
                    <ul style="list-style-type: none; padding-left: 0; line-height: 1.6;">
                        <li><strong>Email:</strong> [NỘI DUNG CẦN CẬP NHẬT - VD: support@dalatbds.com]</li>
                        <li><strong>Hotline:</strong> [NỘI DUNG CẦN CẬP NHẬT - VD: 0918.96.38.78]</li>
                        <li><strong>Địa chỉ:</strong> [NỘI DUNG CẦN CẬP NHẬT - VD: 27 Yersin, TP Đà Lạt]</li>
                    </ul>
                </div>
                
                {{-- Dữ liệu động từ Database (Tạm ẩn để hiển thị mẫu) --}}
                {{-- 
                <hr>
                <div class="dynamic-content mt-5">
                    @php
                        if(isset($privacy_policy) && !empty($privacy_policy->data)) {
                            echo $privacy_policy->data;
                        }
                    @endphp
                </div> 
                --}}
            </div>
        </div>
    </div>
</div>
@endsection