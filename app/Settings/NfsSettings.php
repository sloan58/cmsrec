<?php

namespace App\Settings;

use Exception;
use Spatie\LaravelSettings\Settings;
use Symfony\Component\Process\Process;

class NfsSettings extends Settings
{
    public string $host;
    public string $path;
    public array $mnt_view;

    public static function group(): string
    {
        return 'nfs';
    }

    /**
     * @param $host
     * @param $path
     * @return array
     */
    public function mountNfs($host, $path)
    {
        if(!$this->mountExists()) {

            $command = sprintf(
                'sudo %s/mount -t nfs %s:%s %s',
                env('MOUNT_PATH'),
                $host,
                $path,
                \Storage::disk('recordings')->path('')
            );

            $process = Process::fromShellCommandline($command);

            try {
                $process->mustRun();
                $this->host = $host;
                $this->path = $path;
                $this->save();
                $this->updateMountView();
                return [
                    'status' => 'success',
                    'message' => 'NFS Settings Updated!'
                ];
            } catch (\Exception $e) {
                logger()->error('Could not mount NFS ', [ $e->getMessage() ]);
                return [
                    'status' => 'error',
                    'message' => 'Error updating NFS Settings: ' . $e->getMessage()
                ];
            }
        }
    }

    public function unmountNfs()
    {
        if($this->mountExists()) {

            $command = sprintf(
                'sudo %s/umount -t nfs %s:%s',
                env('MOUNT_PATH'),
                $this->host,
                $this->path,
            );

            $this->host = '';
            $this->path = '';
            $this->mnt_view = [];
            $this->save();

            $process = Process::fromShellCommandline($command);

            try {
                $process->mustRun();
            } catch (\Exception $e) {
                logger()->error('Could not unmount NFS ', [ $e->getMessage() ]);
                return [
                    'status' => 'error',
                    'message' => 'Error updating NFS Settings: ' . $e->getMessage()
                ];
            }
        }
        return [
            'status' => 'success',
            'message' => 'NFS Settings Updated!'
        ];
    }
    /**
     * Check if this NFS mount exists on the filesystem
     *
     * @return bool
     */
    private function mountExists()
    {
        $command = sprintf(
            'df -h | grep %s | grep %s',
            $this->host,
            $this->path,
            );

        $process = Process::fromShellCommandline($command);

        try {
            $process->mustRun();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Update the NFS Mount View
     *
     * @return bool
     */
    private function updateMountView()
    {
        $command = sprintf(
            'df -h | head -1; df -h | grep %s',
            $this->host,
            );

        $process = Process::fromShellCommandline($command);

        try {
            $process->mustRun();
            $output = $process->getOutput();
            $this->mnt_view = $this->formatDiskOutput($output);
            $this->save();
            return true;
        } catch (\Exception $e) {
            logger()->error('Could not collect new mount view', [ $e->getMessage() ]);
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
