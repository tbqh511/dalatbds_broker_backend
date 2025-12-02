@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Thêm bài viết mới</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.posts.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="post_title" class="form-label">Tiêu đề bài viết</label>
                            <input type="text" class="form-control" id="post_title" name="post_title" value="{{ old('post_title') }}" required>
                            @error('post_title')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="post_excerpt" class="form-label">Tóm tắt</label>
                            <textarea class="form-control" id="post_excerpt" name="post_excerpt" rows="3">{{ old('post_excerpt') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="post_content" class="form-label">Nội dung bài viết</label>
                            <textarea class="form-control" id="post_content" name="post_content" rows="10" required>{{ old('post_content') }}</textarea>
                            @error('post_content')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="post_status" class="form-label">Trạng thái</label>
                            <select class="form-control" id="post_status" name="post_status" required>
                                <option value="publish" {{ old('post_status') == 'publish' ? 'selected' : '' }}>Xuất bản</option>
                                <option value="draft" {{ old('post_status') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                            </select>
                            @error('post_status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Tạo bài viết</button>
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Hủy</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('post_content');
</script>
@endsection
