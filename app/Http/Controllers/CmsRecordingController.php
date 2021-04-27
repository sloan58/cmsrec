<?php

namespace App\Http\Controllers;

use Storage;
use Exception;
use App\Models\CmsRecording;
use Iman\Streamer\VideoStreamer;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;

class CmsRecordingController extends Controller
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
     * @throws Exception
     */
    public function play()
    {
        try {
            $streamUrl = request()->get('space') . '/' . request()->get('file');
            return VideoStreamer::streamFile(Storage::disk('recordings')->path($streamUrl));
        } catch (Exception $e) {
            logger()->error('Could not play recording', [
                'errorMessage' => $e->getMessage()
            ]);
        }
    }

    /**
     * Stream the shared video recording to the UI
     *
     * @param CmsRecording $cmsRecording
     * @return void
     * @throws Exception
     */
    public function shared(CmsRecording $cmsRecording)
    {
        if (request()->hasValidSignature() && $cmsRecording->shared) {
            return VideoStreamer::streamFile(Storage::disk('recordings')->path($cmsRecording->urlSafeFullPath));
        } else {
            abort(401);
        }
    }
}
