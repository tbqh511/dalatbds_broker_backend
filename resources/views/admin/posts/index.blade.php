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

                    <table id="posts-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tiêu đề</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(isset($posts) && $posts->count())
                            @foreach($posts as $post)
                                <tr>
                                    <td>{{ $post->ID }}</td>
                                    <td>{{ $post->post_title }}</td>
                                    <td>{{ $post->post_status }}</td>
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
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#posts-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.posts.list") }}',
        columns: [
            { data: 'ID', name: 'ID' },
            { data: 'post_title', name: 'post_title' },
            { data: 'post_status', name: 'post_status' },
            { data: 'post_date', name: 'post_date' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });
});
</script>
@endsection
