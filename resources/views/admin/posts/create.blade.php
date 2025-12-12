@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Thêm bài viết mới</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
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

                        {{-- THUMBNAIL FIELD (Đã hợp nhất từ HEAD) --}}
                        <div class="mb-3">
                            <label for="thumbnail" class="form-label">Ảnh đại diện</label>
                            <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*">
                            @error('thumbnail')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- CATEGORIES FIELD (Đã hợp nhất từ news-post-api-crud) --}}
                        <div class="mb-3">
                            <label class="form-label">Danh mục</label>
                            <div class="card p-3">
                                @if(isset($categories))
                                    @foreach($categories as $category)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->term_id }}" id="category_{{ $category->term_id }}"
                                                {{ in_array($category->term_id, old('categories', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="category_{{ $category->term_id }}">
                                                {{ $category->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        {{-- TAGS FIELD --}}
                        <div class="mb-3">
                            <label for="tags" class="form-label">Thẻ (phân cách bằng dấu phẩy)</label>
                            <input type="text" class="form-control" id="tags" name="tags" value="{{ old('tags') }}" placeholder="Ví dụ: tin tức, công nghệ, bất động sản">
                        </div>

                        <button type="submit" class="btn btn-primary">Tạo bài viết</button>
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Hủy</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/4.25.1-lts/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('post_content', {
        versionCheck: false
    });
</script>
@endsection
