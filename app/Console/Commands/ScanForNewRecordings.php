<?php

namespace App\Console\Commands;

use Storage;
use App\Models\CmsCoSpace;
use App\Models\CmsRecording;
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
        info("ScanForNewRecordings@handle: Scanning NFS for new recordings");
        CmsCoSpace::each(function($cmsCoSpace) {
            $disk = Storage::disk('recordings');
            $recordings = $disk->files($cmsCoSpace->space_id);

            foreach($recordings as $recording) {
                if(pathinfo($recording)['extension'] == 'mp4') {
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
                                'user_id' => $cmsCoSpace->owner()->id
                            ]);

                            \Mail::raw('Please login to ' . env('APP_URL') . ' to view or download your recording', function($message) use ($cmsCoSpace) {
                                $message->subject('Your CMS Recording is available');
                                $message->to($cmsCoSpace->owner()->email);
                            });
                        } catch(\Exception $e) {
                            logger()->error('ScanForNewRecordings@handle: Could not store new CmsRecording', [
                                $e->getMessage()
                            ]);
                            \Mail::raw($e->getMessage(), function($message) use ($cmsCoSpace) {
                                $message->setPriority(\Swift_Message::PRIORITY_HIGH);
                                $message->subject('Error importing new CMS recording');
                                $message->to(explode(',', env('MAIL_TO_ADMINS')));
                            });
                        }
                    }
                }
            }
        });
        info("ScanForNewRecordings@handle: Finished");
    }
}
