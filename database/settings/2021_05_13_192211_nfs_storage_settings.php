<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class NfsStorageSettings extends SettingsMigration
{
    public function up(): void
    {
//        try {
//            $jsonSeeds = json_decode(file_get_contents(base_path('/privateSeeder.json')));
//            $this->migrator->add('nfs.host', $jsonSeeds->nfs->host);
//            $this->migrator->add('nfs.path', $jsonSeeds->nfs->path);
//            $this->migrator->add('nfs.mnt_view', $jsonSeeds->nfs->mnt_view);
//        } catch(Exception $e) {
//            $this->migrator->add('nfs.host', '');
//            $this->migrator->add('nfs.path', '');
//            $this->migrator->add('nfs.mnt_view', []);
//        }
        $this->migrator->add('nfs.host', '');
        $this->migrator->add('nfs.path', '');
        $this->migrator->add('nfs.mnt_view', []);
    }
}
