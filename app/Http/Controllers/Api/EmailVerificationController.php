<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{
    //// Méthode pour envoyer un email de vérification
    public function sendVerificationEmail(Request $request)
    { 
        //// Vérifie si l'utilisateur a déjà vérifié son email
        if ($request->user()->hasVerifiedEmail()) {
            return [
                'message' => 'Already Verified'
            ];
        }
        // Envoie une notification de vérification d'email à l'utilisateur
        $request->user()->sendEmailVerificationNotification();

        return ['status' => 'verification-link-sent'];
    }
    // Méthode pour vérifier l'email
    public function verify(EmailVerificationRequest $request)
    {   // Vérifie si l'email de l'utilisateur est déjà vérifié
        if ($request->user()->hasVerifiedEmail()) {
            return [
                'message' => 'Email already verified'
            ];
        }
        // Marque l'email de l'utilisateur comme vérifié
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return [
            'message'=>'Email has been verified'
        ];
    }
}
