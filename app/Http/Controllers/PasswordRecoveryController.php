<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PasswordRecoveryController extends Controller
{
    // Exibe o formulário de recuperação de senha
    public function showRecoveryForm()
    {
        return view('auth.password-recovery');
    }

    // Processa a recuperação e atualização da senha
    public function recoverPassword(Request $request)
    {
        $request->validate([
            'email'          => 'required|email|exists:users,email',
            'recovery_code'  => 'required|string',
            'password'       => 'required|confirmed|min:8'
        ]);

        $user = User::where('email', $request->email)->first();

        // Valida o código de recuperação armazenado no cadastro do usuário
        if ($user->recovery_code !== $request->recovery_code) {
            return back()->withErrors(['recovery_code' => 'Código de recuperação inválido.']);
        }

        // Atualiza a senha (lembre-se de usar Hash para criptografá-la)
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('status', 'Senha atualizada com sucesso!');
    }
}


