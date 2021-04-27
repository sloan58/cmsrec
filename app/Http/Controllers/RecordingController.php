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
        $spaceId = request()->get('space');
        $cmsCoSpaces = auth()->user()->cmsCoSpaces()->when(request()->get('space'), function($query) use ($spaceId) {
            return $query->where('space_id', $spaceId);
        })->get();
        return view('recordings.index', compact('cmsCoSpaces'));
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

    /**
     * Stream the shared video recording to the UI
     *
     * @throws \Exception
     */
    public function shared()
    {
        if (! request()->hasValidSignature()) {
            return response()->json('bad', 401);
        } else {
            return response()->json('good', 200);
        }
    }
}
