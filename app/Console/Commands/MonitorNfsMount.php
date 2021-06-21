<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MonitorNfsMount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cmsrec:monitor-nfs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ensure the NFS mount is mounted';

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
        info("MonitorNfsMount@handle: Running NFS Check");
        $nfsSettings = app(\App\Settings\NfsSettings::class);
        if (strlen($nfsSettings->host) && !$nfsSettings->mountExists()) {
            logger()->error("MonitorNfsMount@handle: NFS mount not found!  Sending email to app admins");
            \Mail::raw("NFS Mount not found for host {$nfsSettings->host} at {$nfsSettings->path}", function($message) {
                $message->setPriority(\Swift_Message::PRIORITY_HIGH);
                $message->subject('NFS Mount Not Found');
                $message->to(explode(',', env('MAIL_TO_ADMINS')));
            });
        }
    }
}
