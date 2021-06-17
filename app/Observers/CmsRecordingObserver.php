<?php

namespace App\Observers;

use App\Jobs\MoveRecordingToTrash;
use App\Models\CmsRecording;

class CmsRecordingObserver
{
    /**
     * Handle the model "deleted" event.
     *
     * @param CmsRecording $cmsRecording
     * @return void
     */
    public function deleted(CmsRecording $cmsRecording)
    {
        $disk = \Storage::disk('recordings');

        if(file_exists($disk->path($cmsRecording->relativeStoragePath))) {
            dispatch(new MoveRecordingToTrash($cmsRecording->relativeStoragePath, $cmsRecording->cmsCoSpace->space_id));
        }
    }
}
