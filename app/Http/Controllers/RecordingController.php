<?php

namespace App\Http\Controllers;

use Iman\Streamer\VideoStreamer;
use Illuminate\Contracts\View\View;

class RecordingController extends Controller
{
    /**
     * Return the Recordings view with CMS Recordings
     *
     * @return View
     */
    public function index()
    {
        $spacesWithRecordings = auth()->user()->myRecordings(request()->get('space'));
        return view('recordings.index', compact('spacesWithRecordings'));
    }

    /**
     * Stream the video recording to the UI
     *
     * @throws \Exception
     */
    public function play()
    {
        try {
            $streamUrl = request()->get('space') . '/' . request()->get('file');
            return VideoStreamer::streamFile(\Storage::disk('recordings')->path($streamUrl));
        } catch (\Exception $e) {
            logger()->error('Could not play recording', [
                'errorMessage' => $e->getMessage()
            ]);
        }
    }
}
