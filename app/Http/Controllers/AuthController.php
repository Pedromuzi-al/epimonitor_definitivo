<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{
    // Mostrar pagina introdutoria de login
    public function showLoginPage()
    {
        return view('auth.login');
    }

    // Mostrar pagina de registro
    public function showRegisterPage()
    {
        return view('auth.register');
    }

    // Mostrar orientacao para redefinicao de senha
    public function showForgotPasswordPage()
    {
        return view('auth.forgot-password');
    }

    // Enviar email com link de redefinicao de senha
    public function sendPasswordResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'O email e obrigatorio',
            'email.email' => 'Digite um email valido',
        ]);

        try {
            $status = Password::sendResetLink($request->only('email'));
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Nao foi possivel enviar o email agora. Verifique a configuracao de envio do sistema.']);
        }

        if ($status === Password::RESET_LINK_SENT) {
            if (config('mail.default') === 'log') {
                return back()->with('success', 'Link gerado em modo local. Consulte o arquivo storage/logs/laravel.log para copiar o link de redefinicao.');
            }

            return back()->with('success', 'Enviamos um link de redefinicao para o email informado.');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Nao encontramos uma conta com esse email.']);
    }

    // Mostrar formulario para cadastrar nova senha
    public function showResetPasswordPage(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    // Atualizar senha usando token enviado por email
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'email.required' => 'O email e obrigatorio',
            'email.email' => 'Digite um email valido',
            'password.required' => 'A nova senha e obrigatoria',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres',
            'password.confirmed' => 'As senhas nao conferem',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('auth.login')->with('success', 'Senha redefinida com sucesso. Faca login com a nova senha.');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'O link de redefinicao e invalido ou expirou. Solicite um novo link.']);
    }

    // Processar login
    public function login(Request $request)
    {
        $credenciais = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'O email e obrigatorio',
            'email.email' => 'Digite um email valido',
            'password.required' => 'A senha e obrigatoria',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres',
        ]);

        if (Auth::attempt($credenciais)) {
            $request->session()->regenerate();

            $usuario = Auth::user();

            if ($usuario->user_type === 'doctor') {
                return redirect()->route('home')->with('success', 'Bem-vindo, Medico!');
            }

            return redirect()->route('home')->with('success', 'Bem-vindo!');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Email ou senha invalidos']);
    }

    // Processar registro
    public function register(Request $request)
    {
        $dadosValidados = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'user_type' => 'required|in:doctor,person',
        ], [
            'name.required' => 'O nome e obrigatorio',
            'name.max' => 'O nome nao pode exceder 255 caracteres',
            'email.required' => 'O email e obrigatorio',
            'email.email' => 'Digite um email valido',
            'email.unique' => 'Este email ja esta registrado',
            'password.required' => 'A senha e obrigatoria',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres',
            'password.confirmed' => 'As senhas nao conferem',
            'user_type.required' => 'Selecione o tipo de usuario',
            'user_type.in' => 'Tipo de usuario invalido',
        ]);

        try {
            $usuario = User::create([
                'name' => $dadosValidados['name'],
                'email' => $dadosValidados['email'],
                'password' => Hash::make($dadosValidados['password']),
                'user_type' => $dadosValidados['user_type'],
            ]);

            Auth::login($usuario);
            $request->session()->regenerate();

            $mensagem = $dadosValidados['user_type'] === 'doctor'
                ? 'Bem-vindo, Medico! Sua conta foi criada com sucesso.'
                : 'Bem-vindo! Sua conta foi criada com sucesso.';

            return redirect()->route('home')->with('success', $mensagem);
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao criar conta. Tente novamente.');
        }
    }

    // Mostrar perfil do usuario
    public function showProfile()
    {
        $usuario = Auth::user();
        return view('user.profile', ['user' => $usuario]);
    }

    // Mostrar formulario de edicao de perfil
    public function editProfile()
    {
        $usuario = Auth::user();
        return view('user.edit-profile', ['user' => $usuario]);
    }

    // Exibir foto de perfil salva no disco publico
    public function profilePhoto(User $user)
    {
        if (empty($user->profile_photo_path) || !Storage::disk('public')->exists($user->profile_photo_path)) {
            abort(404);
        }

        return response()->file($this->caminhoFotoPerfil($user));
    }

    // Atualizar perfil do usuario
    public function updateProfile(Request $request)
    {
        /** @var User|null $usuario */
        $usuario = Auth::user();
        if (!$usuario) {
            return redirect()->route('login')->with('error', 'Sua sessao expirou. Faca login novamente.');
        }

        $dadosValidados = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'password' => 'nullable|string|min:6|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'remove_profile_photo' => 'nullable|boolean',
        ], [
            'name.required' => 'O nome e obrigatorio',
            'name.max' => 'O nome nao pode exceder 255 caracteres',
            'email.required' => 'O email e obrigatorio',
            'email.email' => 'Digite um email valido',
            'email.unique' => 'Este email ja esta registrado',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres',
            'password.confirmed' => 'As senhas nao conferem',
            'profile_photo.image' => 'A foto de perfil deve ser uma imagem valida.',
            'profile_photo.mimes' => 'A foto deve estar em JPG, PNG ou WEBP.',
            'profile_photo.max' => 'A foto de perfil deve ter no maximo 5MB.',
        ]);

        try {
            $usuario->name = $dadosValidados['name'];
            $usuario->email = $dadosValidados['email'];

            if (!empty($dadosValidados['password'])) {
                $usuario->password = Hash::make($dadosValidados['password']);
            }

            $removerFoto = (bool) $request->boolean('remove_profile_photo');
            if ($removerFoto && !empty($usuario->profile_photo_path)) {
                Storage::disk('public')->delete($usuario->profile_photo_path);
                $usuario->profile_photo_path = null;
            }

            if ($request->hasFile('profile_photo')) {
                if (!empty($usuario->profile_photo_path)) {
                    Storage::disk('public')->delete($usuario->profile_photo_path);
                }

                Storage::disk('public')->makeDirectory('fotos-perfil');
                $caminhoFoto = $request->file('profile_photo')->store('fotos-perfil', 'public');
                $usuario->profile_photo_path = $caminhoFoto;
            }

            $this->salvarUsuario($usuario);

            return redirect()->route('user.profile')->with('success', 'Perfil atualizado com sucesso!');
        } catch (\Throwable $e) {
            report($e);

            $mensagem = 'Erro ao atualizar perfil. Tente novamente.';
            if (app()->environment('local')) {
                $mensagem .= ' Detalhe: ' . $e->getMessage();
            }

            return back()->withInput()->with('error', $mensagem);
        }
    }

    private function salvarUsuario(User $usuario): void
    {
        $usuario->save();
    }

    private function caminhoFotoPerfil(User $user): string
    {
        return storage_path('app/public/' . $user->profile_photo_path);
    }

    // Processar logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Desconectado com sucesso');
    }
}

