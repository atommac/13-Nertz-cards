<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Http;

class ForgotPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'recaptcha_token' => ['required', 'string'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$this->validateReCaptcha()) {
                $validator->errors()->add('recaptcha', 'Failed to validate reCAPTCHA.');
            }
        });
    }

    /**
     * Validate the reCAPTCHA token.
     */
    protected function validateReCaptcha(): bool
    {
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $this->input('recaptcha_token'),
            'remoteip' => $this->ip(),
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['success'] && $data['score'] >= 0.5 && $data['action'] === 'forgot_password';
        }

        return false;
    }
} 