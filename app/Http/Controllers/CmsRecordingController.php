<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\CmsCoSpace;
use App\Models\CmsRecording;
use Iman\Streamer\VideoStreamer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;

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
     * Store a newly created resource in storage.
     *
     * @return void
     */
    public function store()
    {
        info('CmsRecordingController@store: Request Received', [
            'request' => request()->all()
        ]);

        if(request()->has('path')) {
            info('CmsRecordingController@store: Request has path', [
                'request_path' => request()->get('path')
            ]);

            $filePath = explode('/', request()->get('path'));
            $recordingName = array_pop($filePath);
            $cmsCoSpaceId = array_pop($filePath);

            info('CmsRecordingController@store: Extracted path components', [
                'filePath' => $filePath,
                'recordingName' => $recordingName,
                'cmsCoSpaceId' => $cmsCoSpaceId
            ]);


            if($cmsCoSpace = CmsCoSpace::where('space_id', $cmsCoSpaceId)->first()) {
                info('CmsRecordingController@store: CmsCoSpace exists');

                $fileExtension = pathinfo($recordingName, PATHINFO_EXTENSION);

                info('CmsRecordingController@store: Requested file extension is', [
                    'fileExtension' => $fileExtension
                ]);

                if(in_array($fileExtension, ['mp4'])) {
                    info('CmsRecordingController@store: The file is an mp4 file');

                    if(!$cmsCoSpace->cmsRecordings()->where('filename', $recordingName)->exists()) {
                        info('CmsRecordingController@store: The CmsCoSpace does not currently know about this recording');

                        $disk = Storage::disk('recordings');
                        $storeTo = "{$cmsCoSpace->space_id}/{$recordingName}";
                        $size = $disk->size($storeTo);
                        $last_modified = $disk->lastModified($storeTo);

                        info('CmsRecordingController@store: Created file metadata', [
                            'storeTo' => $storeTo,
                            'size' => $size,
                            'last_modified' => $last_modified
                        ]);

                        info('CmsRecordingController@store: Storing new CmsRecording');
                        try {
                            CmsRecording::create([
                                'filename' => $recordingName,
                                'size' => $size,
                                'last_modified' => $last_modified,
                                'cms_co_space_id' => $cmsCoSpace->id,
                                'shared' => false,
                                'user_id' => $cmsCoSpace->owner->id
                            ]);
                            info('CmsRecordingController@store: Stored new CmsRecording');
                        } catch(\Exception $e) {
                            logger()->error('CmsRecordingController@store: Could not store new CmsRecording', [
                                $e->getMessage()
                            ]);
                        }
                    }
                }
            }
        }
        info('CmsRecordingController@store: Request Complete.  Returning 200 OK');
        return response()->json('ACK', 200);
    }

    /**
     * Stream the video recording to the UI
     *
     * @param CmsRecording $cmsRecording
     */
    public function play(CmsRecording $cmsRecording, $timeStamp)
    {
        try {
            $cmsRecording->increment('views');
            return VideoStreamer::streamFile(Storage::disk('recordings')->path($cmsRecording->urlSafeFullPath));
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
            return view('recordings.shared', compact('cmsRecording'));
        } else {
            abort(401);
        }
    }
}
