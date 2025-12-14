@extends('layouts.main')

@section('title')
    Danh Mục Bài Viết
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
                        <li class="breadcrumb-item active" aria-current="page">Danh Mục Bài Viết</li>
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
                        <h4 class="card-title">Thêm Danh Mục Mới</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.posts.categories.store') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="name">Tên</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <small class="text-muted">Tên danh mục hiển thị trên trang web.</small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="slug">Đường dẫn (Slug)</label>
                                <input type="text" class="form-control" id="slug" name="slug">
                                <small class="text-muted">Chuỗi cho URL thường là chữ thường và chỉ chứa chữ cái, số và dấu gạch ngang.</small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="parent">Danh mục cha</label>
                                <select class="form-control" id="parent" name="parent">
                                    <option value="0">Trống</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->term_id }}">{{ $cat->term->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="description">Mô tả</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Thêm Danh Mục</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Danh Sách -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Danh Sách Danh Mục</h4>
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
                                    @forelse($categories as $category)
                                        <tr>
                                            <td>{{ $category->term->name }}</td>
                                            <td>{{ Str::limit($category->description, 50) }}</td>
                                            <td>{{ $category->term->slug }}</td>
                                            <td>{{ $category->count }}</td>
                                            <td>
                                                <a href="{{ route('admin.posts.categories.edit', $category->term_taxonomy_id) }}" class="btn btn-sm btn-info"><i class="bi bi-pencil"></i></a>
                                                <form action="{{ route('admin.posts.categories.destroy', $category->term_taxonomy_id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Chưa có danh mục nào.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $categories->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
