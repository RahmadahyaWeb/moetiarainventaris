<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ChartTopOutItemTable extends Component
{
    public $code = '';
    public $name = '';
    public $status = '';
    public $division = '';
    public $from_date = '';
    public $to_date = '';
    public $show = 'all'; // Filter untuk Total Out

    public function mount()
    {
        // Mengatur nilai default untuk `from_date` dan `to_date`
        $this->to_date = now()->format('Y-m-d'); // Hari ini
        $this->from_date = now()->subWeek()->format('Y-m-d'); // Seminggu kebelakang
    }

    public function search()
    {
        $this->resetPage();
    }

    public function getDateRange()
    {
        return [
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
        ];
    }


    public function render()
    {
        $bakeries = DB::table('bakeries_history')
            ->select(['kode_barang', 'nama_barang', 'qty', 'type', 'tanggal', 'code_id'])
            ->where('status', 1)
            ->when($this->from_date && $this->to_date, function ($query) {
                $query->whereBetween('tanggal', [$this->from_date, $this->to_date]);
            })
            ->get();
        $baristas = DB::table('baristas_history')
            ->select(['kode_barang', 'nama_barang', 'qty', 'type', 'tanggal', 'code_id'])
            ->where('status', 1)
            ->when($this->from_date && $this->to_date, function ($query) {
                $query->whereBetween('tanggal', [$this->from_date, $this->to_date]);
            })
            ->get();
        $kitchens = DB::table('kitchens_history')
            ->select(['kode_barang', 'nama_barang', 'qty', 'type', 'tanggal', 'code_id'])
            ->where('status', 1)
            ->when($this->from_date && $this->to_date, function ($query) {
                $query->whereBetween('tanggal', [$this->from_date, $this->to_date]);
            })
            ->get();
        $operationals = DB::table('operationals_history')
            ->select(['kode_barang', 'nama_barang', 'qty', 'type', 'tanggal', 'code_id'])
            ->where('status', 1)
            ->when($this->from_date && $this->to_date, function ($query) {
                $query->whereBetween('tanggal', [$this->from_date, $this->to_date]);
            })
            ->get();
        $cashiers = DB::table('cashiers_history')
            ->select(['kode_barang', 'nama_barang', 'qty', 'type', 'tanggal', 'code_id'])
            ->where('status', 1)
            ->when($this->from_date && $this->to_date, function ($query) {
                $query->whereBetween('tanggal', [$this->from_date, $this->to_date]);
            })
            ->get();
        $waiters = DB::table('waiters_history')
            ->select(['kode_barang', 'nama_barang', 'qty', 'type', 'tanggal', 'code_id'])
            ->where('status', 1)
            ->when($this->from_date && $this->to_date, function ($query) {
                $query->whereBetween('tanggal', [$this->from_date, $this->to_date]);
            })
            ->get();

        // Gabungkan semua data
        $data = $bakeries->concat($baristas)
            ->concat($kitchens)
            ->concat($operationals)
            ->concat($cashiers)
            ->concat($waiters);

        if ($this->division !== '') {
            $division = (int)$this->division;
            $data = $data->filter(function ($item) use ($division) {
                return $item->code_id == $division;
            });
        }

        $data = $data->groupBy(function ($item) {
            return $item->kode_barang . '|' . $item->nama_barang; // Menghilangkan tanggal dari grouping
        })->map(function ($group) {
            return [
                'kode_barang' => $group->first()->kode_barang,
                'nama_barang' => $group->first()->nama_barang,
                'total_in' => $group->sum(function ($item) {
                    return $item->type == 'in' ? $item->qty : 0;
                }),
                'total_out' => $group->sum(function ($item) {
                    return $item->type == 'out' ? $item->qty : 0;
                })
            ];
        });

        $labels = [];
        $totalOutValues = [];

        $topInItem = $data->sortByDesc('total_out')->take(3);

        foreach ($topInItem as $item) {
            $label = $item['nama_barang'] . ' (' . $item['kode_barang'] . ')';
            $labels[] = $label;
            $totalOutValues[] = $item['total_out'];
        }

        $this->dispatch('chart-top-out-updated', [
            'labels' => $labels,
            'totalOutValues' => $totalOutValues,
        ]);

        return view('livewire.chart-top-out-item-table', compact(
            'labels',
            'totalOutValues',
        ));
    }
}
