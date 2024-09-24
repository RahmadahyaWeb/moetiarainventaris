<?php

namespace App\Http\Controllers;

use App\Models\Code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HistoryExportController extends Controller
{

    public function guard()
    {
        $user = Auth::user();

        if ($user && $user->role_id === 1 || $user->role_id === 2) {
            return $this;
        }

        abort(403);
    }

    public function export(?string $fromDate, ?string $toDate, ?string $division)
    {
        $this->guard();
        if ($division == 'bakeries') {
            $table = 'bakeries_history';
            $title = 'Bakery';
            $code_division = 3;
            $code_id = 1;
        } else if ($division == 'baristas') {
            $table = 'baristas_history';
            $title = 'Barista';
            $code_division = 6;
            $code_id = 2;
        } else if ($division == 'kitchens') {
            $table = 'kitchens_history';
            $title = 'Kitchen';
            $code_division = 7;
            $code_id = 3;
        } else if ($division == 'operationals') {
            $table = 'operationals_history';
            $title = 'Operational';
            $code_division = 4;
            $code_id = 4;
        } else if ($division == 'waiters') {
            $table = 'waiters_history';
            $title = 'Waiter';
            $code_division = 5;
            $code_id = 5;
        } else if ($division == 'cashiers') {
            $table = 'cashiers_history';
            $title = 'Cashier';
            $code_division = 8;
            $code_id = 6;
        }

        $items = DB::table($table)
            ->join('users', "$table.user_id", '=', 'users.id')
            ->join('units', "$table.unit_id", '=', 'units.id')
            ->select("$table.*", 'users.name as user_name', 'units.code as unit')
            ->where("$table.code_id", $code_id)
            ->whereBetween("$table.tanggal", [$fromDate, $toDate])
            ->orderBy("$table.created_at", 'desc')
            ->get();

        if ($items->count() == 0) {
            return back()->with('error', 'Data not found');
        }

        $codes = Code::where('role_id', $code_division)->first();
        $filename = $codes->code . '_' . $fromDate . '_' . $toDate . '.xls';

        return view('history_report', compact('items', 'filename'));
    }
}
