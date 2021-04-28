<div>
    @if($recordingShouldPlay)
    <div class="row">
        <div class="col-md-12 justify-content-center d-flex">
            <h3>{{ $recording->filename }}</h3>
        </div>
        <div class="col-md-12 justify-content-center d-flex">
            <video id="1" width="50%" height="100%" controls autoplay>
                <source src="{{ route('recordings.play', ['space' => $recording->cmsCoSpace->space_id, 'file' => $recording->urlSafeFilename]) }}"
                        type="video/mp4"
                >
                Sorry, your browser doesn't support embedded videos.
            </video>
        </div>
        <div class="col-md-12 justify-content-center d-flex">
            <button wire:click="stopPlayback" class="btn btn-danger btn-fab btn-round">
                Stop Playback
            </button>
        </div>
    </div>
    <hr class="my-5">
    @endif
    <div class="row justify-content-center mb-3">
        <div class="col-md-6 col-sm-12">
            <input wire:model.debounce.500ms="term" class="form-control text-center" type="search" placeholder="Search By {{ $searchBy }}" value="{{ $term }}" aria-label="Search">
        </div>
    </div>
    <div class="row justify-content-center mb-3">
        <div class="dropdown">
            <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Search By {{ $searchBy }}
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#" wire:click="$set('searchBy', 'Recording Name')">Recording Name</a>
                <a class="dropdown-item" href="#" wire:click="$set('searchBy', 'Space Name')">Space Name</a>
                @if(auth()->user()->isAdmin())
                <a class="dropdown-item" href="#" wire:click="$set('searchBy', 'Owner Name')">Owner Name</a>
                @endif
            </div>
        </div>
    </div>
    <div class="col-10 offset-1">
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
                @if(auth()->user()->isAdmin())
                <div class="form-check  mt-3 mr-3">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox">
                        Show All Users Recordings
                        <span wire:click="$toggle('showAll')" class="form-check-sign">
                            <span class="check"></span>
                        </span>
                    </label>
                </div>
                @endif
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
    </div>
    <div class="row justify-content-center mt-4">
        <div class='col-sm-12 col-md-10 offset-md-1 text-center'>
            <div class='row row-cols-1 row-cols-md-4'>
                @foreach($cmsRecordings as $recording)
                    <livewire:cms-recording-card key="{{ now() }}" :recording="$recording"/>
                @endforeach
            </div>
        </div>
    </div>
</div>

