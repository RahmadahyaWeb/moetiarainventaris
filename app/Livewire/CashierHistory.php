<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class CashierHistory extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $name = '';
    public $code = '';
    public $type = '';
    public $fromDate = '';
    public $toDate = '';

    public function search()
    {
        $this->resetPage();
    }

    public function render()
    {
        $items = DB::table('cashiers_history')
            ->where('code_id', 6)
            ->join('users', 'cashiers_history.user_id', '=', 'users.id')
            ->join('units', 'cashiers_history.unit_id', '=', 'units.id')
            ->select('cashiers_history.*', 'users.name as user_name', 'units.code as unit')
            ->where('kode_barang', 'like', '%' . $this->code . '%')
            ->where('nama_barang', 'like', '%' . $this->name . '%')
            ->when($this->type, function ($query) {
                return $query->where('type', 'like', '%' . $this->type . '%');
            })
            ->when($this->fromDate && $this->toDate, function ($query) {
                return $query->whereBetween('cashiers_history.tanggal', [$this->fromDate, $this->toDate]);
            })
            ->orderBy('cashiers_history.created_at', 'desc')
            ->paginate(15);

        return view('livewire.cashier-history', compact('items'));
    }
}
