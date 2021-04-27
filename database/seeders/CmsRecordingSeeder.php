<?php

namespace Database\Seeders;

use App\Models\CmsRecording;
use App\Models\CmsCoSpace;
use Illuminate\Database\Seeder;

class CmsRecordingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Demo mp4 Video Links
        // https://gist.github.com/jsturgis/3b19447b304616f18657
        $demoVideos = [
            'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
            'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4',
            'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4',
        ];

        $disk = \Storage::disk('recordings');
        $coSpace = CmsCoSpace::first();

        // Remove current videos to start fresh
        foreach($disk->files($coSpace->space_id) as $vidOnDisk) {
            $disk->delete($vidOnDisk);
        }

        // Create new recordings and link demo videos
        foreach($demoVideos as $demoVideo) {

            $storeTo = $coSpace->space_id . '/' . basename($demoVideo);
            $videoDownload = file_get_contents($demoVideo);
            $disk->put($storeTo, $videoDownload);

            $size = $disk->size($storeTo);
            $last_modified = $disk->lastModified($storeTo);

            CmsRecording::create([
                'filename' => basename($demoVideo),
                'size' => $size,
                'last_modified' => $last_modified,
                'cms_co_space_id' => $coSpace->id,
                'is_shared' => false
            ]);
        }
    }
}
