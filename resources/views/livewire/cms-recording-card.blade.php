<div class="col-auto mb-4">
    <div class="card shadow" style="height: 375px; width: 281px;">
        <div class="card-header card-header-danger">
        @if($recordingInPlayback)
            <button class="btn btn-lg btn-success btn-fab btn-icon btn-round" disabled>
                <i class="fa fa-play"></i>
            </button>
        @else
            <button wire:click="$emit('startPlayback', '{{ $recording->id }}')" class="btn btn-lg btn-success btn-fab btn-icon btn-round">
                <i class="fa fa-play"></i>
            </button>
        @endif
        </div>
        <div class="card-body pb-0">
            @if($editing)
            <div class="d-flex mb-2">
                <input wire:model="newRecordingName" @if(!$newRecordingNameHasErrors) wire:keydown.enter="saveNewRecordingName" @endif type="text" name="name" class="form-control {{ !$newRecordingNameHasErrors ?: 'is-invalid' }}" value="{{ $recording->filename }}">
                @if(!$newRecordingNameHasErrors)
                    <i wire:click="saveNewRecordingName" class="fa fa-check text-success ml-1 mt-2" style="cursor: pointer;"></i>
                @else
                    <i wire:click="cancelNewRecordingName" class="fa fa-times text-danger ml-1 mt-2" style="cursor: pointer;"></i>
                @endif
            </div>
            @else
            <p class="card-title text-center font-weight-bolder">{{ \Str::limit($recording->filename, 20) }}
                <i wire:click="loadRecordingNames" class="fa fa-pencil text-info ml-1" style="cursor: pointer;"></i>
            </p>
            @endif
            @if($newRecordingNameHasErrors)
            <span class="invalid-feedback text-center mb-2" style="display: block;" role="alert">
                <strong>{{ $newRecordingNameError }}</strong>
            </span>
            @endif
            <p class="card-text text-center">in <span class="font-italic">{{ $recording->cmsCoSpace->name }}</span></p>
            <ul class="text-left list-unstyled mb-0">
                <li><b>Views:</b> {{ $recording->views }}</li>
                <li><b>Downloads:</b> {{ $recording->downloads }}</li>
                <li><b>Owner:</b> {{ $recording->owner->name }}</li>
                <li><b>Size:</b> {{ $recording->friendlySize }}</li>
                <li><b>Created:</b> {{ $recording->last_modified->toDayDateTimeString() }}</li>
            </ul>
            <hr>
            <div class="d-flex justify-content-between">
                <button wire:loading.remove wire:click="downloadRecording('{{ $recording->relativeStoragePath }}')" class="btn btn-info btn-round">
                    <i class="fa fa-download"></i> Download
                </button>
                <button wire:loading wire:target="downloadRecording" class="btn btn-info btn-round" type="button" disabled>
                    <span class="spinner-border spinner-border-sm text-center" role="status" aria-hidden="true"></span>
                    <span class="sr-only">Downloading...</span>
                </button>
                @if($recording->shared)
                    <div class="dropdown">
                        <button class="btn btn-warning btn-round dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Shared
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a x-data="{ input: '{{ $recording->signedRoute }}' }" @click="$clipboard(input)" class="dropdown-item" href="#">
                                <i class="fa fa-copy mr-2 text-success"></i>Copy Share Link
                            </a>
                            <a wire:click="toggleSharing(false)" class="dropdown-item" href="#">
                                <i class="fa fa-times mr-2 text-danger"></i>Disable Sharing
                            </a>
                        </div>
                    </div>
                @else
                    <button wire:click="toggleSharing(true)" class="btn btn-info btn-round">
                        <i class="fa fa-share"></i> Share
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
