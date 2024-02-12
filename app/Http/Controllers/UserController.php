<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //index
    public function index(Request $request)
    {
        $users = DB::table('users')
            ->when($request->input('name'), function ($query, $name) {
                return $query->where('name', 'like', '%' . $name . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('pages.users.index', compact('users'));
    }
    public function create()
    {
        return view('pages.users.create');
    }
    public function store(Request $request)
    {
        // validate the request...
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'roles' => 'required|in:admin,staff,user',
        ]);

        // store the request...
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->roles = $request->roles;
        $user->save();

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function show($id)
    {
        return view('pages.users.show');
    }
    public function edit($id)
    {
        $user = \App\Models\User::findOrFail($id);
        return view('pages.users.edit', compact('user'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'roles' => 'required|in:admin,staff,user',
        ]);

        // update the request...
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->roles = $request->roles;
        $user->save();

        //if password is not empty
        if ($request->password) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }
    public function destroy(Request $request, $id)
    {
        // delete the request...
        $user = User::find($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
