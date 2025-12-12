@extends('layouts.main')

@section('title')
    {{ __('Quản lý bài viết') }}
@endsection

@section('page-title')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h4>@yield('title')</h4>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <!-- Breadcrumb could go here -->
            </div>
        </div>
    </div>
@endsection

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-12 d-flex justify-content-end">
                    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">{{ __('Thêm bài viết mới') }}</a>
                </div>
            </div>
        </div>

        <hr>

        <div class="card-body">
            <!-- Toolbar for filters -->
            <div class="row" id="toolbar">
                <div class="col-sm-4 mb-2">
                    <input id="post_search" type="text" class="form-control form-control-sm" placeholder="Tìm tiêu đề hoặc nội dung...">
                </div>
                <div class="col-sm-4 mb-2">
                    <select id="post_category" class="form-select form-control-sm">
                        <option value="">{{ __('-- Tất cả danh mục --') }}</option>
                        @if(isset($categories))
                            @foreach($categories as $cat)
                                <option value="{{ $cat->term_id }}">{{ $cat->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-sm-4 mb-2">
                    <select id="post_status_filter" class="form-select form-control-sm">
                        <option value="">{{ __('-- Tất cả trạng thái --') }}</option>
                        <option value="publish">{{ __('Xuất bản') }}</option>
                        <option value="draft">{{ __('Bản nháp') }}</option>
                    </select>
                </div>
            </div>

            <!-- Bootstrap Table -->
            <div class="row">
                <div class="col-12">
                    <table class="table-light table-striped"
                           id="table_list"
                           data-toggle="table"
                           data-url="{{ route('admin.posts.list') }}"
                           data-click-to-select="true"
                           data-side-pagination="server"
                           data-pagination="true"
                           data-page-list="[10, 20, 50, 100, 200]"
                           data-search="false"
                           data-toolbar="#toolbar"
                           data-show-columns="true"
                           data-show-refresh="true"
                           data-fixed-columns="true"
                           data-fixed-number="1"
                           data-fixed-right-number="1"
                           data-trim-on-search="false"
                           data-responsive="true"
                           data-sort-name="id"
                           data-sort-order="desc"
                           data-query-params="queryParams"
                           data-response-handler="responseHandler">
                        <thead>
                            <tr>
                                <th data-field="ID" data-sortable="true" data-width="50">{{ __('ID') }}</th>
                                <th data-field="post_title" data-sortable="true" data-formatter="titleFormatter">{{ __('Tiêu đề') }}</th>
                                <th data-field="post_status" data-sortable="true" data-formatter="statusFormatter" data-width="100">{{ __('Trạng thái') }}</th>
                                <th data-field="thumbnail" data-sortable="false" data-width="100">{{ __('Ảnh') }}</th>
                                <th data-field="post_date" data-sortable="true" data-width="150">{{ __('Ngày tạo') }}</th>
                                <th data-field="actions" data-sortable="false" data-width="100">{{ __('Hành động') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('css')
<!-- Inherit Bootstrap Table CSS from main layout includes if present, or add here if missing -->
@endsection

@section('script')
<script>
    // Custom Formatter for Title (Link to Edit)
    function titleFormatter(value, row, index) {
        var editUrl = '{{ route("admin.posts.edit", ":id") }}'.replace(':id', row.ID);
        return '<a href="' + editUrl + '" class="fw-bold">' + value + '</a>';
    }

    // Custom Formatter for Status
    function statusFormatter(value, row, index) {
        if (value === 'publish') {
            return '<span class="badge bg-success">Xuất bản</span>';
        } else if (value === 'draft') {
            return '<span class="badge bg-secondary">Bản nháp</span>';
        }
        return value;
    }

    // Map DataTables response format to Bootstrap Table format
    function responseHandler(res) {
        return {
            "rows": res.data,
            // Use recordsFiltered instead of recordsTotal so pagination works correctly with filters
            "total": res.recordsFiltered
        };
    }

    // Query Params to send to server
    function queryParams(p) {
        return {
            // Map 'offset' (bootstrap-table) to 'start' (controller expected)
            start: p.offset,
            // Map 'limit' (bootstrap-table) to 'length' (controller expected)
            length: p.limit,
            search_text: $('#post_search').val(), // Custom search input
            category: $('#post_category').val(),
            status: $('#post_status_filter').val(),
            sort: p.sort,
            order: p.order
        };
    }

    $(document).ready(function() {
        // Refresh table on filter change
        $('#post_category, #post_status_filter').on('change', function() {
            $('#table_list').bootstrapTable('refresh');
        });

        // Debounce search input
        var searchTimeout;
        $('#post_search').on('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                $('#table_list').bootstrapTable('refresh');
            }, 500);
        });
    });
</script>
@endsection
