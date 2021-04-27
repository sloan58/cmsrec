@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'cospaces'
])

@section('content')
    <div class="content">
        @include('components.flash-message')
        <livewire:cms-co-space-table />
    </div>
@endsection
