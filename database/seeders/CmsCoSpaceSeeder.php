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
        $coSpaceIds = [
            '529416e8-7a91-4367-8ec2-2ff236ac934e',
            '529416e8-7a91-4367-8ec2-2ff236ac934f',
            '529416e8-7a91-4367-8ec2-2ff236ac934g',
        ];

        foreach($coSpaceIds as $index => $coSpaceId) {
            $coSpace = CmsCoSpace::create([
                'space_id' => $coSpaceId,
                'name' => 'Admin Demo Space ' . ($index + 1),
                'ownerId' => User::first()->cms_owner_id
            ]);

            $storagePath = Storage::disk('recordings')->path($coSpace->space_id);
            if(!file_exists($storagePath)) {
                mkdir($storagePath);
            }
        }
    }
}
