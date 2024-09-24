<?php

namespace App\Livewire;

use App\Models\Kitchen;
use Livewire\Component;
use Livewire\WithPagination;

class KitchenTable extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $name = '';
    public $code = '';

    public function search()
    {
        $this->resetPage();
    }

    public function render()
    {
        $items = Kitchen::where('code_id', 3)
            ->where('kode_barang', 'like', '%' . $this->code . '%')
            ->where('nama_barang', 'like', '%' . $this->name . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.kitchen-table', compact('items'));
    }
}
