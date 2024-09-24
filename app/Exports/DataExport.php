<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class DataExport implements FromCollection, WithHeadings, WithMapping
{
    protected $fromDate;
    protected $toDate;
    protected $dates;

    public function __construct($fromDate, $toDate)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;

        $this->dates = DB::table('bakeries_history')
            ->select('tanggal')
            ->whereBetween('tanggal', [$this->fromDate, $this->toDate])
            ->groupBy('tanggal')
            ->pluck('tanggal')
            ->toArray();
    }

    public function collection()
    {
        return DB::table('bakeries_history')
            ->select([
                'nama_barang',
                'kode_barang',
                'units.code as unit_code',
                DB::raw('SUM(CASE WHEN type = "in" THEN qty ELSE 0 END) as total_in'),
                DB::raw('SUM(CASE WHEN type = "out" THEN qty ELSE 0 END) as total_out'),
            ])
            ->join('units', 'units.id', '=', 'bakeries_history.unit_id')
            ->whereBetween('tanggal', [$this->fromDate, $this->toDate])
            ->groupBy('kode_barang', 'nama_barang', 'units.code')
            ->orderBy('nama_barang', 'asc')
            ->get();
    }

    public function headings(): array
    {
        $headings = [
            'NO', 'JENIS BARANG', 'SATUAN', 'STOK MINIMAL'
        ];

        foreach ($this->dates as $date) {
            $formattedDate = \Carbon\Carbon::parse($date)->format('d/m/Y');
            $headings[] = $formattedDate . ' IN';
            $headings[] = $formattedDate . ' OUT';
            $headings[] = $formattedDate . ' STOK';
        }

        return $headings;
    }

    public function map($row): array
    {
        // dd($row);
        $mapped = [
            $row->kode_barang,
            $row->nama_barang,
            $row->unit_code,
        ];

        // Ambil data berdasarkan setiap tanggal untuk IN, OUT, dan STOCK
        foreach ($this->dates as $date) {
            $stockData = DB::table('bakeries_history')
                ->select([
                    DB::raw('SUM(CASE WHEN type = "in" THEN qty ELSE 0 END) as total_in'),
                    DB::raw('SUM(CASE WHEN type = "out" THEN qty ELSE 0 END) as total_out'),
                ])
                ->where('kode_barang', $row->kode_barang)
                ->where('tanggal', $date)
                ->first();

            $in = $stockData->total_in ?? 0;
            $out = $stockData->total_out ?? 0;
            $stok_akhir = abs($in - $out);

            $mapped[] = $in;
            $mapped[] = $out;
            $mapped[] = $stok_akhir;
        }

        return $mapped;
    }
}
