<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\ResetsUserPasswords;
use Illuminate\Support\Facades\Http;

class PasswordResetLink
{
    /**
     * Validate and send password reset link.
     */
    public function sendResetLink(array $input)
    {
        Validator::make($input, [
            'email' => ['required', 'string', 'email'],
            'recaptcha_token' => ['required', 'string'],
        ])->after(function ($validator) use ($input) {
            if (!$this->validateReCaptcha($input['recaptcha_token'] ?? '')) {
                $validator->errors()->add('recaptcha', 'Failed to validate reCAPTCHA.');
            }
        })->validateWithBag('forgot-password');

        // Send password reset link
        $status = Password::sendResetLink(
            ['email' => $input['email']]
        );

        return $status;
    }

    /**
     * Validate the reCAPTCHA token.
     */
    protected function validateReCaptcha(string $token): bool
    {
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $token,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['success'] && $data['score'] >= 0.5 && $data['action'] === 'forgot_password';
        }

        return false;
    }
} 