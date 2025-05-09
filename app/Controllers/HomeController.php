<?php
// /app/Controllers/HomeController.php

// Inclui o Controller base, se você o criou
require_once ROOT_PATH . '/core/Controller.php';

class HomeController extends Controller { // Herda do Controller base

    public function index($nome = '', $idade = '') {
        // Exemplo de como carregar um model (vamos criar um User.php simples depois)
        $userModel = $this->model('User');
        $usuario = $userModel->getPrimeiroUsuario();

        $dadosParaView = [
            'titulo' => 'Página Inicial',
            'mensagem' => 'Bem-vindo à nossa estrutura MVC básica!',
            'nome_param' => $nome,
            'idade_param' => $idade
        ];

        // Carrega a view 'home/index.php' e passa os dados para ela
        $this->view('home/index', $dadosParaView);
    }

    public function sobre() {
        $dados = ['titulo' => 'Sobre Nós'];
        $this->view('home/sobre', $dados); // Você precisaria criar a view /app/Views/home/sobre.php
    }
}
?>