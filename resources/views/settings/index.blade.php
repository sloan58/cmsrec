@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'settings'
])

@section('content')
    <div class="content">
        @include('components.flash-message')
        @include('settings.ldap')
        @include('settings.nfs')
    </div>

@endsection
