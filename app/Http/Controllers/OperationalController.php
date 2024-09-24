<?php

namespace App\Http\Controllers;

use App\Exports\DataExport;
use App\Models\Operational;
use Illuminate\Http\Request;
use App\Mail\LowStockAlert;
use App\Models\Code;
use App\Models\Unit;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;

class OperationalController extends Controller
{
    protected function guard()
    {
        $user = Auth::user();

        if ($user && $user->role_id === 1 || $user->role_id === 2 || $user->role_id === 4) {
            return $this;
        }

        abort(403);
    }

    public function index()
    {
        $this->guard();

        $title = 'Delete Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        return view('operational.index');
    }

    public function create()
    {
        if (Auth::user()->role_id != 1) {
            abort(403);
        }

        $units = Unit::all();
        return view('operational.create', compact('units'));
    }

    public function insert(Request $request)
    {
        $this->guard();

        $request->validate([
            'nama_barang'   => 'required',
            'initial_stock' => 'required',
            'unit_id'       => 'required',
            'tanggal'       => 'required',
            'priority'      => 'required',
            'minimum'       => 'required',
            'price'         => 'required',
        ], $message = [
            'nama_barang.required'  => 'The name of item field is required.',
            'unit_id.required'      => 'The unit of item field is required.',
            'tanggal.required'      => 'The date field is required.',
        ]);

        $codes = Code::where('role_id', 4)->first();
        $lastCode = Operational::orderBy('id', 'desc')->first();
        $newId = $lastCode ? $lastCode->id + 1 : 1;
        $formattedId = str_pad($newId, 3, '0', STR_PAD_LEFT);
        $formattedCode = $codes->code . '-' . $formattedId;

        $items = Operational::create([
            'user_id' => Auth::id(),
            'code_id' => $codes->id,
            'unit_id' => $request->unit_id,
            'type'    => 'in',
            'kode_barang' => $formattedCode,
            'nama_barang' => $request->nama_barang,
            'initial_stock' => $request->initial_stock,
            'last_stock' => $request->initial_stock,
            'qty'  => 0,
            'in' => $request->initial_stock,
            'out' => 0,
            'tanggal' => $request->tanggal,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'priority'   => $request->priority,
            'minimum'    => $request->minimum,
            'price' => $request->price,
        ]);

        DB::table('operationals_history')->insert([
            'user_id' => Auth::id(),
            'code_id' => $codes->id,
            'unit_id' => $request->unit_id,
            'type'    => 'in',
            'kode_barang' => $formattedCode,
            'nama_barang' => $request->nama_barang,
            'initial_stock' => $request->initial_stock,
            'last_stock' => $request->initial_stock,
            'qty'  => $request->initial_stock,
            'in' => $request->initial_stock,
            'out' => 0,
            'tanggal' => $request->tanggal,
            'status' => 1,
            'description' => 'initial data',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'price' => $request->price,
        ]);


        // KIRIM EMAIL
        if ($request->initial_stock < $request->minimum && $request->priority == 'high') {
            Mail::to(env('MAIL_MAIN'))->send(new LowStockAlert($items));
        }

        return redirect()->route('operationals.index')->with('success', 'Data Created Successfully');
    }

    public function edit(Operational $operational)
    {
        $this->guard();

        $item = $operational;
        $units = Unit::all();

        return view('operational.edit', compact('item', 'units'));
    }

    public function incresase(Request $request, Operational $operational)
    {
        $this->guard();

        $request->validate([
            'tanggal'       => 'required',
            'qty'           => 'required',
        ], $message = [
            'tanggal.required'      => 'The date field is required.',
            'qty.required'          => 'The quantity field is required.',
        ]);

        if (Auth::user()->role_id == 1) {
            $status = 1;
            $desc = 'approved';
        } else {
            $status = 0;
            $desc = 'pending';
        }

        DB::table('operationals_history')->insert([
            'user_id' => Auth::id(),
            'code_id' => $operational->code_id,
            'unit_id' => $operational->unit_id,
            'type'    => 'in',
            'kode_barang' => $operational->kode_barang,
            'nama_barang' => $operational->nama_barang,
            'initial_stock' => 0,
            'last_stock' => 0,
            'qty'  => $request->qty,
            'in' => 0,
            'out' => 0,
            'tanggal' => $request->tanggal,
            'status' => $status,
            'description' => $desc,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'price' => $operational->price,
        ]);

        if ($status == 1) {
            $operational->update([
                'user_id' => Auth::id(),
                'type'    => 'in',
                'last_stock' => $operational->last_stock + $request->qty,
                'in' => $operational->in + $request->qty,
                'tanggal' => $request->tanggal,
            ]);
        }

        return redirect()->route('operationals.index')->with('success', 'Stock Data Updated Successfully');
    }

