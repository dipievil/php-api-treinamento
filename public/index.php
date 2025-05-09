<?php
// /public/index.php

// Habilitar exibição de erros (PARA DESENVOLVIMENTO)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define uma constante para o diretório raiz da aplicação (um nível acima de /public)
define('ROOT_PATH', dirname(__DIR__));

// Define uma constante para o diretório da aplicação (/app)
define('APP_PATH', ROOT_PATH . '/app');

// Inclui o roteador básico
require_once ROOT_PATH . '/core/App.php';

// Instancia o roteador e processa a requisição
$app = new App();

?>