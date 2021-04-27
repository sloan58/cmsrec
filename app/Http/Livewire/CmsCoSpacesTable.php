<?php

namespace App\Http\Livewire;

use App\Models\CmsCoSpace;
use Livewire\Component;

class CmsCoSpacesTable extends Component
{
    public $term = '';

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
