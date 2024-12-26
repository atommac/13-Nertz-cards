<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
            'recaptcha_token' => ['required', 'string'],
        ])->after(function ($validator) use ($input) {
            if (!$this->validateReCaptcha($input['recaptcha_token'] ?? '')) {
                $validator->errors()->add(
                    'recaptcha',
                    'Failed to validate reCAPTCHA. Please try again.'
                );
            }
        })->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }

    /**
     * Validate the reCAPTCHA token.
     */
    protected function validateReCaptcha(string $token): bool
    {
        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $token,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['success'] && $data['score'] >= 0.5;
            }
        } catch (\Exception $e) {
            \Log::error('reCAPTCHA validation error: ' . $e->getMessage());
        }

        return false;
    }
}
