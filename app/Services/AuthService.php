<?php

namespace App\Services;

use App\Models\Usuario;
use App\Repositories\UsuarioRepository;
use App\Core\Session;

/**
 * Service para lógica de autenticação
 */
class AuthService
{
    private UsuarioRepository $usuarioRepository;

    /**
     * Construtor
     */
    public function __construct()
    {
        $this->usuarioRepository = new UsuarioRepository();
    }

    /**
     * Realiza login do usuário
     * 
     * @param string $email
     * @param string $senha
     * @return bool
     */
    public function login(string $email, string $senha): bool
    {
        $usuario = $this->usuarioRepository->findByEmail($email);

        if (!$usuario) {
            return false;
        }

        // Verificar senha
        if (!password_verify($senha, $usuario->senha)) {
            return false;
        }

        // Criar sessão
        Session::set('usuario_id', $usuario->id);
        Session::set('usuario_nome', $usuario->nome);
        Session::set('usuario_email', $usuario->email);
        Session::set('usuario_foto', $usuario->foto_perfil);
        
        // Regenerar ID da sessão para segurança
        Session::regenerate();

        return true;
    }

    /**
     * Realiza logout
     * 
     * @return void
     */
    public function logout(): void
    {
        Session::destroy();
    }

    /**
     * Verifica se usuário está autenticado
     * 
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        return Session::has('usuario_id');
    }

    /**
     * Obtém ID do usuário autenticado
     * 
     * @return int|null
     */
    public function getUsuarioId(): ?int
    {
        return Session::get('usuario_id');
    }

    /**
     * Obtém usuário autenticado completo
     * 
     * @return Usuario|null
     */
    public function getUsuario(): ?Usuario
    {
        $id = $this->getUsuarioId();
        
        if (!$id) {
            return null;
        }

        return $this->usuarioRepository->findById($id);
    }

    /**
     * Registra um novo usuário
     * 
     * @param array $data
     * @return array ['success' => bool, 'message' => string, 'usuario' => Usuario|null]
     */
    public function register(array $data): array
    {
        // Validar dados
        if (empty($data['nome']) || empty($data['email']) || empty($data['senha'])) {
            return ['success' => false, 'message' => 'Todos os campos são obrigatórios', 'usuario' => null];
        }

        // Verificar se email já existe
        if ($this->usuarioRepository->emailExists($data['email'])) {
            return ['success' => false, 'message' => 'Email já cadastrado', 'usuario' => null];
        }

        // Validar email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Email inválido', 'usuario' => null];
        }

        // Validar senha
        if (strlen($data['senha']) < 6) {
            return ['success' => false, 'message' => 'Senha deve ter no mínimo 6 caracteres', 'usuario' => null];
        }

        // Criar usuário
        $usuario = new Usuario();
        $usuario->nome = $data['nome'];
        $usuario->email = $data['email'];
        $usuario->senha = password_hash($data['senha'], PASSWORD_DEFAULT);
        $usuario->foto_perfil = null;


        if ($this->usuarioRepository->create($usuario)) {
            // Cria categorias padrão para o novo usuário
            $this->criarCategoriasPadrao($usuario->id);
            return ['success' => true, 'message' => 'Usuário cadastrado com sucesso', 'usuario' => $usuario];
        }
        return ['success' => false, 'message' => 'Erro ao cadastrar usuário', 'usuario' => null];
    }

    /**
     * Cria categorias padrão para um novo usuário
     */
    private function criarCategoriasPadrao(int $usuarioId): void
    {
        $categorias = [
            ['Cartão de Crédito', 'bi-credit-card-fill',  '#e74c3c'],
            ['Alimentação',       'bi-basket-fill',        '#e67e22'],
            ['Transporte',        'bi-car-front-fill',     '#3498db'],
            ['Saúde',             'bi-heart-pulse-fill',   '#e91e63'],
            ['Moradia',           'bi-house-fill',         '#9b59b6'],
            ['Internet/Telefone', 'bi-wifi',               '#1abc9c'],
            ['Lazer',             'bi-controller',         '#f39c12'],
            ['Investimentos',     'bi-graph-up-arrow',     '#27ae60'],
            ['Empréstimos',       'bi-bank',               '#c0392b'],
            ['Outros',            'bi-three-dots',         '#95a5a6'],
        ];
        $db = \App\Core\Database::getConnection();
        $stmt = $db->prepare("INSERT INTO categorias (usuario_id, nome, icone, cor) VALUES (?, ?, ?, ?)");
        foreach ($categorias as $cat) {
            $stmt->execute([$usuarioId, $cat[0], $cat[1], $cat[2]]);
        }
    }

    /**
     * Middleware para proteger rotas
     * 
     * @return void
     */
    public function requireAuth(): void
    {
        if (!$this->isAuthenticated()) {
            header('Location: /login');
            exit;
        }
    }
}
