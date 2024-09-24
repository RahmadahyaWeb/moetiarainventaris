<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class ApprovalTable extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $code = '';
    public $name = '';
    public $status = '';
    public $division = '';

    public function search()
    {
        $this->resetPage();
    }

    public function render()
    {
        $bakeries = DB::table('bakeries_history')->get();
        $baristas = DB::table('baristas_history')->get();
        $kitchens = DB::table('kitchens_history')->get();
        $operationals = DB::table('operationals_history')->get();
        $cashiers = DB::table('cashiers_history')->get();
        $waiters = DB::table('waiters_history')->get();

        $combined = $bakeries->concat($baristas)
            ->concat($kitchens)
            ->concat($operationals)
            ->concat($cashiers)
            ->concat($waiters);

        // Apply search filters
        if ($this->code) {
            $combined = $combined->filter(function ($item) {
                return str_contains(strtolower($item->kode_barang), strtolower($this->code));
            });
        }

        if ($this->name) {
            $combined = $combined->filter(function ($item) {
                return str_contains(strtolower($item->nama_barang), strtolower($this->name));
            });
        }

        if ($this->status !== '') {
            $status = (int)$this->status;
            $combined = $combined->filter(function ($item) use ($status) {
                return $item->status == $status;
            });
        }

        if ($this->division !== '') {
            $division = (int)$this->division;
            $combined = $combined->filter(function ($item) use ($division) {
                return $item->code_id == $division;
            });
        }


        $combined = $combined->sortByDesc('created_at');

        $userIds = $combined->pluck('user_id')->unique();

        $users = DB::table('users')->whereIn('id', $userIds)->get();

        $dataWithUsers = $combined->map(function ($item) use ($users) {
            $item->user = $users->firstWhere('id', $item->user_id);
            return $item;
        });

        $divisiIds = $dataWithUsers->pluck('code_id')->unique();

        $divisions = DB::table('codes')->whereIn('id', $divisiIds)->get();

        $dataWithDivisionsAndRoles = $combined->map(function ($item) use ($divisions) {
            $item->division = $divisions->firstWhere('id', $item->code_id);

            $roleIds = $divisions->pluck('role_id')->unique();

            $roles = DB::table('roles')->whereIn('id', $roleIds)->get();

            $dataWithRoles = $divisions->map(function ($item) use ($roles) {
                $item->role = $roles->firstWhere('id', $item->role_id);
                return $item;
            });

            return $item;
        });

        $combined = $dataWithDivisionsAndRoles;

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;

        $paginator = new LengthAwarePaginator(
            $combined->forPage($currentPage, $perPage),
            $combined->count(),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );

        $items = $paginator;



        return view('livewire.approval-table', compact('items'));
    }
}
