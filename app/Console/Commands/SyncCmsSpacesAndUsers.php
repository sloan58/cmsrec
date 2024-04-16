<?php

namespace App\Console\Commands;

use App\Models\Cms;
use App\APIs\CmsRest;
use Illuminate\Console\Command;

class SyncCmsSpacesAndUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cmsrec:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync the CMS Spaces and Users Data';

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
     */
    public function handle()
    {
        info("SyncCmsSpacesAndUsers@handle: Syncing CMS");
        Cms::each(function($cms) {
            info("SyncCmsSpacesAndUsers@handle: Working {$cms->name}");
            $cmsApi = new CmsRest($cms);
            $cmsApi->getCmsUserIds();
            $cmsApi->getCoSpaces();
            info("SyncCmsSpacesAndUsers@handle: Finished {$cms->name}");
        });
        info("SyncCmsSpacesAndUsers@handle: Finished");
    }
}
