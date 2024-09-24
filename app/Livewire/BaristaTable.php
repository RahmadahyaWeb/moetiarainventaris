<?php

namespace App\Livewire;

use App\Models\Barista;
use Livewire\Component;
use Livewire\WithPagination;

class BaristaTable extends Component
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
        $items = Barista::where('code_id', 2)
            ->where('kode_barang', 'like', '%' . $this->code . '%')
            ->where('nama_barang', 'like', '%' . $this->name . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.barista-table', compact('items'));
    }
}
