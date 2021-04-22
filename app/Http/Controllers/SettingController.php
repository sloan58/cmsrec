<?php

namespace App\Http\Controllers;

use App\Settings\LdapSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\LdapSettingRequest;

class SettingController extends Controller
{
    /**
     * @return View
     */
    public function index()
    {
        return view('settings');
    }

    /**
     * @param LdapSettingRequest $request
     * @param LdapSettings $settings
     * @return RedirectResponse
     */
    public function updateLdapSettings(LdapSettingRequest $request, LdapSettings $settings)
    {
        $settings->name = $request->get('name');
        $settings->host = $request->get('host');
        $settings->searchBase = $request->get('searchBase');
        $settings->bindDN = $request->get('bindDN');
        $settings->password = $request->get('password');
        if($request->get('filter')) {
            $settings->filter = $request->input('filter');
        } elseif($settings->filter) {
            $settings->filter = '';
        }

        $settings->save();
        flash()->success('LDAP Settings Updated!');
        return back();
    }
}
