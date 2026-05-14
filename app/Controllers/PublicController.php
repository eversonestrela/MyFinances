<?php

namespace App\Controllers;

use App\Core\Env;
use App\Core\Session;

/**
 * Controller para páginas públicas (landing page, sitemap, etc.)
 */
class PublicController extends Controller
{
    private string $appUrl;

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        $this->appUrl = Env::get('APP_URL', 'http://localhost:8000');
    }

    /**
     * Landing page pública — raiz do site
     */
    public function landing(): void
    {
        Session::start();

        // Se já está logado, vai pro dashboard
        if (Session::has('usuario_id')) {
            $this->response->redirect('/dashboard');
            return;
        }

        // Renderiza a view da landing page diretamente (tem layout próprio)
        require BASE_PATH . '/views/landing/index.php';
        exit;
    }

    /**
     * Sitemap XML dinâmico
     */
    public function sitemap(): void
    {
        header('Content-Type: application/xml; charset=UTF-8');
        require BASE_PATH . '/public/sitemap.xml.php';
        exit;
    }
}
