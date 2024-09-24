<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
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
        return view('items.index');
    }

    public function edit($kode_barang, $code_id)
    {
        $this->guard();
        $table = '';

        if ($code_id == 1) {
            $table = 'bakeries';
        } else if ($code_id == 2) {
            $table = 'baristas';
        } else if ($code_id == 3) {
            $table = 'kitchens';
        } else if ($code_id == 4) {
            $table = 'operationals';
        } else if ($code_id == 5) {
            $table = 'waiters';
        } else if ($code_id == 6) {
            $table = 'cashiers';
        }

        $item = DB::table($table)->where('kode_barang', $kode_barang)->first();
        $units = Unit::all();

        return view('items.edit', compact('item', 'units'));
    }

    public function update(Request $request, $kode_barang, $code_id)
    {
        $this->guard();
        $request->validate([
            'nama_barang'   => 'required',
            'unit_id'       => 'required',
            'priority'      => 'required',
            'minimum'       => 'required',
            'price'         => 'required',
        ], $message = [
            'nama_barang.required'  => 'The name of item field is required.',
            'unit_id.required'      => 'The unit of item field is required.',
            'tanggal.required'      => 'The date field is required.',
        ]);

        $table = '';

        if ($code_id == 1) {
            $table = 'bakeries';
        } else if ($code_id == 2) {
            $table = 'baristas';
        } else if ($code_id == 3) {
            $table = 'kitchens';
        } else if ($code_id == 4) {
            $table = 'operationals';
        } else if ($code_id == 5) {
            $table = 'waiters';
        } else if ($code_id == 6) {
            $table = 'cashiers';
        }

        DB::table($table)->where('kode_barang', $kode_barang)->update([
            'nama_barang' => $request->nama_barang,
            'unit_id' => $request->unit_id,
            'priority' => $request->priority,
            'minimum' => $request->minimum,
            'price'   => $request->price
        ]);

        $history_table = $table . '_history';

        DB::table($history_table)->where('kode_barang', $kode_barang)->update([
            'nama_barang' => $request->nama_barang,
            'unit_id' => $request->unit_id,
        ]);

        return redirect()->route('items.index')->with('success', 'Item Updated Successfully');
    }
}
