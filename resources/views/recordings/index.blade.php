@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'recordings'
])

@section('content')
    <div class="content">
        @include('components.flash-message')
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">Recordings</h4>
                        <form action="{{ route('recordings.index') }}" class="form-inline my-2 my-lg-0" method="GET">
                            <input class="form-control mr-sm-2" name="q" type="search" placeholder="Search" value="{{ $q ?? '' }}" aria-label="Search">
                            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class=" text-primary">
                                <th>
                                    Space Name
                                </th>
                                <th>
                                    Filename
                                </th>
                                <th>
                                    Size
                                </th>
                                <th>
                                    Last Modified
                                </th>
                                <th class="text-right">
                                    Actions
                                </th>
                                </thead>
                                <tbody>
                                @foreach($spacesWithRecordings as $space)
                                    @foreach($space['recordings'] as $recording)
                                        <tr>
                                            <td>
                                                {{ $space['spaceName'] }}
                                            </td>
                                            <td>
                                                {{ $recording['baseName'] }}
                                            </td>
                                            <td>
                                                {{ $recording['fileSize'] }}
                                            </td>
                                            <td>
                                                {{ $recording['lastModified'] }}
                                            </td>
                                            <td class="text-right">
                                                <button class="btn btn-success btn-fab btn-icon btn-round watchVideo" data-video="{{ $recording['baseNameWithoutExt'] }}">
                                                    <i class="fa fa-video-camera"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <video id="{{ $recording['baseNameWithoutExt'] }}" controls width="500" style="display: none;">
                                                    <source src="/recordings/play?file={{ $recording['url'] }}"
                                                            type="video/mp4"
                                                    >
                                                    Sorry, your browser doesn't support embedded videos.
                                                </video>
                                            </td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                @endforeach
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-12">
{{--                                    {{ $users->links('vendor.pagination.bootstrap-4') }}--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).on('click','.watchVideo',function(){
                const video = $(this).attr('data-video');
                const videoElement = $(`#${video}`)
                if(videoElement.is(":hidden")) {
                    videoElement.show();
                    videoElement.trigger('play');
                } else {
                    videoElement.hide();
                    videoElement.trigger('pause');
                }
            });
        </script>
    @endpush

@endsection
