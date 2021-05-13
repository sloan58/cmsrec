<?php

namespace App\Http\Controllers;

use App\Settings\NfsSettings;
use App\Settings\LdapSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\Process\Process;
use App\Http\Requests\NfsSettingRequest;
use App\Http\Requests\LdapSettingRequest;

class SettingController extends Controller
{
    /**
     * @return View
     */
    public function index()
    {
        return view('settings.index');
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

    /**
     * @param NfsSettingRequest $request
     * @param NfsSettings $settings
     * @return RedirectResponse
     */
    public function updateNfsSettings(NfsSettingRequest $request, NfsSettings $settings)
    {
        $settings->host = $request->get('host');
        $settings->path = $request->get('path');

        $settings->save();

        $command = sprintf(
            '/sbin/mount -t nfs %s:%s %s',
            $settings->host,
            $settings->path,
            \Storage::disk('recordings')->path('')
        );

        if(!$this->mountExists()) {
            $process = Process::fromShellCommandline($command);

            try {
                $process->mustRun();
            } catch (\Exception $e) {
                echo $e->getMessage();
                flash()->success('Error updating NFS Settings: ' . $e->getMessage());
                return back();
            }
        }

        flash()->success('NFS Settings Updated!');
        return back();
    }

    private function mountExists()
    {

        $command = sprintf(
            'df -h | grep %s',
            app(NfsSettings::class)->host,
        );

        $process = Process::fromShellCommandline($command);

        try {
            $process->mustRun();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
