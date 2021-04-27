@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'recordings'
])

@section('content')
    <div class="content">
        @include('components.flash-message')
        <div class="row">
            <div class="col-md-12">
                @foreach($cmsCoSpaces as $space)
                    @if($space->cmsRecordings()->count())
                        <h1 class="display-4 mb-3"><span style="border-bottom: 2px solid #ddd;">{{ $space->name }}</span></h1>
                        <div class='col-sm-12 col-md-10 col-lg-12 justify-content-between'>
                            <div class='row row-cols-1 row-cols-md-4'>
                                @foreach($space->cmsRecordings as $recording)
                                    <livewire:cms-recording-card :recording="$recording"/>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

@endsection
