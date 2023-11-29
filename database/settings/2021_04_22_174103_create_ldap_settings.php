<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateLdapSettings extends SettingsMigration
{
    public function up(): void
    {
        try {
            $jsonSeeds = json_decode(file_get_contents(base_path('/privateSeeder.json')));
            $this->migrator->add('ldap.name', $jsonSeeds->ldap->name);
            $this->migrator->add('ldap.host', $jsonSeeds->ldap->host);
            $this->migrator->add('ldap.searchBase', $jsonSeeds->ldap->searchBase);
            $this->migrator->add('ldap.bindDN', $jsonSeeds->ldap->bindDN);
            $this->migrator->add('ldap.password', $jsonSeeds->ldap->password);
            $this->migrator->add('ldap.filter', $jsonSeeds->ldap->filter);
        } catch(Exception $e) {
            $this->migrator->add('ldap.name', '');
            $this->migrator->add('ldap.host', '');
            $this->migrator->add('ldap.searchBase', []);
            $this->migrator->add('ldap.bindDN', '');
            $this->migrator->add('ldap.password', '');
            $this->migrator->add('ldap.filter', '');
        }
    }
}
