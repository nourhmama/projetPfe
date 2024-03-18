<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'company' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone_number' => 'required|numeric|digits_between:8,8',
                'password' => ['required', 'confirmed', Password::defaults()],
            ], [
                'phone_number.digits_between' => 'Le champ téléphone doit contenir exactement 8 chiffres.',
            ]);

            $user = User::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'company' => $request->input('company'),
                'email' => $request->input('email'),
                'phone_number' => $request->input('phone_number'),
                'password' => Hash::make($request->input('password')),
                'role' => 'user',
            ]);

            event(new Registered($user));

            $token = $user->createToken('authtoken');

            return response()->json([
                'message' => 'User Registered',
                'data' => ['token' => $token->plainTextToken, 'user' => $user]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de l\'enregistrement de l\'utilisateur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        $request->authenticate();
        $token = $request->user()->createToken('authtoken');

        return response()->json([
            'message' => 'Logged Welcome',
            'data' => [
                'user' => $request->user(),
                'token' => $token->plainTextToken
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out'
        ]);
    }
    public function adminLogin(Request $request)
    {
        // Validation des données de la requête
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'admin'])) {
            $admin = Auth::user();
            $token = $admin->createToken('authToken')->plainTextToken;
            return response()->json([
                'user' => $admin, // Renvoyer les données de l'utilisateur
                'token' => $token, // Renvoyer le token
            ]);
        }

        return response()->json(['error' => 'Invalid credentials'], 401);
    }

}
