@extends('frontends.master')

@section('title', 'Đăng tin thành công')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-white p-6">
    <div class="text-center">
        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-check text-5xl text-success"></i>
        </div>
        
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Đăng tin thành công!</h1>
        <p class="text-gray-500 mb-8">Tin đăng của bạn đang chờ duyệt. Chúng tôi sẽ thông báo cho bạn ngay khi tin được hiển thị.</p>
        
        <div class="space-y-3 w-full max-w-xs mx-auto">
            <a href="{{ route('webapp.add_listing') }}" class="block w-full py-3.5 bg-primary text-white font-bold rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-600 transition-colors">
                <i class="fa-solid fa-plus mr-2"></i> Đăng tin khác
            </a>
            
            <a href="{{ route('webapp') }}" class="block w-full py-3.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors">
                <i class="fa-solid fa-house mr-2"></i> Về trang chủ
            </a>
        </div>
    </div>
</div>
@endsection
