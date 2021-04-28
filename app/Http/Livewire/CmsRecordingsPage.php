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
    public $searchBy = 'Recording Name';
    public $recordingShouldPlay = false;
    public $recording = null;


    /**
     * Start playing the recording in the UI
     *
     * @param CmsRecording $cmsRecording
     */
    public function startPlayback(CmsRecording $cmsRecording)
    {
        $this->recording = $cmsRecording;
        $this->recordingShouldPlay = true;
    }

    /**
     * Stop playing the recording in the UI
     */
    public function stopPlayback()
    {
        $this->recording = null;
        $this->recordingShouldPlay = false;
    }

    public function render()
    {
        $cmsRecordings = CmsRecording::with(['cmsCoSpace', 'owner'])->when($this->term, function ($query) {
            switch($this->searchBy) {
                case 'Recording Name':
                    $query->where('filename', 'like', '%' . $this->term . '%');
                    break;
                case 'Space Name':
                    $query->whereHas('cmsCoSpace', function($query) {
                        $query->where('name', 'like', '%' . $this->term . '%');
                    });
                    break;
                case 'Owner Name':
                    $query->whereHas('owner', function($query) {
                        $query->where('name', 'like', '%' . $this->term . '%');
                    });
                    break;
            }
        });

        info('there');
        if(!auth()->user()->isAdmin() || (auth()->user()->isAdmin() && !$this->showAll)) {
            info('here');
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
