<?php

// app/Actions/Fortify/LoginResponse.php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        // Redirect to your custom page after login
        return redirect()->intended('/scoreboard');
    }
}