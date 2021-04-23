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
                <h1 class="display-4">{{ $space['spaceName'] }}</h1>
                <hr>
                <div class='col-sm-12 col-md-10 col-lg-12 justify-content-between'>
                    <div class='row row-cols-1 row-cols-md-4'>
                        @foreach($space['recordings'] as $recording)
                        <div class="col mb-4" style="max-width: 313px;">
                            <div class="card h-100 shadow">
                                <video id="{{ $recording['sanitizedFilename'] }}" preload="none" controls>
                                    <source src="/recordings/play?file={{ $recording['url'] }}"
                                            type="video/mp4"
                                    >
                                    Sorry, your browser doesn't support embedded videos.
                                </video>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $recording['baseName'] }}</h5>
                                    <p class="card-text">in <b>{{ $space['spaceName'] }}</b></p>
                                    <footer class="blockquote-footer"><b>Created:</b> {{ $recording['lastModified'] }}</footer>
                                    <footer class="blockquote-footer"><b>Size:</b> {{ $recording['fileSize'] }}</footer>
                                </div>
                                <div class="card-footer text-left">
                                    <a href="/recordings/download?file={{ $recording['url'] }}" class="btn btn-primary"><i class="fa fa-download mr-2"></i>Download</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection
