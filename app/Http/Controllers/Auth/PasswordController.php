<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class PasswordController extends Controller
{
    /**
     * Exibe o formulário de alteração de senha.
     *
     * @return View
     */
    public function show(): View
    {
        return view('auth.change-password');
    }

    /**
     * Atualiza a senha do usuário autenticado.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        // Valida os dados enviados utilizando a bag de erros 'updatePassword'
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        // Atualiza a senha do usuário autenticado, criptografando a nova senha
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Redireciona para a home com uma mensagem de sucesso
        return redirect()->route('home')->with('status', 'password-updated');
    }
}
