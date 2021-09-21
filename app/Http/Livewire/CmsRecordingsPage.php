<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\CmsRecording;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CmsRecordingsPage extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'startPlayback' => 'startPlayback',
        'recordingDeleted' => '$refresh'
    ];

    public $term = '';
    public $paginate = 10;
    public $showAll = false;
    public $recordingShouldPlay = false;
    public $recordingInPlayback = null;

    /**
     * Set initial component values
     */
    public function mount()
    {
        $this->showAll = auth()->user()->ui_state['recordings->showAll'] ?? $this->showAll;
        $this->term = auth()->user()->ui_state['recordings->term'] ?? $this->term;
        $this->paginate = auth()->user()->ui_state['recordings->paginate'] ?? $this->paginate;
    }

    /**
     * The $term property has updated
     */
    public function updatedTerm()
    {
        auth()->user()->updateUiState('recordings->term', $this->term);
    }

    /**
     * The $showAll property has updated
     */
    public function updatedShowAll()
    {
        auth()->user()->updateUiState('recordings->showAll', $this->showAll);
    }

    /**
     * The $paginate property has updated
     */
    public function updatedPaginate()
    {
        auth()->user()->updateUiState('recordings->paginate', $this->paginate);
    }

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
        $results = $this->buildCmsRecordingsSearchHits();

        return view('livewire.cms-recordings-page', [
            'cmsRecordings' => $results
        ]);
    }

    /**
     * Build the CmsRecordings search results
     *
     * @return LengthAwarePaginator|Builder|mixed
     */
    private function buildCmsRecordingsSearchHits()
    {
        if(auth()->user()->isAdmin() && $this->showAll) {
            $cmsRecordings = CmsRecording::with('cmsCoSpace')->when($this->term, function ($query) {
                $query->where('filename', 'like', '%' . $this->term . '%');
                $query->orWhereHas('cmsCoSpace', function ($query) {
                    $query->where('name', 'like', '%' . $this->term . '%');
                    $query->orWhereHas('owners', function($query) {
                        $query->where('name', 'like', '%' . $this->term . '%');
                    });
                });
            });
        } else {
            $cmsRecordings = auth()->user()->cmsRecordings()->with('cmsCoSpace')->when($this->term, function ($query) {
                $query->where('filename', 'like', '%' . $this->term . '%');
                $query->orWhereHas('cmsCoSpace', function ($query) {
                    $query->where('name', 'like', '%' . $this->term . '%');
                });
            });
        }

        return $cmsRecordings->orderBy('created_at', 'desc')->paginate($this->paginate);
    }
}
