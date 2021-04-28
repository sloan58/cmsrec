<div>
    @if($recordingShouldPlay)
    <div class="row">
        <div class="col-md-12">
            <div class="card bg-dark">
                <div class="col-md-12 justify-content-center d-flex">
                    <div class="card-header card-header-warning text-white">
                        <h3 class="mb-0">{{ $recordingInPlayback->filename }}</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-md-12 justify-content-center d-flex">
                        <video id="1" width="75%" height="100%" controls autoplay>
                            <source src="{{ route('recordings.play', ['space' => $recordingInPlayback->cmsCoSpace->space_id, 'file' => $recordingInPlayback->urlSafeFilename]) }}"
                                    type="video/mp4"
                            >
                            Sorry, your browser doesn't support embedded videos.
                        </video>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="col-md-12 justify-content-center d-flex">
                        <button wire:click="stopPlayback" class="btn btn-danger btn-fab btn-round">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="my-5">
    @else
    <div class="row justify-content-center mb-3">
        <div class="col-md-6 col-sm-12">
            <input wire:model.debounce.500ms="term" class="form-control text-center" type="search" placeholder="Search By Recording, Space Name {{ !auth()->user()->isAdmin() ?: 'or Owner'}}" value="{{ $term }}" aria-label="Search">
        </div>
    </div>
    <div class="row justify-content-center mt-4">
        <div class='col-sm-12 col-md-10 offset-md-1 text-center'>
            <div class="row justify-content-between">
                <div class="d-flex">
                    <div class="mr-2">
                        {{ $cmsRecordings->links() }}
                    </div>
                    <div class="mt-1">
                        Showing {{ $cmsRecordings->firstItem() }} to {{ $cmsRecordings->lastItem() }} of total {{$cmsRecordings->total()}} entries
                    </div>
                </div>
                <div class="d-flex">
                    <div class="dropdown">
                        <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Show: {{ $paginate }}
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#" wire:click="$set('paginate', '10')">10</a>
                            <a class="dropdown-item" href="#" wire:click="$set('paginate', '25')">25</a>
                            <a class="dropdown-item" href="#" wire:click="$set('paginate', '50')">50</a>
                        </div>
                    </div>
                </div>
            </div>
            @if(auth()->user()->isAdmin())
                <div class="row justify-content-end mt-2">
                    <label class="form-check-label pl-0">
                        <input wire:click="$toggle('showAll')" type="radio" class="form-radio" name="rank"
                               value="1" {{ $showAll ? 'checked' : '' }}>
                        Show{{ $showAll ? 'ing' : ''}} All Users Recordings
                    </label>
                </div>
            @endif
        </div>
    </div>
    @endif
    <div class="row justify-content-center mt-4">
        <div class='col-sm-12 col-md-10 offset-md-1 text-center'>
            <div class='row row-cols-1 row-cols-md-4'>
                @foreach($cmsRecordings as $recording)
                    <livewire:cms-recording-card key="{{ now() }}" :recording="$recording" :recordingInPlayback="$recordingInPlayback"/>
                @endforeach
            </div>
        </div>
    </div>
</div>

