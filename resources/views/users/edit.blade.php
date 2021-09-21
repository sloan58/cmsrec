@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'users'
])

@section('content')
    <livewire:user-edit-page :user="$user"/>
@endsection
