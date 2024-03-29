<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
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
        DB::table('users')->insert([
            'username' => 'Admin',
            'name' => 'Admin',
            'email' => 'admin@cmsplayer.com',
            'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'cms_owner_id' => '559416e8-7a91-4367-8ec2-2ff236ac934e',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        if(env('APP_ENV') === 'local') {
            DB::table('users')->insert([
                'username' => 'Marty Sloan',
                'name' => 'Marty Sloan',
                'email' => 'marty@cmsplayer.com',
                'email_verified_at' => now(),
                'password' => Hash::make('secret'),
                'cms_owner_id' => '559416e8-7a91-4367-8ec2-2ff236ac934f',
                'created_at' => now(),
                'updated_at' => now()
            ]);   
        }
    }
}
