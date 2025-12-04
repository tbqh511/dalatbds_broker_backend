@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Chỉnh sửa bài viết</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.posts.update', $post->ID) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="post_title" class="form-label">Tiêu đề bài viết</label>
                            <input type="text" class="form-control" id="post_title" name="post_title" value="{{ $post->post_title }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="post_excerpt" class="form-label">Tóm tắt</label>
                            <textarea class="form-control" id="post_excerpt" name="post_excerpt" rows="3">{{ $post->post_excerpt }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="post_content" class="form-label">Nội dung bài viết</label>
                            <textarea class="form-control" id="post_content" name="post_content" rows="10" required>{{ $post->post_content }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="post_status" class="form-label">Trạng thái</label>
                            <select class="form-control" id="post_status" name="post_status" required>
                                <option value="publish" {{ $post->post_status == 'publish' ? 'selected' : '' }}>Xuất bản</option>
                                <option value="draft" {{ $post->post_status == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Danh mục</label>
                            <div class="card p-3">
                                @foreach($categories as $category)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->term_id }}" id="category_{{ $category->term_id }}"
                                            {{ in_array($category->term_id, $selectedCategories ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="category_{{ $category->term_id }}">
                                            {{ $category->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="tags" class="form-label">Thẻ (phân cách bằng dấu phẩy)</label>
                            <input type="text" class="form-control" id="tags" name="tags" value="{{ $tags ?? '' }}" placeholder="Ví dụ: tin tức, công nghệ, bất động sản">
                        </div>

                        <button type="submit" class="btn btn-primary">Cập nhật bài viết</button>
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
