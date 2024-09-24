<?php

namespace App\Livewire;

use App\Models\Unit;
use Livewire\Component;
use Livewire\WithPagination;

class UnitTable extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $query = '';

    public function search()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.unit-table', [
            'units' => Unit::where('name', 'like', '%' . $this->query . '%')->latest()->paginate(15)
        ]);
    }
}
