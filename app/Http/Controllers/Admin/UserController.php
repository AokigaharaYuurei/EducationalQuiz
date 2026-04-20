<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');
    
    $users = User::when($search, function ($query, $search) {
        return $query->where('name', 'like', "%{$search}%")
                     ->orWhere('middlename', 'like', "%{$search}%")
                     ->orWhere('lastname', 'like', "%{$search}%");
    })->get(); 
    
    return view('admin.users', compact('users', 'search'));
}

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Вы не можете удалить самого себя.');
        }
        $user->delete();
        return back()->with('success', 'Пользователь удалён.');
    }

    public function toggleRole(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Вы не можете изменить свою роль.');
        }

        $newRole = $user->role === 'admin' ? 'user' : 'admin';
        $user->update(['role' => $newRole]);

        $message = $newRole === 'admin' 
            ? 'Пользователь назначен администратором.' 
            : 'Роль администратора отозвана.';

        return back()->with('success', $message);
    }
}