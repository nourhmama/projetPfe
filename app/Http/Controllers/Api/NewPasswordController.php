<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException; // Import de ValidationException
use Illuminate\Validation\Rules\Password as RulesPassword;

class NewPasswordController extends Controller
{

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        // Vérifier si l'e-mail existe dans la base de données
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return response()->json([
                'message' => 'Cet e-mail n\'est pas enregistré.'
            ], 404);
        }

        // Envoyer le lien de réinitialisation de mot de passe seulement si l'e-mail existe
        $status = Password::sendResetLink(
            $request->only('email')
        );
    
        if ($status == Password::RESET_LINK_SENT) {
            return response()->json([
                'status' => 'success',
                'message' => 'Un lien de réinitialisation de mot de passe a été envoyé à votre adresse e-mail.'
            ]);
        }
    
        return response()->json([
            'message' => 'Une erreur s\'est produite lors de l\'envoi du lien de réinitialisation de mot de passe.'
        ], 500);
    }

    // Fonction pour vérifier si un e-mail existe
    public function checkEmailExists(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Vérifier si l'e-mail existe dans la base de données
        $user = User::where('email', $request->email)->first();

        if ($user) {
            return response()->json([
                'exists' => true
            ]);
        } else {
            return response()->json([
                'exists' => false
            ]);
        }
    }

    // Fonction pour réinitialiser le mot de passe
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', RulesPassword::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response([
                'message'=> 'Password reset successfully'
            ]);
        }

        return response([
            'message'=> __($status)
        ], 500);

    }
}
