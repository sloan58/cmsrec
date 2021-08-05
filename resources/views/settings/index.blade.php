@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'settings'
])

@section('content')
    <div class="content">
        @include('components.flash-message')
        <livewire:ldap-settings />
        <livewire:nfs-settings />
    </div>

@endsection
