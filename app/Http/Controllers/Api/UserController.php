<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Lire tous les utilisateurs
    public function index()
    {
        $users = User::all();
        return response()->json(['users' => $users]);
    }

    // Lire un utilisateur spécifique
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['user' => $user]);
    }

    // Créer un utilisateur
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|numeric|digits_between:8,8',
            'password' => ['required', 'confirmed'],
        ], [
            'phone_number.digits_between' => 'Le champ téléphone doit contenir exactement 8 chiffres.',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'company' => $request->company,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'User created', 'user' => $user]);
    }

    // Mettre à jour un utilisateur
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Valider les données mises à jour
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone_number' => 'required|numeric|digits_between:8,8',
        ]);
        
        // Mettre à jour l'utilisateur
        $user->update($request->all());

        return response()->json(['message' => 'User updated', 'user' => $user]);
    }

    // Supprimer un utilisateur
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }
}