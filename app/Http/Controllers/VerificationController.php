<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\EmailVerificationToken;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verifyEmail(Request $request)
    {
        dd($request);
        $token = $request->query('token');
        $email = $request->query('email');

        // Retrieve the user by email
        $user = User::where('email', $email)->first();
        $userToken = EmailVerificationToken::where('email', $email)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Check if the user's email verification token matches
        if ($userToken->token !== $token && $userToken->expired_at > now()) {
            return response()->json(['error' => 'Invalid token'], 400);
        }

        if ($user->email_verified_at) {
            return response()->json(['message' => 'Email already verified'], 200);
        }

        // Update the user's email_verified_at field
        $user->email_verified_at = now();
        $user->save();

        return response()->json(['message' => 'Email verified successfully'], 200);
    }
}
