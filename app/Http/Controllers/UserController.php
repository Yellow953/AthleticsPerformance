<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $search = request()->query('search');

        if ($search) {
            $users = User::where('name', 'LIKE', "%{$search}%")->orWhere('email', 'LIKE', "%{$search}%")->paginate(25);
        } else {
            $users = User::paginate(25);
        }

        return view('users.index', compact('users'));
    }

    public function new()
    {
        return view('users.new');
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/users')->with('success', 'User successfully created!');
    }

    public function edit($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect('/users')->with('danger', 'User not found!');
        }

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        $user = User::find($id);

        if (!$user) {
            return redirect('/users')->with('danger', 'User not found!');
        }

        $user->update(
            $request->all()
        );

        return redirect('/users')->with('warning', 'User successfully updated!');
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('danger', 'User not found!');
        }

        $user->delete();

        return redirect()->back()->with('danger', 'User successfully deleted!');
    }

    public function export()
    {

    }
}