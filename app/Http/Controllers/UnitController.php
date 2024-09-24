<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitController extends Controller
{
    protected function guard()
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

        $title = 'Delete Unit!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        return view('units.index');
    }

    public function create()
    {
        $this->guard();
        return view('units.create');
    }

    public function insert(Request $request)
    {
        $this->guard();
        $validated = $request->validate([
            'code' => 'required|unique:units,code',
            'name' => 'required'
        ]);

        $code = strtolower($request->code);

        Unit::create([
            'code' => $code,
            'name' => $request->name
        ]);

        return redirect()->route('units.index')->with('success', 'Unit Created Successfully');
    }

    public function edit(Unit $unit)
    {
        $this->guard();
        return view('units.edit', compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        $this->guard();
        $validated = $request->validate([
            'code' => 'required|unique:units,code,' . $unit->id,
            'name' => 'required'
        ]);

        $code = strtolower($request->code);

        $unit->update([
            'code' => $code,
            'name' => $request->name
        ]);

        return redirect()->route('units.index')->with('success', 'Unit Updated Successfully');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();

        return redirect()->route('units.index')->with('success', 'Unit Deleted Successfully');
    }
}
