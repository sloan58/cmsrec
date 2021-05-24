<?php

namespace App\Http\Controllers;

use App\Models\CmsCoSpace;
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
            $views = CmsRecording::whereShared(true)->sum('views');

            $styles = ['primary', 'warning', 'danger', 'gray'];
            $chartBackGroundColors = ["'#4acccd'", "'#fcc468'", "'#ef8157'", "'#e3e3e3'"];
            $topCoSpaceStorageUsages = array_values(CmsCoSpace::take(4)->get()->sortByDesc('size')->each(
                function ($space, $key) use ($styles, $chartBackGroundColors) {
                    $space['style'] = $styles[$key];
                    $space['rawSize'] = (int) $space['size'];
                    $space['chartBackgroundColor'] = $chartBackGroundColors[$key];
                    return $space;
                }
            )->toArray());

            return view('pages.dashboard', compact(
                'diskSize',
                'diskUsage',
                'recordingCount',
                'lastRecordingIn',
                'averageRecordingSize',
                'largestRecordingSize',
                'smallestRecordingSize',
                'sharedRecordings',
                'views',
                'topCoSpaceStorageUsages',
            ));

        } else {
            return redirect()->route('recordings.index');
        }
    }
}
