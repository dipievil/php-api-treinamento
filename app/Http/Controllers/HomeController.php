<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Display the home page.
     *
     * @param string $nome
     * @param string $idade
     * @return \Illuminate\Http\Response
     */
    public function index($nome = '', $idade = '')
    {
        $dadosParaView = [
            'titulo' => 'Página Inicial',
            'mensagem' => 'Bem-vindo à nossa estrutura MVC básica!',
            'nome_param' => $nome,
            'idade_param' => $idade
        ];

        // Carrega a view 'home/index.php' e passa os dados para ela
        return view('home.index', $dadosParaView);
    }

    /**
     * Display the about page.
     *
     * @return \Illuminate\Http\Response
     */
    public function sobre()
    {
        $dados = ['titulo' => 'Sobre Nós'];
        return view('home.sobre', $dados);
    }
}
