<div>
    <div class="row justify-content-center">
        <div class="col-md-4 col-sm-12 mb-4">
            <input wire:model.debounce.500ms="term" class="form-control mr-sm-2 text-center" type="search" placeholder="Search By Recording Name" value="{{ $term }}" aria-label="Search">
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
