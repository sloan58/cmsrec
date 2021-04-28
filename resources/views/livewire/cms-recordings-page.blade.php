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
    <div class="col-10 offset-1">
        <div class="row justify-content-between">
            <div class="d-flex">
                <div class="mr-2">
                    {{ $cmsRecordings->links('vendor.pagination.bootstrap-4') }}
                </div>
                <div class="mt-1">
                    Showing {{ $cmsRecordings->firstItem() }} to {{ $cmsRecordings->lastItem() }} of total {{$cmsRecordings->total()}} entries
                </div>
            </div>
            <div class="dropdown">
                <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ $paginate }}
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#" wire:click="$set('paginate', '10')">10</a>
                    <a class="dropdown-item" href="#" wire:click="$set('paginate', '25')">25</a>
                    <a class="dropdown-item" href="#" wire:click="$set('paginate', '50')">50</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-left">
        <div class='col-sm-12 col-md-10 offset-md-1 text-center'>
            <div class='row row-cols-1 row-cols-md-4'>
                @foreach($cmsRecordings as $recording)
                    <livewire:cms-recording-card key="{{ now() }}" :recording="$recording"/>
                @endforeach
            </div>
        </div>
    </div>
</div>
