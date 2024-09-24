<?php

namespace App\Http\Controllers;

use App\Models\Code;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function guard()
    {
        $user = Auth::user();

        if ($user && $user->role_id === 1 || $user->role_id === 2) {
            return $this;
        }

        abort(403);
    }

    public function pdf(Request $request, $division)
    {
        $this->guard();
        $request->validate([
            'fromDate'  => 'required',
            'toDate'    => 'required',
        ]);

        if ($division == 'bakeries') {
            $table = 'bakeries_history';
            $title = 'Bakery';
            $code_division = 3;
        } else if ($division == 'baristas') {
            $table = 'baristas_history';
            $title = 'Barista';
            $code_division = 6;
        } else if ($division == 'kitchens') {
            $table = 'kitchens_history';
            $title = 'Kitchen';
            $code_division = 7;
        } else if ($division == 'operationals') {
            $table = 'operationals_history';
            $title = 'Operational';
            $code_division = 4;
        } else if ($division == 'waiters') {
            $table = 'waiters_history';
            $title = 'Waiter';
            $code_division = 5;
        } else if ($division == 'cashiers') {
            $table = 'cashiers_history';
            $title = 'Cashier';
            $code_division = 8;
        }

        $codes = Code::where('role_id', $code_division)->first();
        $filename = $codes->code . '_' . $request->fromDate . '_' . $request->toDate . '.pdf';

        $data = DB::table($table)
            ->select([
                'tanggal',
                'kode_barang',
                'nama_barang',
                DB::raw('SUM(CASE WHEN type = "in" THEN qty ELSE 0 END) as total_in'),
                DB::raw('SUM(CASE WHEN type = "out" THEN qty ELSE 0 END) as total_out'),
                'units.code',

            ])
            ->join('units', 'units.id', '=', $table . '.unit_id')
            ->where('status', 1)
            ->whereBetween('tanggal', [$request->fromDate, $request->toDate])
            ->groupBy('tanggal', 'kode_barang', 'nama_barang', 'units.code')
            ->orderBy('tanggal', 'asc')
            ->get()
            ->map(function ($item) {
                $item->stock_akhir = abs($item->total_out - $item->total_in);
                return $item;
            });

        $startDateFilter = Carbon::parse($request->fromDate);
        $endDateFilter = Carbon::parse($request->toDate);
        $intervals = [];

        $grouped = $data->groupBy(function ($item) {
            return Carbon::parse($item->tanggal)->startOfWeek()->format('Y-m-d');
        });

        foreach ($grouped as $weekStart => $items) {
            $startDate = Carbon::parse($weekStart);
            $endDate = $startDate->copy()->endOfWeek();

            if ($endDate->greaterThan($endDateFilter)) {
                $endDate = $endDateFilter;
            }

            $intervals[] = [
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'data' => $items->toArray(),
            ];
        }

        if ($data->count() == 0) {
            return back()->with('error', 'Data not found');
        }

        $pdf = PDF::loadView('pdf', [
            'title'     => 'Laporan Inventaris Divisi ' . $title,
            'code'      => $codes->code,
            'fromDate'  => $request->fromDate,
            'toDate'    => $request->toDate,
            'data'      => $intervals,
        ]);


        return $pdf->download($filename);
    }

    public function excel($fromDate, $toDate, $division)
    {

        if ($division == 'bakeries') {
            $table = 'bakeries_history';
            $mainTable = 'bakeries';
            $code_division = 3;
        } else if ($division == 'baristas') {
            $table = 'baristas_history';
            $mainTable = 'baristas';
            $code_division = 6;
        } else if ($division == 'kitchens') {
            $table = 'kitchens_history';
            $mainTable = 'kitchens';
            $code_division = 7;
        } else if ($division == 'operationals') {
            $table = 'operationals_history';
            $mainTable = 'operationals';
            $code_division = 4;
        } else if ($division == 'waiters') {
            $table = 'waiters_history';
            $mainTable = 'waiters';
            $code_division = 5;
        } else if ($division == 'cashiers') {
            $table = 'cashiers_history';
            $mainTable = 'cashiers';
            $code_division = 8;
        }

        $data = DB::table($table)
            ->select([
                "{$table}.tanggal",
                "{$table}.kode_barang",
                "{$table}.nama_barang",
                DB::raw("SUM(CASE WHEN {$table}.type = 'in' THEN {$table}.qty ELSE 0 END) as total_in"),
                DB::raw("SUM(CASE WHEN {$table}.type = 'out' THEN {$table}.qty ELSE 0 END) as total_out"),
                'units.code',
                "{$mainTable}.minimum",
                "{$table}.tanggal",
                DB::raw("(SELECT price FROM $mainTable WHERE kode_barang = {$table}.kode_barang LIMIT 1) as price"),
            ])
            ->join('units', 'units.id', '=', "{$table}.unit_id")
            ->join($mainTable, "{$mainTable}.kode_barang", '=', "{$table}.kode_barang")
            ->where("{$table}.status", 1)
            ->whereBetween("{$table}.tanggal", [$fromDate, $toDate])
            ->groupBy("{$table}.tanggal", "{$table}.kode_barang", "{$table}.nama_barang", 'units.code', "{$mainTable}.minimum")
            ->orderBy("{$table}.tanggal", 'asc')
            ->get()
            ->map(function ($item) {
                // Calculate final stock
                $item->stock_akhir = abs($item->total_out - $item->total_in);
                return $item;
            });

        if ($data->count() == 0) {
            return back()->with('error', 'Data not found');
        }

        $dates = DB::table($table)
            ->select('tanggal')
            ->whereBetween('tanggal', [$fromDate, $toDate])
            ->groupBy('tanggal')
            ->pluck('tanggal')
            ->toArray();

        $groupedData = $data->groupBy('tanggal');

        $codes = Code::where('role_id', $code_division)->first();
        $filename = $codes->code . '_' . $fromDate . '_' . $toDate . '.xls';

        $start = new DateTime($fromDate);
        $end = new DateTime($toDate);

        $tanggalArray = [];

        for ($date = $start; $date <= $end; $date->modify('+1 day')) {
            $tanggalArray[] = $date->format('Y-m-d');
        }

        return view('excel', [
            'filename' => $filename,
            'items' => $data,
            'dates' => $dates,
            'fromDate' => Carbon::parse($fromDate)->format('d/m/Y'),
            'toDate' => Carbon::parse($toDate)->format('d/m/Y'),
            'groupedData' => $groupedData,
            'tanggalArray' => $tanggalArray,
        ]);
    }
}
