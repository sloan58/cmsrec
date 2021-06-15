<?php

namespace App\Console\Commands;

use App\Models\CmsCoSpace;
use App\Models\CmsRecording;
use Storage;
use Illuminate\Console\Command;

class ScanForNewRecordings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cmsrec:scan-for-new';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan the recording filesystem for recordings that have not been imported yet';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        CmsCoSpace::each(function($cmsCoSpace) {
            $disk = Storage::disk('recordings');
            $recordings = $disk->files($cmsCoSpace->space_id);
            foreach($recordings as $recording) {
                $check = CmsRecording::where([
                    ['filename', basename($recording)], ['cms_co_space_id', $cmsCoSpace->id]
                ]);
                if(! $check->exists()) {
                    try {
                        CmsRecording::create([
                            'filename' => basename($recording),
                            'size' => $disk->size($recording),
                            'last_modified' => $disk->lastModified($recording),
                            'cms_co_space_id' => $cmsCoSpace->id,
                            'shared' => false,
                            'user_id' => $cmsCoSpace->owner->id
                        ]);
                    } catch(\Exception $e) {
                        logger()->error('ScanForNewRecordings@handle: Could not store new CmsRecording', [
                            $e->getMessage()
                        ]);
                    }
                }
            }
        });
    }
}
