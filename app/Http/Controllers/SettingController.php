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

        if(!$this->mountExists($settings)) {

            $command = sprintf(
                'sudo /bin/mount -t nfs %s:%s %s',
                $settings->host,
                $settings->path,
                \Storage::disk('recordings')->path('')
            );

            $process = Process::fromShellCommandline($command);

            try {
                $process->mustRun();
                $this->updateNfsMntView($settings);
            } catch (\Exception $e) {
                echo $e->getMessage();
                flash()->success('Error updating NFS Settings: ' . $e->getMessage());
                return back();
            }
        }

        flash()->success('NFS Settings Updated!');
        return back();
    }

    /**
     * Check if the NFS mount exists
     *
     * @param NfsSettings $settings
     * @return bool
     */
    private function mountExists(NfsSettings $settings)
    {

        $command = sprintf(
            'df -h | grep %s',
            $settings->host,
        );

        $process = Process::fromShellCommandline($command);

        try {
            $process->mustRun();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Update the NFS Mount View
     *
     * @param NfsSettings $settings
     * @return bool
     */
    private function updateNfsMntView(NfsSettings $settings)
    {
        $command = sprintf(
            'df -h | head -1; df -h | grep %s',
            $settings->host,
            );

        $process = Process::fromShellCommandline($command);

        try {
            $process->mustRun();
            $output = $process->getOutput();
            $settings->mnt_view = $this->formatDiskOutput($output);
            $settings->save();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Format the output from the du -h command
     *
     * @param $output
     * @return void
     */
    private function formatDiskOutput($output)
    {
        $output = array_filter(explode("\n", $output));
        $keys = array_filter(explode(' ', $output[0]));
        $on = array_pop($keys);
        $mounted = array_pop($keys);
        $mountedOn = "$mounted $on";
        $keys[] = $mountedOn;
        $values = array_filter(explode(' ', $output[1]));
        $mnt_view = array_combine($keys, $values);

        return $mnt_view;
    }
}
