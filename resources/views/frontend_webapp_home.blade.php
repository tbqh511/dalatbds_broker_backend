@extends('frontends.master')

@section('content')
<div class="content">
    @include('frontends.components.home_breadcrumb', ['title' => 'WebApp'])
    @include('frontends.components.webapp_main')
    <div class="limit-box fl-wrap"></div>
</div>
@endsection
