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
                    if(!$check->exists()) {
                        info('ScanForNewRecordings@handle: New recording found', [
                            'recording' => $recording,
                            'cmsCoSpace' => $cmsCoSpace
                        ]);
                        info('ScanForNewRecordings@handle: Storing new recording');
                        try {
                            CmsRecording::create([
                                'filename' => basename($recording),
                                'size' => $disk->size($recording),
                                'last_modified' => $disk->lastModified($recording),
                                'cms_co_space_id' => $cmsCoSpace->id,
                                'shared' => false,
                            ]);

                            info('ScanForNewRecordings@handle: Sending notifications if this space has any owners');
                            $toAddresses = array_unique(array_filter($cmsCoSpace->owners()->pluck('email')->toArray()));

                            foreach($toAddresses as $toAddress) {
                                info('ScanForNewRecordings@handle: Sending notification to address', [
                                    $toAddress
                                ]);
                                info('ScanForNewRecordings@handle: Sending notification email');
                                \Mail::raw('Please login to ' . env('APP_URL') . ' to view or download your recording', function($message) use ($cmsCoSpace, $toAddress) {
                                    $message->subject('Your CMS Recording is available');
                                    $message->to($toAddress);
                                    $message->bcc('martin_sloan@ao.uscourts.gov');
                                });
                            }
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
