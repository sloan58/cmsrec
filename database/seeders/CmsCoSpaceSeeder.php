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
            'space_id' => '529416e8-7a91-4367-8ec2-2ff236ac934d',
            'name' => 'Admin Demo Space',
            'ownerId' => User::first()->cms_owner_id
        ]);

        $storagePath = Storage::disk('recordings')->path($coSpace->space_id);
        if(!file_exists($storagePath)) {
            mkdir($storagePath);
        }
    }
}
