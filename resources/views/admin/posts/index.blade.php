@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Quản lý bài viết
                        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary float-end">Thêm bài viết mới</a>
                    </h4>
                </div>
                            <div class="card-body">
                                @if(session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
<<<<<<< Updated upstream

<<<<<<< Updated upstream
                    <div class="row mb-3" id="posts-filters">
                        <div class="col-md-4">
                            <input id="post_search" type="text" class="form-control" placeholder="Tìm tiêu đề hoặc nội dung">
                        </div>
                        <div class="col-md-4">
                            <select id="post_category" class="form-select">
                                <option value="">-- Chọn danh mục --</option>
                                @if(isset($categories))
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->term_id }}">{{ $cat->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select id="post_status_filter" class="form-select">
                                <option value="">-- Chọn trạng thái --</option>
                                <option value="publish">Xuất bản</option>
                                <option value="draft">Bản nháp</option>
                            </select>
                        </div>
                    </div>

                    <table id="posts-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tiêu đề</th>
                                <th>Trạng thái</th>
                                <th>Ảnh</th>
                                <th>Ngày tạo</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(isset($posts) && $posts->count())
                            @foreach($posts as $post)
                                @php
                                    $thumb = optional($post->meta->where('meta_key', '_thumbnail')->first())->meta_value;
                                @endphp
                                <tr>
                                    <td>{{ $post->ID }}</td>
                                    <td>{{ $post->post_title }}</td>
                                    <td>{{ $post->post_status }}</td>
                                    <td>
                                        @if($thumb)
                                            <img src="{{ asset('storage/' . $thumb) }}" alt="thumb" style="max-width:80px; height:auto;" />
                                        @endif
                                    </td>
                                    <td>{{ optional($post->post_date)->format('Y-m-d H:i:s') ?? '—' }}</td>
                                    <td>
                                        <a href="{{ route('admin.posts.edit', $post->ID) }}" class="btn btn-sm btn-primary">Sửa</a>
                                        <form action="{{ route('admin.posts.destroy', $post->ID) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    @if(isset($posts))
                        <div class="mt-2">
                            {{ $posts->links() }}
                        </div>
                    @endif
=======
=======

>>>>>>> Stashed changes
                                <div class="row mb-3" id="posts-filters">
                                    <div class="col-md-4">
                                        <input id="post_search" type="text" class="form-control" placeholder="Tìm tiêu đề hoặc nội dung">
                                    </div>
                                    <div class="col-md-4">
                                        <select id="post_category" class="form-select">
                                            <option value="">-- Chọn danh mục --</option>
                                            @if(isset($categories))
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat->term_id }}">{{ $cat->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select id="post_status_filter" class="form-select">
                                            <option value="">-- Chọn trạng thái --</option>
                                            <option value="publish">Xuất bản</option>
                                            <option value="draft">Bản nháp</option>
                                        </select>
                                    </div>
                                </div>

                                <table id="posts-table" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Tiêu đề</th>
                                            <th>Trạng thái</th>
                                            <th>Ảnh</th>
                                            <th>Ngày tạo</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var table = $('#posts-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.posts.list") }}',
            data: function(d) {
                d.category = $('#post_category').val();
                d.status = $('#post_status_filter').val();
                d.search_text = $('#post_search').val();
            }
        },
        columns: [
            { data: 'ID', name: 'ID' },
            { data: 'post_title', name: 'post_title' },
            { data: 'post_status', name: 'post_status' },
            { data: 'thumbnail', name: 'thumbnail', orderable: false, searchable: false },
            { data: 'post_date', name: 'post_date' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });

<<<<<<< Updated upstream
<<<<<<< Updated upstream
    // Filters
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
    $('#post_category, #post_status_filter').on('change', function() {
        table.ajax.reload();
    });

    $('#post_search').on('keyup', function(e) {
        if (e.key === 'Enter' || $(this).val().length === 0) {
            table.ajax.reload();
        }
    });
});
</script>
@endsection
