@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'users'
])

@section('content')
    <div class="content">
        @include('components.flash-message')
        <livewire:users-table />
    </div>

    @include(
    'components.delete-modal', [
        'heading' => 'Delete User',
        'body' => "Are you sure you'd like to delete this user?"
    ])
@endsection
