@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'recordings'
])

@section('content')
    <div class="content">
        @include('components.flash-message')
        <div class="row">
            <div class="col-md-12">
                @foreach($spacesWithRecordings as $space)
                    @if(count($space['recordings']))
                        <livewire:co-space-videos-card :space="$space"/>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

@endsection
