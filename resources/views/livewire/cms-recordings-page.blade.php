<div>
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
    <div class="row justify-content-left">
        <div class="col-md-12 text-center">
            <div class='col-sm-12 col-md-10 col-lg-12 justify-content-between'>
                <div class='row row-cols-1 row-cols-md-4 justify-content-center'>
                    @foreach($cmsRecordings as $recording)
                        <livewire:cms-recording-card key="{{ now() }}" :recording="$recording"/>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
