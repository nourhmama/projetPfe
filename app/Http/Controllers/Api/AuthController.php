<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'entreprise' => 'required|string|max:255',
                'phone' => 'required|numeric|digits_between:8,8',
                'password' => ['required', 'confirmed', Password::defaults()],
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

       return response()->json(
           [
               'message'=>'Logged Welcome',
               'data'=> [
                   'user'=> $request->user(),
                   'token'=> $token->plainTextToken
               ]
           ]
        );
    }

    public function logout(Request $request)
    {

        $request->user()->tokens()->delete();

        return response()->json(
            [
                'message' => 'Logged out'
            ]
        );

    }

}
