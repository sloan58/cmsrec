<?php

namespace App\Http\Controllers;

use File;
use Exception;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Symfony\Component\Process\Process;

class LogController extends Controller
{
    /**
     * Return a view of the Laravel logs
     *
     * @return Factory|View
     * @throws Exception
     */
    public function index()
    {
        if(env('LOG_CHANNEL') == 'stack') {
            $filePath = storage_path("logs/laravel.log");
            $fileList = [];
        } else {
            $fileList = File::glob(storage_path("logs/*.log"));
            if(request()->has('file')) {
                $filePath = storage_path("logs/" . request()->get('file'));
            } else {
                $today = today('America/New_York')->format('Y-m-d');
                $filePath = storage_path("logs/laravel-{$today}.log");
            }
        }

        $data = [];
        if(File::exists($filePath)) {
            $process = Process::fromShellCommandline('tac ' . $filePath);

            try {
                $process->mustRun();
                $data = [
                    'last_modified' => new Carbon(File::lastModified($filePath)),
                    'size' => File::size($filePath),
                    'name' => File::basename($filePath),
                    'file' => $process->getOutput()
                ];
            } catch (\Exception $e) {
                logger()->error('LogController@index: Could not run Process ', [ $e->getMessage() ]);
                $data = [
                    'last_modified' => new Carbon(File::lastModified($filePath)),
                    'size' => File::size($filePath),
                    'name' => File::basename($filePath),
                    'file' => File::get($filePath)
                ];
            }


        }

        return view('pages.logs', compact('fileList', 'data'));
    }
}
