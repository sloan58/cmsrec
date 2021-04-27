<div class="col mb-4" style="max-width: 400px;">
    <div class="card h-100 shadow">
        <video id="{{ $recording->sanitizedFilename }}" preload="none" controls>
            <source src="{{ route('recordings.play', ['space' => $recording->cmsCoSpace->space_id, 'file' => $recording->urlSafeFilename]) }}"
                    type="video/mp4"
            >
            Sorry, your browser doesn't support embedded videos.
        </video>
        <div class="card-body">
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
                <h5 class="card-title text-center">{{ $recording->filename }}
                    <i wire:click="loadRecordingNames" class="fa fa-pencil text-info ml-1" style="cursor: pointer;"></i>
                </h5>
            @endif
            @if($newRecordingNameHasErrors)
                <span class="invalid-feedback text-center mb-2" style="display: block;" role="alert">
                    <strong>{{ $newRecordingNameError }}</strong>
                </span>
            @endif
            <p class="card-text text-center">in <b>{{ $recording->cmsCoSpace->name }}</b></p>
            <footer class="blockquote-footer"><b>Created:</b> {{ $recording->last_modified->toDayDateTimeString() }}</footer>
            <footer class="blockquote-footer"><b>Size:</b> {{ $recording->size }}</footer>
        </div>
        @if($shareLinkCreated)
        <div class="text-center">
            <h3>Coming soon!</h3>
        </div>
        @endif
        <div class="card-footer text-left d-flex justify-content-between">
            <button wire:loading.remove wire:click="downloadRecording('{{ $recording->urlSafeFilename }}')" class="btn btn-primary btn-round">
                <i class="fa fa-download"></i> Download
            </button>
            <button wire:loading wire:target="downloadRecording" class="btn btn-primary btn-round" type="button" disabled>
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                <span class="sr-only">Downloading...</span>
                Downloading
            </button>
            <button wire:click="createShareLink('{{ $recording->urlSafeFilename }}')" class="btn btn-primary btn-round">
                <i class="fa fa-share"></i> Share
            </button>
        </div>
    </div>
</div>
