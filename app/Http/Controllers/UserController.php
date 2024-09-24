<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;


class UserController extends Controller
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

        $title = 'Delete User!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        $roles = Role::all();

        return view('users.index', compact('roles'));
    }

    public function create()
    {
        $this->guard();

        $roles = Role::all();

        return view('users.create', compact('roles'));
    }

    public function insert(Request $request)
    {
        $this->guard();

        $request->validate([
            'name'      => 'required|string',
            'email'     => 'required|email|unique:users,email',
            'role'   => 'required|integer'
        ], $message = [
            'role.required' => 'The role field is required.'
        ]);


        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'role_id'   => $request->role,
            'password'  => Hash::make('password')
        ]);

        return redirect()->route('users.index')->with('success', 'User Created Successfully');
    }

    public function edit(User $user)
    {
        $this->guard();

        $roles = Role::all();

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $this->guard();

        $validated = $request->validate([
            'name'      => 'required|string',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'role_id'      => 'required|integer'
        ], $message = [
            'role_id.required' => 'The role field is required.'
        ]);

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User Updated Successfully');
    }

    // public function destroy(Request $request, User $user)
    // {
    //     $this->guard();

    //     $user->delete();

    //     return redirect()->route('users.index')->with('success', 'User Deleted Successfully');
    // }

    public function reset(User $user)
    {
        $this->guard();

        $user->update([
            'password' => Hash::make('password')
        ]);

        return redirect()->route('users.index')->with('success', 'User password reseted Successfully');
    }
}
