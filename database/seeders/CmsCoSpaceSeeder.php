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
        $userOneCoSpaceIds = [
            '529416e8-7a91-4367-8ec2-2ff236ac934e',
            '529416e8-7a91-4367-8ec2-2ff236ac934f',
        ];

        foreach($userOneCoSpaceIds as $index => $coSpaceId) {
            $coSpace = CmsCoSpace::create([
                'space_id' => $coSpaceId,
                'name' => 'Demo Space ' . ($index + 1),
                'ownerId' => User::find(1)->cms_owner_id
            ]);

            $storagePath = Storage::disk('recordings')->path($coSpace->space_id);
            if(!file_exists($storagePath)) {
                mkdir($storagePath);
            }
        }

        $userTwoCoSpaceIds = [
            '529416e8-7a91-4367-8ec2-2ff236ac934g',
        ];

        foreach($userTwoCoSpaceIds as $index => $coSpaceId) {
            $coSpace = CmsCoSpace::create([
                'space_id' => $coSpaceId,
                'name' => 'Demo Space ' . ($index + 3),
                'ownerId' => User::find(2)->cms_owner_id
            ]);

            $storagePath = Storage::disk('recordings')->path($coSpace->space_id);
            if(!file_exists($storagePath)) {
                mkdir($storagePath);
            }
        }
    }
}
