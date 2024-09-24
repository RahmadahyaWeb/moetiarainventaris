<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class BakeryHistory extends Component
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
        $bakeries = DB::table('bakeries_history')
            ->join('users', 'bakeries_history.user_id', '=', 'users.id')
            ->join('units', 'bakeries_history.unit_id', '=', 'units.id')
            ->select('bakeries_history.*', 'users.name as user_name', 'units.code as unit')
            ->where('bakeries_history.code_id', 1)
            ->where('bakeries_history.kode_barang', 'like', '%' . $this->code . '%')
            ->where('bakeries_history.nama_barang', 'like', '%' . $this->name . '%')
            ->when($this->type, function ($query) {
                return $query->where('type', 'like', '%' . $this->type . '%');
            })
            ->when($this->fromDate && $this->toDate, function ($query) {
                return $query->whereBetween('bakeries_history.tanggal', [$this->fromDate, $this->toDate]);
            })
            ->orderBy('bakeries_history.created_at', 'desc')
            ->paginate(15);

        return view('livewire.bakery-history', compact('bakeries'));
    }
}
