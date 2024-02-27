<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
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
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'entreprise' => 'required|string|max:255',
        'phone' => 'required|numeric|digits_between:8,8',
        'password' => ['required', 'confirmed'],
    ], [
        'phone.digits_between' => 'Le champ téléphone doit contenir exactement 8 chiffres.',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'entreprise' => $request->entreprise,
        'phone' => $request->phone,
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
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'entreprise' => 'required|string|max:255',
            'phone' => 'required|numeric|digits_between:8,8',
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
