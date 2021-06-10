<div class="card card-stats">
    <div class="card-body ">
        <div class="row">
            <div class="col-5 col-md-4">
                <div class="icon-big text-center icon-warning">
                    <i class="nc-icon nc-favourite-28 text-primary"></i>
                </div>
            </div>
            <div class="col-7 col-md-8">
                <div class="numbers">
                    <p class="card-category">Shared Recordings</p>
                    <p class="card-title">{{ $sharedRecordings }}
                    <p>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer ">
        <hr>
        <div class="stats">
            <i class="fa fa-television"></i> {{ floor(($sharedRecordings / $recordingCount) * 100)}}%
        </div>
    </div>
</div>
