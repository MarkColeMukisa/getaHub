<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = \App\Models\User::latest()->get();
        return view('dashboard', compact('users'));
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        // If email omitted, generate a placeholder unique email to satisfy NOT NULL + unique constraint
        $email = $data['email'] ?? ('user-'.now()->timestamp.'-'.Str::random(6).'@placeholder.local');
        // If password omitted, generate a random secure password
        $plainPassword = $data['password'] ?? Str::random(16);

        $user = User::create([
            'name' => $data['name'],
            'email' => $email,
            'password' => Hash::make($plainPassword),
            'contact' => $data['contact'] ?? null,
            'room_number' => $data['room_number'] ?? null,
        ]);

        return redirect()->route('dashboard')->with('status', 'User '.$user->name.' created successfully.');
    }
}
