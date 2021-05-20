<?php

namespace App\Http\Livewire;

use App\Helpers\NfsUtils;
use Livewire\Component;
use Symfony\Component\Process\Process;

class NfsSettings extends Component
{
    public $host;
    public $path;

    public function saveNfs()
    {
        info('saving');
    }

    /**
     * Connect the NFS mount
     */
    public function connect()
    {
        $result = app(\App\Settings\NfsSettings::class)->mountNfs($this->host, $this->path);

        return flash()->{$result['status']}($result['message']);
    }

    /**
     * Disconnect the NFS mount
     */
    public function disconnect()
    {
        $result = app(\App\Settings\NfsSettings::class)->unmountNfs();
        $this->initNfsData();
        return flash()->{$result['status']}($result['message']);
    }

    public function mount()
    {
        $this->initNfsData();
    }

    private function initNfsData() {

        $settings = app(\App\Settings\NfsSettings::class);

        $this->host = $settings->host;
        $this->path = $settings->path;
    }

    public function render()
    {
        return view('livewire.nfs-settings');
    }
}
