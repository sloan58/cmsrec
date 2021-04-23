<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $spacesWithRecordings = auth()->user()->myRecordings(
            request()->has('space') && request()->get('space')
        );
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
            return VideoStreamer::streamFile(\Storage::disk('recordings')->path(request()->get('file')));
        } catch (Exception $e) {
            logger()->error('Could not play recording', [
                'errorMessage' => $e->getMessage()
            ]);
        }
    }

    /**
     * Download the video recording
     *
     * @throws \Exception
     */
    public function download()
    {
        try {
            return \Storage::disk('recordings')->download(request()->get('file'));
        } catch (Exception $e) {
            logger()->error('Could not play recording', [
                'errorMessage' => $e->getMessage()
            ]);
        }
    }
}
