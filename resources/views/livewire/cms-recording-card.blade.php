<div class="card shadow m-2 w-25">
    @if($promptForDelete)
        <div class="d-flex justify-content-center align-items-center h-100">
            <button wire:click="deleteRecording" class="btn btn-danger btn-round my-auto">
                Delete
            </button>
            <button wire:click="$toggle('promptForDelete')" class="btn btn-default btn-round">
                Cancel
            </button>
        </div>
    @else
        <div class="card-header card-header-danger pt-0">
            <div class="d-flex justify-content-between">
                @if($recordingInPlayback)
                    <button class="btn btn-sm btn-success btn-fab btn-icon btn-round" disabled>
                        <i class="fa fa-play"></i>
                    </button>
                @else
                    <button wire:click="$emit('startPlayback', '{{ $recording->id }}')" class="btn btn-sm btn-success btn-fab btn-icon btn-round">
                        <i class="fa fa-play"></i>
                    </button>
                @endif
                <button wire:click="$toggle('promptForDelete')" class="btn btn-sm btn-danger btn-fab btn-icon btn-round">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
        <div class="card-body d-flex flex-column justify-content-between">
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
                <li><b>Owner(s):</b> {{ implode(', ', $recording->cmsCoSpace->owners->pluck('name')->toArray() )}}</li>
                <li><b>Size:</b> {{ $recording->friendlySize }}</li>
                <li><b>Created:</b> {{ $recording->last_modified->toDayDateTimeString() }}</li>
                <li><b>Downloads:</b> {{ $recording->downloads }}</li>
            </ul>
            <div class="d-flex justify-content-between">
                <a href="{{ route('recordings.download', $recording) }}" class="btn btn-info btn-round">
                    <i class="fa fa-download"></i> Download
                </a>
                @if($recording->shared)
                    <div class="dropdown">
                        <button class="btn btn-warning btn-round dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Shared
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a x-data="{ input: '{{ $recording->signedLinkRoute }}' }" @click="$clipboard(input)" class="dropdown-item" href="#">
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
    @endif
</div>
