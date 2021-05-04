<?php

namespace App\Console\Commands;

use App\Models\Cms;
use App\ApiClients\CmsRest;
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
     *
     * @return int
     */
    public function handle()
    {
        Cms::each(function($cms) {
            $cmsApi = new CmsRest($cms);
            $cmsApi->getCmsUserIds();
            $cmsApi->getCoSpaces();
        });

        return true;
    }
}
