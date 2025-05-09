<?php
// /app/Views/home/index.php

// Opcional: Incluir um cabeçalho de layout, se você tiver um
// if (file_exists(APP_PATH . '/Views/layouts/header.php')) {
//     require_once APP_PATH . '/Views/layouts/header.php';
// }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($titulo) ? htmlspecialchars($titulo) : 'Meu Site MVC'; ?></title>
    </head>
<body>
    <h1><?php echo isset($mensagem) ? htmlspecialchars($mensagem) : 'Página Carregada!'; ?></h1>

    <?php if (isset($usuario) && is_array($usuario)): ?>
        <h2>Dados do Usuário (do Model):</h2>
        <p>ID: <?php echo htmlspecialchars($usuario['id']); ?></p>
        <p>Nome: <?php echo htmlspecialchars($usuario['nome']); ?></p>
        <p>Email: <?php echo htmlspecialchars($usuario['email']); ?></p>
    <?php endif; ?>

    <?php if (!empty($nome_param)): ?>
        <p>Olá, <?php echo htmlspecialchars($nome_param); ?>!</p>
    <?php endif; ?>

    <?php if (!empty($idade_param)): ?>
        <p>Você tem <?php echo htmlspecialchars($idade_param); ?> anos.</p>
    <?php endif; ?>

    <p>Se você vê esta mensagem, a view `home/index.php` foi carregada com sucesso!</p>
</body>
</html>
<?php
// Opcional: Incluir um rodapé de layout
// if (file_exists(APP_PATH . '/Views/layouts/footer.php')) {
//     require_once APP_PATH . '/Views/layouts/footer.php';
// }
?>