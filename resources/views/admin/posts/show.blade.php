@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ $post->post_title }}</h4>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary float-end">Quay lại</a>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Trạng thái:</strong> {{ $post->post_status == 'publish' ? 'Xuất bản' : 'Bản nháp' }}
                    </div>
                    <div class="mb-3">
                        <strong>Ngày tạo:</strong> {{ $post->post_date->format('d/m/Y H:i:s') }}
                    </div>
                    <div class="mb-3">
                        <strong>Ngày cập nhật:</strong> {{ $post->post_modified->format('d/m/Y H:i:s') }}
                    </div>
                    @if($post->post_excerpt)
                    <div class="mb-3">
                        <strong>Tóm tắt:</strong>
                        <p>{{ $post->post_excerpt }}</p>
                    </div>
                    @endif
                    <div class="mb-3">
                        <strong>Nội dung:</strong>
                        <div>{!! $post->post_content !!}</div>
                    </div>
                    <a href="{{ route('admin.posts.edit', $post->ID) }}" class="btn btn-primary">Chỉnh sửa</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
