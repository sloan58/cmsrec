<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MoveRecordingToTrash implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $relativeStoragePath;
    private $spaceId;

    /**
     * Create a new job instance.
     * @param $relativeStoragePath
     * @param $spaceId
     */
    public function __construct($relativeStoragePath, $spaceId)
    {
        $this->relativeStoragePath = $relativeStoragePath;
        $this->spaceId = $spaceId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sourceDisk = \Storage::disk('recordings');
        $destinationDisk = \Storage::disk('recordings-trash');

        $sourcePath = $sourceDisk->path($this->relativeStoragePath);
        $destinationPath = $destinationDisk->path($this->relativeStoragePath);

        $storagePath = $destinationDisk->path($this->spaceId);
        if(!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        try {
            \File::move($sourcePath, $destinationPath);
        } catch(\Exception $e) {
            logger()->error('MoveRecordingToTrash@handle: Could not move recording to trash', [
                'errorMessage' => $e->getMessage(),
                'sourcePath' => $sourcePath,
                'destinationPath' => $destinationPath
            ]);
        }
    }
}
