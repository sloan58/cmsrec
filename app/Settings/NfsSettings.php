<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class NfsSettings extends Settings
{
    public string $host;
    public string $path;

    public static function group(): string
    {
        return 'nfs';
    }
}
