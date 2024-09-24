<?php

namespace App\Livewire;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ItemTable extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $code = '';
    public $name = '';

    public function search()
    {
        $this->resetPage();
    }

    public function render()
    {
        if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2) {
            $bakeries = DB::table('bakeries')->get();
            $baristas = DB::table('baristas')->get();
            $kitchens = DB::table('kitchens')->get();
            $operationals = DB::table('operationals')->get();
            $cashiers = DB::table('cashiers')->get();
            $waiters = DB::table('waiters')->get();

            $combined = $bakeries->concat($baristas)
                ->concat($kitchens)
                ->concat($operationals)
                ->concat($cashiers)
                ->concat($waiters);

            // Filter data based on search query
            if (!empty($this->code)) {
                $combined = $combined->filter(function ($item) {
                    return stripos($item->kode_barang, $this->code) !== false;
                });
            }

            if (!empty($this->name)) {
                $combined = $combined->filter(function ($item) {
                    return stripos($item->nama_barang, $this->name) !== false;
                });
            }

            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $perPage = 5;

            $paginator = new LengthAwarePaginator(
                $combined->forPage($currentPage, $perPage),
                $combined->count(),
                $perPage,
                $currentPage,
                ['path' => Paginator::resolveCurrentPath()]
            );

            $items = $paginator;
        }


        return view('livewire.item-table', compact('items'));
    }
}
