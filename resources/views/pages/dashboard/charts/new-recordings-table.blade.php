<div class="col-md-8">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">New Recordings</h5>
            <p class="card-category">Recently Added</p>
        </div>
        <div class="card-body table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>File</th>
                    <th>Size</th>
                    <th>CoSpace</th>
                    <th>Created</th>
                </tr>
                </thead>
                <tbody>
                @foreach($latestRecordings as $latestRecording)
                <tr>
                    <th>{{$latestRecording->filename}}</th>
                    <td>{{ $latestRecording->friendlySize }}</td>
                    <td>{{ $latestRecording->cmsCoSpace->name }}</td>
                    <td>{{ $latestRecording->created_at->diffForHumans() }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <div class="stats">
                <i class="fa fa-calendar"></i> Updated every five minutes
            </div>
        </div>
    </div>
</div>
