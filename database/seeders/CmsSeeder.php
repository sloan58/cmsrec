<?php

namespace Database\Seeders;

use App\Models\Cms;
use Illuminate\Database\Seeder;

class CmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cms::create([
            'name' => 'DevCMS1',
            'host' => '10.10.10.10',
            'username' => 'admin',
            'password' => 'password'
        ]);
    }
}
