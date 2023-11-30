@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'dashboard'
])

@section('content')

    <div class="content">

        <div class="d-flex justify-content-between" style="gap: 10px">
            @include('pages.dashboard.charts.nfs-usage')
            @include('pages.dashboard.charts.recordings-count')
            @include('pages.dashboard.charts.recordings-size')
            @include('pages.dashboard.charts.recordings-shared')
        </div>

        <div class="d-flex justify-content-between" style="gap: 10px">
            @include('pages.dashboard.charts.co-spaces-by-usage')
            @include('pages.dashboard.charts.new-recordings-table')
        </div>

    </div>

@endsection
