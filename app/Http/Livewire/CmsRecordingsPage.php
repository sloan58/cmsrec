<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\CmsRecording;
use Livewire\WithPagination;

class CmsRecordingsPage extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['playRecording' => 'startPlayback'];

    public $term = '';
    public $paginate = 10;
    public $showAll = false;
    public $recordingShouldPlay = false;
    public $recordingInPlayback = null;


    /**
     * Start playing the recording in the UI
     *
     * @param CmsRecording $cmsRecording
     */
    public function startPlayback(CmsRecording $cmsRecording)
    {
        $this->recordingInPlayback = $cmsRecording;
        $this->recordingShouldPlay = true;
    }

    /**
     * Stop playing the recording in the UI
     */
    public function stopPlayback()
    {
        $this->recordingInPlayback = null;
        $this->recordingShouldPlay = false;
    }

    public function render()
    {
        $cmsRecordings = CmsRecording::with(['cmsCoSpace', 'owner'])->when($this->term, function ($query) {
            $query->where('filename', 'like', '%' . $this->term . '%');
            $query->orWhereHas('cmsCoSpace', function($query) {
                $query->where('name', 'like', '%' . $this->term . '%');
            });
            if(auth()->user()->isAdmin()) {
                $query->orWhereHas('owner', function($query) {
                    $query->where('name', 'like', '%' . $this->term . '%');
                });
            }
        });

        if(!auth()->user()->isAdmin() || (auth()->user()->isAdmin() && !$this->showAll)) {
            $cmsRecordings = $cmsRecordings->with('owner')->whereHas('owner', function($query) {
                $query->where('id', auth()->user()->id);
            });
        }

        $cmsRecordings = $cmsRecordings->orderBy('created_at', 'desc')->paginate($this->paginate);

        return view('livewire.cms-recordings-page', [
            'cmsRecordings' => $cmsRecordings
        ]);
    }
}
