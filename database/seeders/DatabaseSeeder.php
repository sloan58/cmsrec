<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
        ]);

        if(env('APP_ENV') === 'local') {
            $this->call([
                CmsSeeder::class,
                CmsCoSpaceSeeder::class,
                CmsRecordingSeeder::class
            ]);
        }
    }
}
