<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        // 1. Validació dels camps del formulari
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Intentem obrir la sessió comprovant MariaDB
        if (Auth::attempt($credentials)) {
            // Regenerem la sessió per motius de seguretat industrial
            $request->session()->regenerate();

            // Redirigim directament al carret per incitar a comprar!
            return redirect()->route('orders.showOrderDetails.current');
        }

        // 3. Si les credencials fallen, llancem l'error cap al formulari
        throw ValidationException::withMessages([
            'email' => 'El correu electrònic o la contrasenya no són correctes.',
        ]);
    }

}
