<?php
// /app/Controllers/UserController.php

require_once ROOT_PATH . '/core/Controller.php'; // Inclui o Controller base

class UserController extends Controller {

    /**
     * Lida com requisições GET para o endpoint /user (ou /user/index)
     * Retorna os dados do primeiro usuário de exemplo em formato JSON.
     */
    public function index() {
        // 1. Verificar se o método da requisição é GET
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendJsonResponse(['error' => 'Método não permitido. Use GET.'], 405); // 405 Method Not Allowed
            // A função sendJsonResponse já inclui exit
        }

        try {
            // 2. Carregar o Model
            $userModel = $this->model('User'); // Carrega o model User.php

            // 3. Obter os dados do usuário
            $userData = $userModel->getPrimeiroUsuario(); // Pega os dados do usuário de exemplo

            if ($userData) {
                // 4. Enviar a resposta JSON com sucesso
                $this->sendJsonResponse($userData, 200); // 200 OK
            } else {
                $this->sendJsonResponse(['error' => 'Usuário não encontrado.'], 404); // 404 Not Found
            }
        } catch (Exception $e) {
            // Em um ambiente de produção, você deveria logar o erro $e->getMessage()
            // em vez de, ou além de, enviá-lo na resposta.
            $this->sendJsonResponse(['error' => 'Erro interno no servidor.', 'details' => $e->getMessage()], 500); // 500 Internal Server Error
        }
    }

    /**
     * Lida com requisições GET para o endpoint /user/show/ID
     * Retorna os dados de um usuário específico em formato JSON.
     * @param int $id O ID do usuário a ser buscado.
     */
    public function show($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendJsonResponse(['error' => 'Método não permitido. Use GET.'], 405);
        }

        // Validar o ID
        if ($id === null || !filter_var($id, FILTER_VALIDATE_INT) || (int)$id <= 0) {
            $this->sendJsonResponse(['error' => 'ID do usuário inválido ou não fornecido. Deve ser um inteiro positivo.'], 400); // 400 Bad Request
        }
        $id = (int)$id;

        try {
            $userModel = $this->model('User');
            $userData = $userModel->getUsuarioPorId($id);

            if ($userData) { // Supondo que getUsuarioPorId retorna dados ou algo 'falsey'
                $this->sendJsonResponse($userData, 200);
            } else {
                $this->sendJsonResponse(['error' => "Usuário com ID {$id} não encontrado."], 404);
            }
        } catch (Exception $e) {
            $this->sendJsonResponse(['error' => 'Erro interno no servidor.', 'details' => $e->getMessage()], 500);
        }
    }

    // Poderíamos adicionar outros métodos aqui para POST, PUT, DELETE no futuro.
}
?>