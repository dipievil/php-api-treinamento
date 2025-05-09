<?php
// /app/Models/User.php

// (Opcional) Se você tiver um Model base, pode estendê-lo.
// require_once ROOT_PATH . '/core/Model.php';
// class User extends Model {

class User {
    // Em uma aplicação real, você teria propriedades e métodos
    // para interagir com a tabela 'users' do banco de dados.

    public function getPrimeiroUsuario() {
        // Simulação: buscaria o primeiro usuário no banco de dados.
        // Aqui, apenas retornamos dados fictícios.
        return [
            'id' => 1,
            'nome' => 'Usuário Exemplo',
            'email' => 'exemplo@site.com'
        ];
    }

    public function getUsuarioPorId($id) {
        // Simulação: buscaria um usuário por ID.
        return [
            'id' => $id,
            'nome' => 'Usuário ' . $id,
            'email' => 'usuario'.$id.'@site.com'
        ];
    }
}
?>