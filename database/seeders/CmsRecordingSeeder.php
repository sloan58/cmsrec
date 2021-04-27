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
            'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4',
            'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4',
            'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4',
            'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerMeltdowns.mp4',
            'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/Sintel.mp4',
            'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/SubaruOutbackOnStreetAndDirt.mp4',
            'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/TearsOfSteel.mp4',
            'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/VolkswagenGTIReview.mp4',
            'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/WeAreGoingOnBullrun.mp4',
        ];

        $disk = \Storage::disk('recordings');

        $coSpaceIds = [
            '529416e8-7a91-4367-8ec2-2ff236ac934e',
            '529416e8-7a91-4367-8ec2-2ff236ac934f',
            '529416e8-7a91-4367-8ec2-2ff236ac934g',
        ];

        // Remove demo videos to start fresh
        foreach($coSpaceIds as $coSpaceId) {
            foreach($disk->files($coSpaceId) as $vidOnDisk) {
                $disk->delete($vidOnDisk);
            }
        }

        CmsCoSpace::each(function($space, $key) use ($disk, $demoVideos, &$videoIndex) {

            $iter = $key * 4;
            for($i=$iter; $i<($iter + 4); $i++) {
                $storeTo = $space->space_id . '/' . basename($demoVideos[$i]);
                $videoDownload = file_get_contents($demoVideos[$i]);
                $disk->put($storeTo, $videoDownload);

                $size = $disk->size($storeTo);
                $last_modified = $disk->lastModified($storeTo);

                CmsRecording::create([
                    'filename' => basename($demoVideos[$i]),
                    'size' => $size,
                    'last_modified' => $last_modified,
                    'cms_co_space_id' => $space->id,
                    'shared' => false
                ]);
            }
        });
    }
}
