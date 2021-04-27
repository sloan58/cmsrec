<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class UsersTable extends Component
{
    public $term = '';

    public function render()
    {
        $users = User::when($this->term, function($query) {
            return $query->where('name', 'like', '%'.$this->term.'%')
                    ->orWhere('email', 'like', '%'.$this->term.'%');
        })->paginate(10);
        return view('livewire.users-table', [
            'users' => $users
        ]);
    }
}
