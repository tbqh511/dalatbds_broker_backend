@extends('layouts.main')

@section('title')
    Thẻ Bài Viết (Tags)
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
                        <li class="breadcrumb-item active" aria-current="page">Thẻ Bài Viết</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section class="section">
        <div class="row">
            <!-- Form Tạo Mới -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Thêm Thẻ Mới</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.posts.tags.store') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="name">Tên</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <small class="text-muted">Tên thẻ hiển thị trên trang web.</small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="slug">Đường dẫn (Slug)</label>
                                <input type="text" class="form-control" id="slug" name="slug">
                                <small class="text-muted">Chuỗi cho URL thường là chữ thường và chỉ chứa chữ cái, số và dấu gạch ngang.</small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="description">Mô tả</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Thêm Thẻ</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Danh Sách -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Danh Sách Thẻ</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tên</th>
                                        <th>Mô tả</th>
                                        <th>Slug</th>
                                        <th>Count</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tags as $tag)
                                        <tr>
                                            <td>{{ $tag->term->name }}</td>
                                            <td>{{ Str::limit($tag->description, 50) }}</td>
                                            <td>{{ $tag->term->slug }}</td>
                                            <td>{{ $tag->count }}</td>
                                            <td>
                                                <a href="{{ route('admin.posts.tags.edit', $tag->term_taxonomy_id) }}" class="btn btn-sm btn-info"><i class="bi bi-pencil"></i></a>
                                                <form action="{{ route('admin.posts.tags.destroy', $tag->term_taxonomy_id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Chưa có thẻ nào.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $tags->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
