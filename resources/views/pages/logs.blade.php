@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'logs'
])

@section('content')
    <div class="content">
        @include('components.flash-message')
        @if(empty($data['file']))
            <div>
                <h3>No Logs Found</h3>
            </div>
        @else
            @if(count($fileList))
            <div class="row">
                <form action="{{ route('logs.index') }}" class="form-inline">
                    <div class="form-group pr-2">
                        <select class="form-control" id="file" name="file">
                            @foreach($fileList as $file)
                            <option {{ File::basename($file) == $data['name'] ? 'selected' : '' }}>{{ File::basename($file) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Go</button>
                    </div>
                </form>
            </div>
            <hr>
            @endif
            <div>
                <h5><span class="text-secondary font-weight-bold">File Name:</span> {{ $data['name'] }}</h5>
                <h5><span class="text-secondary font-weight-bold">Updated On:</span> {{ $data['last_modified']->format('Y-m-d h:i a') }}</h5>
                <h5><span class="text-secondary font-weight-bold">File Size:</span> {{ bytesToHuman($data['size']) }}</h5>
                <hr>
                <pre>{{ $data['file'] }}</pre>
            </div>
        @endif
    </div>

@endsection
