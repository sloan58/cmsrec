<div class="card card-stats w-25">
    <div class="card-body ">
        <div class="row">
            <div class="col-5 col-md-4">
                <div class="icon-big text-center icon-warning">
                    <i class="fa fa-video-camera text-success"></i>
                </div>
            </div>
            <div class="col-7 col-md-8">
                <div class="numbers">
                    <p class="card-category">Recordings</p>
                    <p class="card-title">{{ $recordingCount }}
                    <p>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer ">
        <hr>
        <div class="stats">
            <i class="fa fa-calendar-o"></i> Last recording received {{ $lastRecordingIn }}
        </div>
    </div>
</div>
