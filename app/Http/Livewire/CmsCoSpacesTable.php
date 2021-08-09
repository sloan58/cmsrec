<?php

namespace App\Http\Livewire;

use App\Models\User;
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
        $userFilter = array_merge(
            ...User::where('name', 'like', '%'.$this->term.'%')
            ->pluck('cms_ownerIds')
            ->toArray()
        );

        $cmsCoSpaces = CmsCoSpace::when($this->term, function($query) use ($userFilter) {
            return $query->where('name', 'like', '%'.$this->term.'%')->orWhereIn('ownerId', $userFilter);
        })->paginate(10);

        return view('livewire.cms-co-spaces-table', [
            'cmsCoSpaces' => $cmsCoSpaces
        ]);
    }
}
