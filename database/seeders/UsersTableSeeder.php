<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'Admin',
            'name' => 'Admin',
            'email' => 'admin@cmsplayer.com',
            'password' => Hash::make('secret'),
        ]);

//        if(env('APP_ENV') === 'local') {
//            DB::table('users')->insert([
//                'username' => 'Marty Sloan',
//                'name' => 'Marty Sloan',
//                'email' => 'marty@cmsplayer.com',
//                'email_verified_at' => now(),
//                'password' => Hash::make('secret'),
//                'cms_owner_id' => '559416e8-7a91-4367-8ec2-2ff236ac934f',
//                'created_at' => now(),
//                'updated_at' => now()
//            ]);
//        }
    }
}
