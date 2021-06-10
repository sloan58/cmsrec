@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'dashboard'
])

@section('content')
    <div class="content">
        @include('pages.dashboard.charts._header-cards')
        <div class="row">
            @include('pages.dashboard.charts.co-spaces-by-usage')
            @include('pages.dashboard.charts.new-recordings-table')
        </div>
    </div>
@endsection
