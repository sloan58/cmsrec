<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="card-title">CMS CoSpaces</h4>
            </div>
            <div class="card-header d-flex justify-content-end">
                <div class="form-inline my-2 my-lg-0">
                    <input wire:model.debounce.200ms="term" class="form-control mr-sm-2" name="q" type="search" placeholder="Search" value="{{ $term }}" aria-label="Search">
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class=" text-primary">
                        <th>
                            Name
                        </th>
                        <th>
                            Size
                        </th>
                        <th>
                            Recordings
                        </th>
                        <th>
                            Owner Name
                        </th>
                        </thead>
                        <tbody>
                        @foreach($cmsCoSpaces as $cmsCoSpace)
                            <tr>
                                <td>
                                    {{ $cmsCoSpace->name }}
                                </td>
                                <td>
                                    {{ $cmsCoSpace->size() }}
                                </td>
                                <td>
                                    {{ $cmsCoSpace->cmsRecordings()->count() }}
                                </td>
                                <td>
                                    {{ $cmsCoSpace->owner->name }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
