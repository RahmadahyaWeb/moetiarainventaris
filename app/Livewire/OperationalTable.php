<?php

namespace App\Livewire;

use App\Models\Operational;
use Livewire\Component;
use Livewire\WithPagination;

class OperationalTable extends Component
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
        $items = Operational::where('code_id', 4)
            ->where('kode_barang', 'like', '%' . $this->code . '%')
            ->where('nama_barang', 'like', '%' . $this->name . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.operational-table', compact('items'));
    }
}
