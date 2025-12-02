@extends('layouts.app')

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
                        </tbody>
                    </table>
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
