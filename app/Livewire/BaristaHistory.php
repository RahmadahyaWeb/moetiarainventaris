<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class BaristaHistory extends Component
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
        $items = DB::table('baristas_history')
            ->where('code_id', 2)
            ->join('users', 'baristas_history.user_id', '=', 'users.id')
            ->join('units', 'baristas_history.unit_id', '=', 'units.id')
            ->select('baristas_history.*', 'users.name as user_name', 'units.code as unit')
            ->where('kode_barang', 'like', '%' . $this->code . '%')
            ->where('nama_barang', 'like', '%' . $this->name . '%')
            ->when($this->type, function ($query) {
                return $query->where('type', 'like', '%' . $this->type . '%');
            })
            ->when($this->fromDate && $this->toDate, function ($query) {
                return $query->whereBetween('baristas_history.tanggal', [$this->fromDate, $this->toDate]);
            })
            ->orderBy('baristas_history.created_at', 'desc')
            ->paginate(15);

        return view('livewire.barista-history', compact('items'));
    }
}
