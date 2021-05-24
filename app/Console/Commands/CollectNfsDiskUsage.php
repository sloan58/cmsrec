<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class CollectNfsDiskUsage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cmsrec:collect-disk-usage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collect the current NFS disk usage';

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
        info("CollectNfsDiskUsage@handle: Starting process");
        
        if (Storage::missing('stats/dashboard.json')) {
            info("CollectNfsDiskUsage@handle: Output file does not exist.  Creating it now");
            Storage::put('stats/dashboard.json', json_encode([]));
        }

        info("CollectNfsDiskUsage@handle: Opened output file 'stats/dashboard.json");
        $outputFile = (array) json_decode(Storage::get('stats/dashboard.json'));

        info("CollectNfsDiskUsage@handle: Setting recordings disk instance");
        $disk = \Storage::disk('recordings');

        info("CollectNfsDiskUsage@handle: Setting disk usage values in bytes and percentage");
        $diskSizeInBytes = disk_total_space($disk->path(''));
        $diskAvailableInBytes = disk_free_space($disk->path(''));
        $diskUsageInBytes = $diskSizeInBytes - $diskAvailableInBytes;
        $diskUsageInPercentage = round(($diskUsageInBytes / $diskSizeInBytes) * 100);

        info("CollectNfsDiskUsage@handle: Creating stats timestamp for this iteration");
        $time = Carbon::now('America/New_York')
            ->startOfHour()
            ->timestamp;

        info("CollectNfsDiskUsage@handle: Checking all null time values in the past 24 hours (setting to default 0)");
        foreach(range(1, 23) as $hour) {
            $timeCheck = Carbon::now('America/New_York')->subHours($hour)->startOfHour()->timestamp;
            if(!isset($outputFile[$timeCheck])) {
                $outputFile[$timeCheck] = '0';
            }
        }

        info("CollectNfsDiskUsage@handle: Filtering out any entries older than 24hrs");
        $outputFile = array_filter($outputFile, function($entry) {
            if(!Carbon::createFromTimestamp($entry)->timezone('America/New_York')->isBefore(Carbon::now('America/New_York')->subHours(24))) {
                return $entry;
            }
        }, ARRAY_FILTER_USE_KEY);

        info("CollectNfsDiskUsage@handle: Adding current iteration timestamp to output array");
        $outputFile[$time] = $diskUsageInPercentage;

        info("CollectNfsDiskUsage@handle: Storing updated stats to dashboard json file");
        Storage::put('stats/dashboard.json', json_encode($outputFile));

        info("CollectNfsDiskUsage@handle: Process complete");
    }
}
