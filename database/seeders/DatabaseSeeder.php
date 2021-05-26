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
            CmsSeeder::class,
        ]);

        if(env('APP_ENV') === 'local') {
            $this->call([
                CmsCoSpaceSeeder::class,
                CmsRecordingSeeder::class
            ]);
        }
    }
}
