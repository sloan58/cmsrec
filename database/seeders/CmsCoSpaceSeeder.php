<?php

namespace Database\Seeders;

use Str;
use Storage;
use App\Models\User;
use App\Models\CmsCoSpace;
use Illuminate\Database\Seeder;

class CmsCoSpaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coSpace = CmsCoSpace::create([
            'space_id' => 'b9965863-56b0-43b6-a613-c19997c9e6f5',
            'name' => 'Admin Demo Space',
            'ownerId' => User::first()->cms_owner_id
        ]);

        $storagePath = Storage::disk('recordings')->path($coSpace->space_id);
        if(!file_exists($storagePath)) {
            mkdir($storagePath);
        }
    }
}
