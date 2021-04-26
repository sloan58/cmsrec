<div>
    <h1 class="display-4 mb-3"><span style="border-bottom: 2px solid #ddd;">{{ $space['spaceName'] }}</span></h1>
    <div class='col-sm-12 col-md-10 col-lg-12 justify-content-between'>
        <div class='row row-cols-1 row-cols-md-4'>
            @foreach($space['recordings'] as $recording)
                <div class="col mb-4" style="max-width: 400px;">
                    <div class="card h-100 shadow">
                        <video id="{{ $recording['sanitizedFilename'] }}" preload="none" controls>
                            <source src="/recordings/play?file={{ $recording['url'] }}"
                                    type="video/mp4"
                            >
                            Sorry, your browser doesn't support embedded videos.
                        </video>
                        <div class="card-body">
                            @if($currentRecordingName === $recording['baseName'])
                            <div class="d-flex mb-2">
                                <input wire:model="newRecordingName" @if(!$newRecordingNameHasErrors) wire:keydown.enter="saveNewRecordingName" @endif type="text" name="name" class="form-control {{ !$newRecordingNameHasErrors ?: 'is-invalid' }}" value="{{ $recording['baseName'] }}">
                                @if(!$newRecordingNameHasErrors)
                                <i wire:click="saveNewRecordingName" class="fa fa-check text-success ml-1 mt-2" style="cursor: pointer;"></i>
                                @else
                                <i wire:click="cancelNewRecordingName" class="fa fa-times text-danger ml-1 mt-2" style="cursor: pointer;"></i>
                                @endif
                            </div>
                            @else
                            <h5 class="card-title text-center">{{ $recording['baseName'] }}
                                <i wire:click="loadRecordingNames('{{ $recording['baseName'] }}')" class="fa fa-pencil text-info ml-1" style="cursor: pointer;"></i>
                            </h5>
                            @endif
                            @if($newRecordingNameHasErrors)
                            <span class="invalid-feedback text-center mb-2" style="display: block;" role="alert">
                                <strong>{{ $newRecordingNameError }}</strong>
                            </span>
                            @endif
                            <p class="card-text text-center">in <b>{{ $space['spaceName'] }}</b></p>
                            <footer class="blockquote-footer"><b>Created:</b> {{ $recording['lastModified'] }}</footer>
                            <footer class="blockquote-footer"><b>Size:</b> {{ $recording['fileSize'] }}</footer>
                        </div>
                        <div class="card-footer text-left">
                            <a href="/recordings/download?file={{ $recording['url'] }}" class="btn btn-primary"><i class="fa fa-download mr-2"></i>Download</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
