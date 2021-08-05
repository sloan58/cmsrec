<?php

namespace App\Http\Livewire;

use Livewire\Component;

class LdapSettings extends Component
{
    public $searchBase;

    /**
     * Remove a searchBase from the LDAP settings array
     *
     * @param \App\Settings\LdapSettings $settings
     * @param $searchBase
     */
    public function removeSearchBase(\App\Settings\LdapSettings $settings, $searchBase)
    {
        $settings->searchBase = array_filter($settings->searchBase, function($i) use($searchBase) {
            return $i != $searchBase;
        });
        $settings->save();
    }

    public function render()
    {
        return view('livewire.ldap-settings');
    }
}
