<?php

namespace App\Livewire;

use App\Models\Bakery;
use Livewire\Component;
use Livewire\WithPagination;

class BakeryTable extends Component
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
        $bakeries = Bakery::where('code_id', 1)
        ->where('kode_barang', 'like', '%' . $this->code .'%')
        ->where('nama_barang', 'like', '%' . $this->name .'%')
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        return view('livewire.bakery-table', compact('bakeries'));
    }
}
