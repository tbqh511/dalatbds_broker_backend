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

                    <div class="row mb-3" id="posts-filters">
                        <div class="col-md-4">
                            <input id="post_search" type="text" class="form-control" placeholder="Tìm tiêu đề hoặc nội dung...">
                        </div>
                        <div class="col-md-4">
                            <select id="post_category" class="form-select">
                                <option value="">-- Tất cả danh mục --</option>
                                @if(isset($categories))
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->term_id }}">{{ $cat->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select id="post_status_filter" class="form-select">
                                <option value="">-- Tất cả trạng thái --</option>
                                <option value="publish">Xuất bản</option>
                                <option value="draft">Bản nháp</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
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
                                {{-- Data will be loaded via AJAX --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
(function($) {
    "use strict";
    $(document).ready(function() {
        if (!$.fn.DataTable) {
            console.warn('DataTables library is not loaded. Filtering may not work.');
            return;
        }

        try {
            var table = $('#posts-table').DataTable({
                processing: true,
                serverSide: true,
                // Hide default search box since we have a custom one
                dom: 'lrtip',
                ajax: {
                    url: '{{ route("admin.posts.list") }}',
                    data: function(d) {
                        d.category = $('#post_category').val();
                        d.status = $('#post_status_filter').val();
                        d.search_text = $('#post_search').val();
                    },
                    error: function(xhr, error, code) {
                        console.error('DataTables error:', error, code);
                        console.log('Response:', xhr.responseText);
                        alert('Không thể tải dữ liệu bài viết. Vui lòng thử lại sau.');
                    }
                },
                columns: [
                    { data: 'ID', name: 'ID' },
                    {
                        data: 'post_title',
                        name: 'post_title',
                        render: function(data, type, row) {
                            // Create link to edit page
                            var editUrl = '{{ route("admin.posts.edit", ":id") }}'.replace(':id', row.ID);
                            return '<a href="' + editUrl + '" class="text-primary fw-bold">' + data + '</a>';
                        }
                    },
                    {
                        data: 'post_status',
                        name: 'post_status',
                        render: function(data) {
                            if (data === 'publish') {
                                return '<span class="badge bg-success">Xuất bản</span>';
                            } else if (data === 'draft') {
                                return '<span class="badge bg-secondary">Bản nháp</span>';
                            }
                            return data;
                        }
                    },
                    { data: 'thumbnail', name: 'thumbnail', orderable: false, searchable: false },
                    { data: 'post_date', name: 'post_date' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                order: [[4, 'desc']], // Default sort by post_date desc
                language: {
                    processing: "Đang xử lý...",
                    search: "Tìm kiếm:",
                    lengthMenu: "Hiển thị _MENU_ mục",
                    info: "Đang hiển thị _START_ đến _END_ trong tổng số _TOTAL_ mục",
                    infoEmpty: "Đang hiển thị 0 đến 0 trong tổng số 0 mục",
                    infoFiltered: "(được lọc từ tổng số _MAX_ mục)",
                    infoPostFix: "",
                    loadingRecords: "Đang tải...",
                    zeroRecords: "Không tìm thấy kết quả nào",
                    emptyTable: "Không có dữ liệu trong bảng",
                    paginate: {
                        first: "Đầu",
                        previous: "Trước",
                        next: "Tiếp",
                        last: "Cuối"
                    },
                    aria: {
                        sortAscending: ": kích hoạt để sắp xếp cột tăng dần",
                        sortDescending: ": kích hoạt để sắp xếp cột giảm dần"
                    }
                }
            });

            // Custom Filter Events
            $('#post_category').on('change', function() {
                table.ajax.reload(); // Reload table data with new category param
            });

            $('#post_status_filter').on('change', function() {
                table.ajax.reload(); // Reload table data with new status param
            });

            // Debounce search input
            var searchTimeout;
            $('#post_search').on('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    table.ajax.reload();
                }, 500);
            });

        } catch (e) {
            console.error('Error initializing DataTables:', e);
        }
    });
})(jQuery);
</script>
@endsection
