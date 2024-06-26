<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email:dns',
            'role' => 'required',
        ]);
    
        $password = substr($request->email, 0, 3) . substr($request->name, 0, 3);
        try {
            //code...
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'password' => Hash::make($password) // Encrypt the password
            ]);
        return redirect()->route('users.home')->with('success', 'Berhasil menambahkan Pengguna!');

        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('users.home')->with('danger', 'Gagal menambahkan data pengguna!');

        }
        

    }

    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $users = user::find($id);
        return view('users.edit', compact('users'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required',
            'role' => 'required|',
        ]);
    
        user::where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);
    
        return redirect()->route('users.home')->with('success', 'Berhasil mengubah data!');
    }
    
    public function destroy($id)
    {
        user::where('id', $id)->delete();
        return redirect()->back()->with('deleted', 'Berhasil menghapus data!');
    }

    public function loginAuth(Request $request)
    {
    $request->validate([
        'email'    => 'required|email:dns',
        'password' => 'required',
    ]);

    $user = $request->only(['email', 'password']);
    if (Auth::attempt($user)) {
        return redirect()->route('home.page');
    } else {
        return redirect()->back()->with('failed', 'Proses login gagal, silahkan coba kembali dengan data yang benar!');
    }
}

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('logout', 'Anda telah logout!');
    }
}