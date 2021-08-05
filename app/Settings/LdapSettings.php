<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class LdapSettings extends Settings
{
    public string $name;
    public string $host;
    public array $searchBase;
    public string $bindDN;
    public string $password;
    public string $filter;

    public static function group(): string
    {
        return 'ldap';
    }
}
