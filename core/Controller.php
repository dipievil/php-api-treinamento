<?php
// /core/Controller.php

class Controller {

    /**
     * Carrega um Model.
     * @param string $model Nome do Model
     * @return object Instância do Model
     */
    public function model($model) {
        // Verifica se o arquivo do model existe
        if (file_exists(APP_PATH . '/Models/' . ucfirst($model) . '.php')) {
            require_once APP_PATH . '/Models/' . ucfirst($model) . '.php';
            $modelClass = ucfirst($model);
            return new $modelClass();
        } else {
            // Lança um erro ou lida com a ausência do model
            //die("Model '{$model}' não encontrado.");
        }
    }

    /**
     * Carrega uma View.
     * @param string $view Nome da View (ex: 'home/index')
     * @param array $data Dados para passar para a View
     */
    public function view($view, $data = []) {
        // Extrai os dados para que possam ser usados como variáveis na view
        // Ex: $data = ['nome' => 'João'] se torna $nome = 'João' na view
        extract($data);

        $viewFile = APP_PATH . '/Views/' . $view . '.php';
        // var_dump("Tentando carregar a view: " . $viewFile); // Linha de depuração temporária        

        // Verifica se o arquivo da view existe
        if (file_exists(APP_PATH . '/Views/' . $view . '.php')) {
            require_once APP_PATH . '/Views/' . $view . '.php';
        } else {
            // Lança um erro ou lida com a ausência da view
           // die("View '{$view}' não encontrada.");
        }
    }

    /**
     * Envia uma resposta JSON padronizada.
     * @param mixed $data Os dados a serem codificados em JSON.
     * @param int $statusCode O código de status HTTP (padrão é 200).
     */
    protected function sendJsonResponse($data, $statusCode = 200) {
        // Garante que nenhum output anterior interfira com os cabeçalhos JSON
        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit; // Termina o script após enviar a resposta JSON
    }
}

?>