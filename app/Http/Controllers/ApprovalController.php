<?php

namespace App\Http\Controllers;

use App\Mail\LowStockAlert;
use App\Models\Bakery;
use App\Models\Barista;
use App\Models\Cashier;
use App\Models\Kitchen;
use App\Models\Operational;
use App\Models\Waiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ApprovalController extends Controller
{
    public function guard()
    {
        $user = Auth::user();

        if ($user && $user->role_id === 1 || $user->role_id === 2) {
            return $this;
        }

        abort(403);
    }

    public function index()
    {
        $this->guard();
        return view('approval.index');
    }

    public function edit($kode_barang, $code_id, $id, $division, $input_by)
    {
        $this->guard();
        $table = '';

        if ($code_id == 1) {
            $table = 'bakeries_history';
        } else if ($code_id == 2) {
            $table = 'baristas_history';
        } else if ($code_id == 3) {
            $table = 'kitchens_history';
        } else if ($code_id == 4) {
            $table = 'operationals_history';
        } else if ($code_id == 5) {
            $table = 'waiters_history';
        } else if ($code_id == 6) {
            $table = 'cashiers_history';
        }

        $item = DB::table($table)->where('kode_barang', $kode_barang)->where('id', $id)->first();

        if ($item->status != 0) {
            abort(403);
        }

        return view('approval.edit', compact('item', 'division', 'input_by', 'id'));
    }

    public function update(Request $request, $kode_barang, $code_id, $id, $qty, $type)
    {
        $this->guard();
        $request->validate([
            'status' => 'required',
            'description' => 'required',
        ]);

        if ($code_id == 1) {
            $table = 'bakeries_history';
            $main_table = Bakery::class;
        } else if ($code_id == 2) {
            $table = 'baristas_history';
            $main_table = Barista::class;
        } else if ($code_id == 3) {
            $table = 'kitchens_history';
            $main_table = Kitchen::class;
        } else if ($code_id == 4) {
            $table = 'operationals_history';
            $main_table = Operational::class;
        } else if ($code_id == 5) {
            $table = 'waiters_history';
            $main_table = 'waiters';
        } else if ($code_id == 6) {
            $table = 'cashiers_history';
            $main_table = Cashier::class;
        }

        if ($request->status == 1) {
            $item = $main_table::where('kode_barang', $kode_barang)->first();

            if ($type == 'in') {
                DB::table($table)->where('id', $id)->update([
                    'status' => $request->status,
                    'description' => $request->description
                ]);

                $main_table::where('kode_barang', $kode_barang)->update([
                    'in' => $item->in + $qty,
                    'last_stock' => $item->last_stock + $qty
                ]);
            } else {
                if ($qty > $item->last_stock) {
                    return back()->with('error', 'Quantity exceeds available stock!');
                }

                DB::table($table)->where('id', $id)->update([
                    'status' => $request->status,
                    'description' => $request->description
                ]);

                $main_table::where('kode_barang', $kode_barang)->update([
                    'out' => $item->out + $qty,
                    'last_stock' => $item->last_stock - $qty
                ]);

                $last_stock = $item->last_stock - $qty;

                if ($last_stock <= $item->minimum && $item->priority == 'high') {
                    Mail::to(env('MAIL_MAIN'))->send(new LowStockAlert($item));
                }
            }
        }

        return redirect()->route('approvals.index')->with('success', 'Data Updated Successfully');
    }
}
