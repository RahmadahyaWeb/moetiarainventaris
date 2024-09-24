<?php

namespace App\Livewire;

use App\Models\Bakery;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class ChartTable extends Component
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

        if ($this->code) {
            $data = $data->filter(function ($item) {
                return str_contains(strtolower($item->kode_barang), strtolower($this->code));
            });
        }

        $data = $data->groupBy(function ($item) {
            return $item->kode_barang . '|' . $item->nama_barang . '|' . $item->tanggal;
        })->map(function ($group) {
            return [
                'kode_barang' => $group->first()->kode_barang,
                'nama_barang' => $group->first()->nama_barang,
                'tanggal' => $group->first()->tanggal,
                'total_in' => $group->where('type', 'in')->sum('qty'),
                'total_out' => $group->where('type', 'out')->sum('qty')
            ];
        });

        $labels = [];
        $totalInValues = [];
        $totalOutValues = [];

        foreach ($data as $item) {
            $label = $item['nama_barang'] . ' (' . $item['tanggal'] . ')';
            $labels[] = $label;
            $totalInValues[] = $item['total_in'];
            $totalOutValues[] = $item['total_out'];
        }

        if ($this->show == 'in') {
            $showIn = true;
            $showOut = false;
        } else if ($this->show == 'out') {
            $showOut = true;
            $showIn = false;
        } else {
            $showIn = true;
            $showOut = true;
        }

        $this->dispatch('chart-data-updated', [
            'labels' => $labels,
            'totalInValues' => $totalInValues,
            'totalOutValues' => $totalOutValues,
            'showIn' => $showIn,
            'showOut' => $showOut,
        ]);

        return view('livewire.chart-table', compact(
            'labels',
            'totalInValues',
            'totalOutValues',
        ));
    }
}
