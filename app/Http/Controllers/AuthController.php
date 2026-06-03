<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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

