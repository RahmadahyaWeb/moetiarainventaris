<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class OperationalHistory extends Component
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
        $items = DB::table('operationals_history')
            ->where('code_id', 4)
            ->join('users', 'operationals_history.user_id', '=', 'users.id')
            ->join('units', 'operationals_history.unit_id', '=', 'units.id')
            ->select('operationals_history.*', 'users.name as user_name', 'units.code as unit')
            ->where('kode_barang', 'like', '%' . $this->code . '%')
            ->where('nama_barang', 'like', '%' . $this->name . '%')
            ->when($this->type, function ($query) {
                return $query->where('type', 'like', '%' . $this->type . '%');
            })
            ->when($this->fromDate && $this->toDate, function ($query) {
                return $query->whereBetween('operationals_history.tanggal', [$this->fromDate, $this->toDate]);
            })
            ->orderBy('operationals_history.created_at', 'desc')
            ->paginate(15);

        return view('livewire.operational-history', compact('items'));
    }
}
