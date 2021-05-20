<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class NfsSettings extends Settings
{
    public string $host;
    public string $path;
    public array $mnt_view;

    public static function group(): string
    {
        return 'nfs';
    }
}
