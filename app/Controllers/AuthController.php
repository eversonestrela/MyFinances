<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Core\Session;

/**
 * Controller para autenticação
 */
class AuthController extends Controller
{
    private AuthService $authService;

    /**
     * Construtor
     */
    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        $this->authService = new AuthService();
    }

    /**
     * Exibe página de login
     */
    public function showLogin(): void
    {
        // Se já estiver autenticado, redirecionar para dashboard
        if ($this->authService->isAuthenticated()) {
            $this->redirect('/dashboard');
            return;
        }

        $this->view('auth/login');
    }

    /**
     * Processa login
     */
    public function login(): void
    {
        $email = $this->request->post('email');
        $senha = $this->request->post('senha');

        if ($this->authService->login($email, $senha)) {
            Session::flash('success', 'Login realizado com sucesso!');
            $this->redirect('/dashboard');
        } else {
            Session::flash('error', 'Email ou senha inválidos');
            $this->redirect('/login');
        }
    }

    /**
     * Processa logout
     */
    public function logout(): void
    {
        $this->authService->logout();
        Session::flash('success', 'Logout realizado com sucesso');
        $this->redirect('/login');
    }

    /**
     * Exibe página de registro
     */
    public function showRegister(): void
    {
        $this->view('auth/register');
    }

    /**
     * Processa registro
     */
    public function register(): void
    {
        $data = $this->request->only(['nome', 'email', 'senha', 'confirmar_senha']);

        // Validar confirmação de senha
        if ($data['senha'] !== $data['confirmar_senha']) {
            Session::flash('error', 'As senhas não conferem');
            $this->redirect('/register');
            return;
        }

        $result = $this->authService->register($data);

        if ($result['success']) {
            Session::flash('success', $result['message']);
            $this->redirect('/login');
        } else {
            Session::flash('error', $result['message']);
            $this->redirect('/register');
        }
    }
}
