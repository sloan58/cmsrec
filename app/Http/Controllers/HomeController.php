<?php

namespace App\Http\Controllers;

use App\Models\CmsCoSpace;
use App\Models\CmsRecording;
use Carbon\Carbon;

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

            if($recordingCount) {
                $lastRecordingIn = CmsRecording::orderBy('created_at', 'desc')->first()->created_at->diffForHumans();
                $largestRecordingSize = bytesToHuman(CmsRecording::orderBy('size', 'desc')->first()->size);
                $smallestRecordingSize = bytesToHuman(CmsRecording::orderBy('size', 'asc')->first()->size);
                $averageRecordingSize = bytesToHuman(CmsRecording::sum('size') / $recordingCount);
            } else {
                $lastRecordingIn = 'Never';
                $largestRecordingSize = '0 bytes';
                $smallestRecordingSize = '0 bytes';
                $averageRecordingSize = '0 bytes';
            }

            $sharedRecordings = CmsRecording::whereShared(true)->count();

            $styles = ['primary', 'warning', 'danger', 'gray'];
            $chartBackGroundColors = ["'#4acccd'", "'#fcc468'", "'#ef8157'", "'#e3e3e3'"];
            $topCoSpaceStorageUsages = array_values(CmsCoSpace::get()->sortByDesc('rawSize')->take(4)->toArray());

            foreach($topCoSpaceStorageUsages as $index => $topCoSpaceStorageUsage) {
                $topCoSpaceStorageUsage['style'] = $styles[$index];
                $topCoSpaceStorageUsage['chartBackgroundColor'] = $chartBackGroundColors[$index];
                $topCoSpaceStorageUsages[$index] = $topCoSpaceStorageUsage;
            }

            $latestRecordings = CmsRecording::orderBy('created_at', 'desc')->take(5)->get();

            return view('pages.dashboard.dashboard', compact(
                'diskSize',
                'diskUsage',
                'recordingCount',
                'lastRecordingIn',
                'averageRecordingSize',
                'largestRecordingSize',
                'smallestRecordingSize',
                'sharedRecordings',
                'topCoSpaceStorageUsages',
                'latestRecordings'
            ));

        } else {
            return redirect()->route('recordings.index');
        }
    }
}