    public function decrease(Request $request, Operational $operational)
    {
        $this->guard();

        $request->validate([
            'tanggal_decreased'       => 'required',
            'qty_decreased'           => 'required',
        ], $message = [
            'tanggal_decreased.required'      => 'The date field is required.',
            'qty_decreased.required'          => 'The quantity field is required.',
        ]);

        if ($request->qty_decreased > $operational->last_stock) {
            return Redirect::back()->withErrors([
                'qty_decreased' => 'Requested quantity exceeds available stock.',
            ])->withInput();
        }

        $last_stock = $operational->last_stock - $request->qty_decreased;

        if (Auth::user()->role_id == 1) {
            $status = 1;
            $desc = 'approved';
        } else {
            $status = 0;
            $desc = 'pending';
        }

        DB::table('operationals_history')->insert([
            'user_id' => Auth::id(),
            'code_id' => $operational->code_id,
            'unit_id' => $operational->unit_id,
            'type'    => 'out',
            'kode_barang' => $operational->kode_barang,
            'nama_barang' => $operational->nama_barang,
            'initial_stock' => 0,
            'last_stock' => 0,
            'qty'  => $request->qty_decreased,
            'in' => 0,
            'out' => 0,
            'tanggal' => $request->tanggal_decreased,
            'status' => $status,
            'description' => $desc,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        if ($status == 1) {
            $operational->update([
                'user_id' => Auth::id(),
                'type'    => 'in',
                'last_stock' => $last_stock,
                'out' => $operational->out + $request->qty_decreased,
                'tanggal' => $request->tanggal_decreased,
            ]);

            // KIRIM EMAIL
            if ($last_stock < $operational->minimum && $operational->priority == 'high') {
                Mail::to(env('MAIL_MAIN'))->send(new LowStockAlert($operational));
            }
        }

        return redirect()->route('operationals.index')->with('success', 'Stock Data Updated Successfully');
    }

    public function destroy(Operational $operational)
    {
        $this->guard();

        $operational->delete();
        DB::table('operationals_history')->where('kode_barang', $operational->kode_barang)->delete();

        return redirect()->route('operationals.index')->with('success', 'Data Deleted Successfully');
    }

    public function history()
    {
        $this->guard();

        return view('operational.history');
    }

    public function report()
    {
        $this->guard();

        $today = Carbon::now()->format('Y-m-d');
        return view('operational.report', compact('today'));
    }

    public function pdf(Request $request)
    {
        $this->guard();

        $request->validate([
            'fromDate' => 'required',
            'toDate' => 'required',
        ]);

        if ($request->fromDate > $request->toDate) {
            return Redirect::back()->withErrors([
                'toDate' => 'The to date cannot be earlier than the from date.',
            ])->withInput();
        }

        $codes = Code::where('role_id', 4)->first();
        $filename = $codes->code . '_' . $request->fromDate . '_' . $request->toDate . '.pdf';

        $title = '';

        if ($codes->role_id == 4) {
            $title = 'operational';
        }

        $data = DB::table('operationals_history')
            ->join('units', 'operationals_history.unit_id', '=', 'units.id')
            ->select(
                'operationals_history.kode_barang',
                'operationals_history.nama_barang',
                DB::raw('SUM(CASE WHEN operationals_history.type = "in" THEN operationals_history.qty ELSE 0 END) as total_in'),
                DB::raw('SUM(CASE WHEN operationals_history.type = "out" THEN operationals_history.qty ELSE 0 END) as total_out'),
                DB::raw('SUM(operationals_history.initial_stock) as initial_stock'),
                'operationals_history.unit_id',
                'units.code'
            )
            ->whereBetween('operationals_history.tanggal', [$request->fromDate, $request->toDate])
            ->groupBy('operationals_history.kode_barang', 'operationals_history.nama_barang', 'operationals_history.unit_id', 'units.code')
            ->get()
            ->map(function ($item) {
                $item->stock_akhir = $item->total_in - $item->total_out;
                return $item;
            });

        if ($data->count() == 0) {
            return back()->with('error', 'Data not found');
        }

        $pdf = PDF::loadView('pdf', [
            'title'     => 'Laporan Inventaris Divisi ' . $title,
            'code'      => $codes->code,
            'fromDate'  => $request->fromDate,
            'toDate'    => $request->toDate,
            'data'      => $data,
        ]);

        return $pdf->download($filename);
    }
}
