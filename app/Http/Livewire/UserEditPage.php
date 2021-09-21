<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\CmsCoSpace;
use Livewire\WithPagination;

class UserEditPage extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $user;
    public $term = '';
    public $addingCoSpaceToUser = false;

    public function addCoSpaceToUser($coSpaceId)
    {
        $this->user->cmsCoSpaces()->attach($coSpaceId, ['admin_assigned' => true]);
        $this->addingCoSpaceToUser = false;
        $this->term = '';
    }

    public function removeCoSpaceFromUser($coSpaceId)
    {
        $this->user->cmsCoSpaces()->detach($coSpaceId);
        $this->resetPage();
    }

    public function render()
    {
        $currentAssignedCoSpaces = $this->user->cmsCoSpaces();
        $availableCoSpaces = CmsCoSpace::when($this->term, function($q) {
                    $q->where('name', 'like', "%{$this->term}%");
                })
            ->whereNotIn('id', $this->user->cmsCoSpaces()->pluck('cms_co_space_id')->toArray());

        return view('livewire.user-edit-page', [
            'currentAssignedCoSpaces' => $currentAssignedCoSpaces->paginate(10),
            'availableCoSpaces' => $availableCoSpaces->paginate(10)
        ]);
    }
}
