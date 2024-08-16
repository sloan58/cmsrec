<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('tag', function () {
    echo "CoSpaceName,CoSpaceID,Tag\n";
    \App\Models\CmsCoSpace::whereHas('owners')->each(function ($coSpace) {
        $tag = explode(
            '@',
            $coSpace->owners()->first()->email
        )[1] ?? '';
        echo sprintf('"%s","%s","%s"', $coSpace->name,$coSpace->space_id,$tag);
    });
});
