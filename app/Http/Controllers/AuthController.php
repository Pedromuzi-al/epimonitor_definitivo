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
    // Mostrar página introdutoria de login
    public function showLoginPage()
    {
        return view('auth.login');
    }

    // Mostrar página de registro
    public function showRegisterPage()
    {
        return view('auth.register');
    }

    // Mostrar orientacao para redefinição de senha
    public function showForgotPasswordPage()
    {
        return view('auth.forgot-password');
    }

    // Enviar e-mail com link de redefinição de senha
    public function sendPasswordResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'O e-mail é obrigatório',
            'email.email' => 'Digite um e-mail válido',
        ]);

        try {
            $status = Password::sendResetLink($request->only('email'));
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Não foi possível enviar o e-mail agora. Verifique a configuração de envio do sistema.']);
        }

        if ($status === Password::RESET_LINK_SENT) {
            if (config('mail.default') === 'log') {
                return back()->with('success', 'Link gerado em modo local. Consulte o arquivo storage/logs/laravel.log para copiar o link de redefinição.');
            }

            return back()->with('success', 'Enviamos um link de redefinição para o e-mail informado.');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Não encontramos uma conta com esse e-mail.']);
    }

    // Mostrar formulário para cadastrar nova senha
    public function showResetPasswordPage(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    // Atualizar senha usando token enviado por e-mail
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'email.required' => 'O e-mail é obrigatório',
            'email.email' => 'Digite um e-mail válido',
            'password.required' => 'A nova senha é obrigatória',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres',
            'password.confirmed' => 'As senhas não conferem',
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
            return redirect()->route('auth.login')->with('success', 'Senha redefinida com sucesso. Faça login com a nova senha.');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'O link de redefinição é inválido ou expirou. Solicite um novo link.']);
    }

    // Processar login
    public function login(Request $request)
    {
        $credenciais = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'O e-mail é obrigatório',
            'email.email' => 'Digite um e-mail válido',
            'password.required' => 'A senha é obrigatória',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres',
        ]);

        if (Auth::attempt($credenciais)) {
            $request->session()->regenerate();

            $usuario = Auth::user();

            if ($usuario->user_type === 'doctor') {
                return redirect()->route('home')->with('success', 'Bem-vindo, Médico!');
            }

            return redirect()->route('home')->with('success', 'Bem-vindo!');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'E-mail ou senha inválidos']);
    }

    // Processar registro
    public function register(Request $request)
    {
        $dadosValidados = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'O nome é obrigatório',
            'name.max' => 'O nome não pode exceder 255 caracteres',
            'email.required' => 'O e-mail é obrigatório',
            'email.email' => 'Digite um e-mail válido',
            'email.unique' => 'Este e-mail já está registrado',
            'password.required' => 'A senha é obrigatória',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres',
            'password.confirmed' => 'As senhas não conferem',
        ]);

        try {
            $usuario = User::create([
                'name' => $dadosValidados['name'],
                'email' => $dadosValidados['email'],
                'password' => Hash::make($dadosValidados['password']),
                'user_type' => 'person',
            ]);

            Auth::login($usuario);
            $request->session()->regenerate();

            return redirect()->route('home')->with('success', 'Bem-vindo! Sua conta foi criada com sucesso.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao criar conta. Tente novamente.');
        }
    }

    // Mostrar perfil do usuário
    public function showProfile()
    {
        $usuario = Auth::user();
        return view('user.profile', ['user' => $usuario]);
    }

    // Mostrar formulário de edição de perfil
    public function editProfile()
    {
        $usuario = Auth::user();
        return view('user.edit-profile', ['user' => $usuario]);
    }

    // Exibir foto de perfil salva no disco público
    public function profilePhoto(User $user)
    {
        if (empty($user->profile_photo_path) || !Storage::disk('public')->exists($user->profile_photo_path)) {
            abort(404);
        }

        return response()->file($this->caminhoFotoPerfil($user));
    }

    // Atualizar perfil do usuário
    public function updateProfile(Request $request)
    {
        /** @var User|null $usuario */
        $usuario = Auth::user();
        if (!$usuario) {
            return redirect()->route('auth.login')->with('error', 'Sua sessão expirou. Faça login novamente.');
        }

        $dadosValidados = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'password' => 'nullable|string|min:6|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'remove_profile_photo' => 'nullable|boolean',
        ], [
            'name.required' => 'O nome é obrigatório',
            'name.max' => 'O nome não pode exceder 255 caracteres',
            'email.required' => 'O e-mail é obrigatório',
            'email.email' => 'Digite um e-mail válido',
            'email.unique' => 'Este e-mail já está registrado',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres',
            'password.confirmed' => 'As senhas não conferem',
            'profile_photo.image' => 'A foto de perfil deve ser uma imagem válida.',
            'profile_photo.mimes' => 'A foto deve estar em JPG, PNG ou WEBP.',
            'profile_photo.max' => 'A foto de perfil deve ter no máximo 5MB.',
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
