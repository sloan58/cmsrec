<video id="1" width="100%" height="100%" controls preload="none" controlsList="nodownload">
    <source src="{{ route('recordings.play', ['cmsRecording' => $cmsRecording, 'timeStamp' => \Carbon\Carbon::now()->timestamp]) }}"
            type="video/mp4"
    >
    Sorry, your browser doesn't support embedded videos.
</video>
