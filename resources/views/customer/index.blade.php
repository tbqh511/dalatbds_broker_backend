@extends('layouts.main')

@section('title')
    {{ __('Customer') }}
@endsection

@section('page-title')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h4>@yield('title')</h4>

            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">

            </div>
        </div>
    </div>
@endsection


@section('content')
    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <table class="table-light" aria-describedby="mydesc" class='table-striped' id="table_list"
                            data-toggle="table" data-url="{{ url('customerList') }}" data-click-to-select="true"
                            data-side-pagination="server" data-pagination="true"
                            data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true" data-toolbar="#toolbar"
                            data-show-columns="true" data-show-refresh="true" data-fixed-columns="true"
                            data-fixed-number="1" data-fixed-right-number="1" data-trim-on-search="false"
                            data-responsive="true" data-sort-name="id" data-sort-order="desc"
                            data-pagination-successively-size="3" data-query-params="queryParams" data-show-export="true"
                            data-export-options='{ "fileName": "data-list-<?= date('d-m-y') ?>" }'>
                            <thead>
                                <tr>
                                    <th scope="col" data-field="id" data-sortable="true" data-align="center">
                                        {{ __('ID') }}</th>
                                    <th scope="col" data-field="profile" data-sortable="false" data-align="center">
                                        {{ __('Profile') }}</th>
                                    <th scope="col" data-field="name" data-sortable="true" data-align="center">
                                        {{ __('Name') }}</th>
                                    <th scope="col" data-field="mobile" data-sortable="true" data-align="center">
                                        {{ __('Number') }}</th>
                                    <th scope="col" data-field="address" data-sortable="false" data-align="center">
                                        {{ __('Address') }}</th>
                                    <th scope="col" data-field="customertotalpost" data-sortable="false"
                                        data-align="center">
                                        {{ __('Total Post') }}</th>
                                    <th scope="col" data-field="isActive" data-sortable="false" data-align="center">
                                        {{ __('Active Status') }}
                                    </th>
                                    <th scope="col" data-field="role" data-sortable="false" data-align="center">
                                        {{ __('Role') }}
                                    </th>
                                    <th scope="col" data-field="referred_by" data-sortable="false" data-align="center">
                                        {{ __('Người giới thiệu') }}</th>
                                    <th scope="col" data-field="operate" data-sortable="false" data-align="center">
                                        {{ __('Action') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        function queryParams(p) {
            return {
                sort: p.sort,
                order: p.order,
                offset: p.offset,
                limit: p.limit,
                search: p.search
            };
        }

        function disable(id) {
            $.ajax({
                url: "{{ route('customer.customerstatus') }}",
                type: "POST",
                data: {
                    '_token': "{{ csrf_token() }}",
                    "id": id,
                    "status": 0,
                },
                cache: false,
                success: function(result) {

                    if (result.error == false) {
                        Toastify({
                            text: 'Customer Deactive successfully',
                            duration: 6000,
                            close: !0,
                            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
                        }).showToast();
                        $('#table_list').bootstrapTable('refresh');
                    } else {
                        Toastify({
                            text: result.message,
                            duration: 6000,
                            close: !0,
                            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
                        }).showToast();
                        $('#table_list').bootstrapTable('refresh');
                    }

                },
                error: function(error) {

                }
            });
        }

        // Referrer change handler (admin only)
        function changeReferrer(customerId) {
            var code = prompt('Nhập mã giới thiệu mới (để trống để xóa người giới thiệu):');
            if (code === null) return; // bấm Cancel
            $.ajax({
                url: '/customer/' + customerId + '/referrer',
                type: 'PATCH',
                data: { '_token': "{{ csrf_token() }}", referral_code: code.trim() },
                success: function (result) {
                    if (!result.error) {
                        var nameEl = document.getElementById('ref-name-' + customerId);
                        if (nameEl) {
                            nameEl.innerHTML = result.referrer_name
                                ? result.referrer_name
                                : '<span class="text-muted">—</span>';
                        }
                    }
                    Toastify({
                        text: result.message,
                        duration: 3000, close: true,
                        backgroundColor: result.error
                            ? 'linear-gradient(to right, #ff5f6d, #ffc371)'
                            : 'linear-gradient(to right, #00b09b, #96c93d)'
                    }).showToast();
                },
                error: function (xhr) {
                    var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Lỗi hệ thống';
                    Toastify({ text: msg, duration: 3000, close: true, backgroundColor: 'linear-gradient(to right, #ff5f6d, #ffc371)' }).showToast();
                }
            });
        }

        // Role change handler — delegated from bootstrap-table rendered HTML
        $(document).on('change', '.role-select', function () {
            const id = $(this).data('id');
            const role = $(this).val();
            $.ajax({
                url: '/customer/' + id + '/role',
                type: 'PATCH',
                data: { '_token': "{{ csrf_token() }}", role: role },
                success: function (result) {
                    Toastify({
                        text: result.error ? result.message : 'Role đã cập nhật',
                        duration: 3000, close: true,
                        backgroundColor: result.error
                            ? 'linear-gradient(to right, #ff5f6d, #ffc371)'
                            : 'linear-gradient(to right, #00b09b, #96c93d)'
                    }).showToast();
                }
            });
        });

        function active(id) {
            $.ajax({
                url: "{{ route('customer.customerstatus') }}",
                type: "POST",
                data: {
                    '_token': "{{ csrf_token() }}",
                    "id": id,
                    "status": 1,
                },
                cache: false,
                success: function(result) {

                    if (result.error == false) {
                        Toastify({
                            text: 'Customer Active successfully',
                            duration: 6000,
                            close: !0,
                            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
                        }).showToast();
                        $('#table_list').bootstrapTable('refresh');
                    } else {
                        Toastify({
                            text: result.message,
                            duration: 6000,
                            close: !0,
                            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
                        }).showToast();
                        $('#table_list').bootstrapTable('refresh');
                    }
                },
                error: function(error) {

                }
            });
        }
    </script>
@endsection
