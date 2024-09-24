<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class UserTable extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $roles;
    public $query = '';
    public $role_id = '';

    public function search()
    {
        $this->resetPage();
    }

    public function render()
    {


        $userId = Auth::id();
        return view('livewire.user-table', [
            'users' => User::where('name', 'like', '%' . $this->query . '%')
                ->where('id', '!=', $userId)
                ->where('role_id', '!=', 1)
                ->where('role_id', 'like', '%' . $this->role_id . '%')
                ->orderBy('name', 'asc')
                ->paginate(15)
        ]);
    }
}
