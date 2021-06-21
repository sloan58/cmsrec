<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\CmsRecording;
use Illuminate\Http\Response;
use Iman\Streamer\VideoStreamer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Routing\ResponseFactory;

class CmsRecordingController extends Controller
{
    /**
     * Return the Recordings view with CMS Recordings
     *
     * @return View
     */
    public function index()
    {
        return view('recordings.index');
    }

    /**
     * Stream the video recording to the UI
     *
     * @param CmsRecording $cmsRecording
     */
    public function play(CmsRecording $cmsRecording)
    {
        try {
            return VideoStreamer::streamFile(Storage::disk('recordings')->path($cmsRecording->relativeStoragePath));
        } catch (Exception $e) {
            logger()->error('Could not play recording', [
                'errorMessage' => $e->getMessage()
            ]);
        }
    }

    /**
     * Provide an Nginx-based download of the file
     * Files are linked in /var/www/html/cms-recordings
     *
     * @param CmsRecording $cmsRecording
     * @return ResponseFactory|Response
     */
    public function download(CmsRecording $cmsRecording)
    {
        return response(null)
            ->header('Content-Disposition', 'attachment; filename="' . $cmsRecording->filename . '"')
            ->header('X-Accel-Redirect', "/protected/{$cmsRecording->cmsCoSpace->space_id}/{$cmsRecording->filename}");
    }

    /**
     * Stream the shared video recording to the UI
     *
     * @param CmsRecording $cmsRecording
     * @return void
     * @throws Exception
     */
    public function sharedLink(CmsRecording $cmsRecording)
    {
        if (request()->hasValidSignature() && $cmsRecording->shared) {
            return view('recordings.shared', compact('cmsRecording'));
        } else {
            abort(401);
        }
    }

    /**
     * Stream the shared video recording to the UI
     *
     * @param CmsRecording $cmsRecording
     * @return void
     * @throws Exception
     */
    public function sharedView(CmsRecording $cmsRecording)
    {
        if (request()->hasValidSignature() && $cmsRecording->shared) {
            try {
                return VideoStreamer::streamFile(Storage::disk('recordings')->path($cmsRecording->relativeStoragePath));
            } catch (Exception $e) {
                logger()->error('Could not play recording', [
                    'errorMessage' => $e->getMessage()
                ]);
            }
        } else {
            abort(401);
        }
    }
}
