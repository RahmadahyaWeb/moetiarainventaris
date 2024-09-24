<?php

namespace App\Livewire;

use App\Models\Cashier;
use Livewire\Component;
use Livewire\WithPagination;

class CashierTable extends Component
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
        $items = Cashier::where('code_id', 6)
            ->where('kode_barang', 'like', '%' . $this->code . '%')
            ->where('nama_barang', 'like', '%' . $this->name . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.cashier-table', compact('items'));
    }
}
