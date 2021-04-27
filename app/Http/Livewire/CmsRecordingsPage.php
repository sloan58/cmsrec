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
        });

        if(!auth()->user()->isAdmin()) {
            $cmsRecordings = $cmsRecordings->with('owner')->whereHas('owner', function($query) {
                $query->where('id', auth()->user()->id);
            });
        }

        $cmsRecordings = $cmsRecordings->get()->sortBy(function ($recording) {
            return $recording->cmsCoSpace->name;
        });

        return view('livewire.cms-recordings-page', [
            'cmsRecordings' => $cmsRecordings
        ]);
    }
}
