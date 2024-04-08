<?php


namespace App\Customs\Services;

use App\Models\EmailVerificationToken;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use App\Notifications\EmailVerificationNotification;

class EmailVerificationService
{

    /**
     * Send a verification link to the user
     *
     * @param object $user
     * @return void
     */
    public function sendVerificationLink(object $user): void
    {
        Notification::send($user, new EmailVerificationNotification($this->generateVerificationLink($user->email)));
        // dd($user);
    }

    /**
     * Generate a verification link
     *
     * @param string $email
     * @return string
     */
    public function generateVerificationLink(string $email): string
    {
        $checkIfTokenExist = EmailVerificationToken::where('email', $email)->first();
        if ($checkIfTokenExist) {
            $checkIfTokenExist->delete();
        }

        $token = Str::uuid();

        $url = config('app.url') . '?token=' . $token . '&email=' . $email;

        $savedToken = EmailVerificationToken::create([
            'email' => $email,
            'token' => $token,
            'expired_at' => now()->addMinutes(60)
        ]);

        if (!$savedToken) {
            return '';
        }

        return $url;
    }




}

