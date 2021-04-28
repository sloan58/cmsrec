<?php

namespace App\Http\Controllers;

use App\Models\CmsRecording;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if(auth()->user()->isAdmin()) {

            $disk = \Storage::disk('recordings');

            $diskSizeInBytes = disk_total_space($disk->path(''));
            $diskAvailableInBytes = disk_free_space($disk->path(''));

            $diskSize = bytesToHuman($diskSizeInBytes);
            $diskUsage = bytesToHuman($diskSizeInBytes - $diskAvailableInBytes);

            $recordingCount = CmsRecording::count();
            $lastRecordingIn = CmsRecording::orderBy('created_at', 'desc')->first()->created_at->diffForHumans();

            $averageRecordingSize = bytesToHuman(CmsRecording::sum('size') / $recordingCount);

            $largestRecordingSize = bytesToHuman(CmsRecording::orderBy('size', 'desc')->first()->size);
            $smallestRecordingSize = bytesToHuman(CmsRecording::orderBy('size', 'asc')->first()->size);

            $sharedRecordings = CmsRecording::whereShared(true)->count();

            return view('pages.dashboard', compact(
                'diskSize',
                'diskUsage',
                'recordingCount',
                'lastRecordingIn',
                'averageRecordingSize',
                'largestRecordingSize',
                'smallestRecordingSize',
                'sharedRecordings'
            ));

        } else {
            return redirect()->route('recordings.index');
        }
    }
}
