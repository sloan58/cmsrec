<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\CmsCoSpace;
use Livewire\WithPagination;

class CmsCoSpacesTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $term = '';

    /**
     * Set initial component values
     */
    public function mount()
    {
        $this->term = auth()->user()->ui_state['spaces->term'] ?? $this->term;
    }

    /**
     * The $term property has updated
     */
    public function updatedTerm()
    {
        auth()->user()->updateUiState('spaces->term', $this->term);
    }

    public function render()
    {
        $cmsCoSpaces = CmsCoSpace::with('owner')->when($this->term, function($query) {
            return $query->where('name', 'like', '%'.$this->term.'%')->orWhereHas('owner', function($query) {
                $query->where('name', 'like', '%'.$this->term.'%');
            });
        })->paginate(10);

        return view('livewire.cms-co-spaces-table', [
            'cmsCoSpaces' => $cmsCoSpaces
        ]);
    }
}
