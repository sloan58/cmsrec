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
        $spacesWithRecordings = auth()->user()->myRecordings();
        return view('recordings.index', compact('spacesWithRecordings'));
    }

    /**
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
}
