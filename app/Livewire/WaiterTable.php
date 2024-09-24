<?php

namespace App\Livewire;

use App\Models\Waiter;
use Livewire\Component;
use Livewire\WithPagination;

class WaiterTable extends Component
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
        $items = Waiter::where('code_id', 5)
            ->where('kode_barang', 'like', '%' . $this->code . '%')
            ->where('nama_barang', 'like', '%' . $this->name . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.waiter-table', compact('items'));
    }
}
