<div class="card card-stats">
    <div class="card-body ">
        <div class="row">
            <div class="col-5 col-md-4">
                <div class="icon-big text-center icon-warning">
                    <i class="nc-icon nc-vector text-danger"></i>
                </div>
            </div>
            <div class="col-7 col-md-8">
                <div class="numbers">
                    <p class="card-category">Average Recording Size</p>
                    <p class="card-title">{{ $averageRecordingSize }}
                    <p>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer ">
        <hr>
        <div class="stats">
            <i class="fa fa-clock-o"></i> Largest: {{ $largestRecordingSize }} | Smallest: {{ $smallestRecordingSize }}
        </div>
    </div>
</div>
