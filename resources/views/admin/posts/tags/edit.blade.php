@extends('layouts.main')

@section('title')
    Chỉnh Sửa Thẻ
@endsection

@section('page-title')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h4>@yield('title')</h4>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.posts.tags.index') }}">Thẻ Bài Viết</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Chỉnh Sửa</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.posts.tags.update', $tag->term_taxonomy_id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group mb-3">
                        <label for="name">Tên</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $tag->term->name }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="slug">Đường dẫn (Slug)</label>
                        <input type="text" class="form-control" id="slug" name="slug" value="{{ $tag->term->slug }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="description">Mô tả</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ $tag->description }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Cập Nhật</button>
                    <a href="{{ route('admin.posts.tags.index') }}" class="btn btn-secondary">Hủy</a>
                </form>
            </div>
        </div>
    </section>
@endsection
