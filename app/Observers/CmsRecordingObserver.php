<?php

namespace App\Observers;

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
        info('CmsRecordingObserver@deleted: Model deleted', [
            $cmsRecording->toArray()
        ]);

        $disk = \Storage::disk('recordings');
        if(file_exists($disk->path($cmsRecording->relativeStoragePath))) {
            $disk->delete($cmsRecording->relativeStoragePath);
            info('CmsRecordingObserver@deleted: Recording deleted from disk', [
                'file' => $disk->path($cmsRecording->relativeStoragePath)
            ]);
        }
    }
}
