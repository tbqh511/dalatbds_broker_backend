@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Chỉnh sửa bài viết</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.posts.update', $post->ID) }}" method="POST" enctype="multipart/form-data">
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

                        {{-- TAGS FIELD - UPDATED TO SELECT2 --}}
                        <div class="mb-3">
                            <label for="tags" class="form-label">Thẻ</label>
                            <select class="form-control" id="tags" name="tags[]" multiple="multiple">
                                @if(isset($allTags))
                                    @foreach($allTags as $tag)
                                        <option value="{{ $tag->name }}"
                                            {{ in_array($tag->name, $currentTags ?? []) ? 'selected' : '' }}
                                        >{{ $tag->name }}</option>
                                    @endforeach
                                @endif
                                {{-- Add tags that are attached to post but not in allTags list (just in case) --}}
                                @if(isset($currentTags))
                                    @foreach($currentTags as $tagName)
                                        @if(isset($allTags) && !$allTags->contains('name', $tagName))
                                             <option value="{{ $tagName }}" selected>{{ $tagName }}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                            <small class="text-muted">Nhập tên thẻ mới và nhấn Enter hoặc chọn từ danh sách.</small>
                        </div>

                        <div class="mb-3">
                            <label for="thumbnail" class="form-label">Ảnh đại diện</label>
                            @php
                                // Standardized thumbnail display logic - UPDATED PRIORITY
                                $thumbUrl = null;
                                $thumbMeta = $post->meta->where('meta_key', '_thumbnail')->first();
                                if ($thumbMeta && $thumbMeta->meta_value) {
                                    // PRIORITY 1: Check direct public asset copy (most reliable if symlink fails)
                                    if (file_exists(public_path('assets/images/posts/' . basename($thumbMeta->meta_value)))) {
                                        $thumbUrl = asset('assets/images/posts/' . basename($thumbMeta->meta_value));
                                    }
                                    // PRIORITY 2: Check storage via symlink
                                    elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($thumbMeta->meta_value)) {
                                        $thumbUrl = \Illuminate\Support\Facades\Storage::url($thumbMeta->meta_value);
                                    }
                                }
                            @endphp
                            @if($thumbUrl)
                                <div class="mb-2">
                                    <img src="{{ $thumbUrl }}" alt="Ảnh đại diện hiện tại" style="max-width:200px;">
                                </div>
                            @endif
                            <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*">
                            @error('thumbnail')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Cập nhật bài viết</button>
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Hủy</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    // Wrap all jQuery dependent code in a ready block or IIFE
    (function($) {
        "use strict";
        $(document).ready(function() {
            // Initialize CKEditor
            if (typeof CKEDITOR !== 'undefined') {
                CKEDITOR.replace('post_content', {
                    versionCheck: false
                });
            } else {
                console.warn('CKEditor library not loaded.');
            }

            // Initialize Select2 for Tags
            if ($.fn.select2) {
                $('#tags').select2({
                    tags: true,
                    tokenSeparators: [','],
                    placeholder: "Chọn hoặc nhập thẻ...",
                    allowClear: true,
                    width: '100%'
                });
            } else {
                console.warn('Select2 library not loaded.');
            }
        });
    })(jQuery);
</script>
@endsection
