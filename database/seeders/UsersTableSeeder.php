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
            'name' => 'Admin',
            'email' => 'admin@cmsrec.com',
            'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'cms_owner_id' => \Str::uuid(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('users')->insert([
            'name' => 'Marty Sloan',
            'email' => 'marty@cmsrec.com',
            'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'cms_owner_id' => \Str::uuid(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

//        \App\Models\User::factory()->count(300)->create();
    }
}
