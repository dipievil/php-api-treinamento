<?php
// /core/App.php

class App {
    protected $controller = 'HomeController'; // Controller padrão
    protected $method = 'index';             // Método padrão
    protected $params = [];                  // Parâmetros da URL

    public function __construct() {
        $url = $this->parseUrl();

        // 1. Verifica se o Controller existe
        if (isset($url[0]) && !empty($url[0]) && file_exists(APP_PATH . '/Controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        }
        require_once APP_PATH . '/Controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // 2. Verifica se o Método existe no Controller
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // 3. Pega os Parâmetros
        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /**
     * Trata a URL, quebrando-a em partes.
     * Funciona com o servidor embutido do PHP ou com Apache + .htaccess (se $_GET['url'] estiver definido)
     * @return array
     */
    public function parseUrl() {
        $requestPath = '';
        if (isset($_GET['url'])) {
            // Se usando Apache com .htaccess, $_GET['url'] estará disponível
            $requestPath = $_GET['url'];
        } else {
            // Se usando o servidor embutido do PHP ou outra configuração sem ?url=
            // Pega o caminho da REQUEST_URI e remove a query string, se houver.
            $requestUri = $_SERVER['REQUEST_URI'];
            if (($pos = strpos($requestUri, '?')) !== false) {
                $requestUri = substr($requestUri, 0, $pos);
            }
            // Remove a barra inicial se houver
            $requestPath = trim($requestUri, '/');
        }

        if (!empty($requestPath)) {
            return explode('/', filter_var(rtrim($requestPath, '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
?>