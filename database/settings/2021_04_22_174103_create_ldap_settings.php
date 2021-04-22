<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateLdapSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('ldap.name', '');
        $this->migrator->add('ldap.host', '');
        $this->migrator->add('ldap.searchBase', '');
        $this->migrator->add('ldap.bindDN', '');
        $this->migrator->add('ldap.password', '');
        $this->migrator->add('ldap.filter', '');
    }
}
