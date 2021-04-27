<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\CmsRecording;

class CmsRecordingsPage extends Component
{
    public $term = '';

    public function render()
    {
        $cmsRecordings = CmsRecording::when($this->term, function ($query) {
            $query->where('filename', 'like', '%' . $this->term . '%');
        })->get()->sortBy(function ($recording) {
                return $recording->cmsCoSpace->name;
            });

        info('recordings', [
            $cmsRecordings->count()
        ]);

        return view('livewire.cms-recordings-page', [
            'cmsRecordings' => $cmsRecordings
        ]);
    }
}
