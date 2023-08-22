
<?php


// Dados de ligação
$database = 'loja_online';
$username = 'root';
$password = '';

try {
    // Ligação
    $ligacao = new PDO("mysql:host=localhost;dbname=$database;charset=utf8", $username, $password);
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Busca de Cliente
    if (isset($_GET['client_id'])) {
        $client_id = $_GET['client_id'];
        $resultados = $ligacao->prepare("SELECT * FROM clientes WHERE id = :client_id");
        $resultados->bindParam(':client_id', $client_id, PDO::PARAM_INT);
        $resultados->execute();
    }

    // Inserção de Cliente
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'];
        $cpf = $_POST['cpf'];

        $inserirCliente = $ligacao->prepare("INSERT INTO clientes (nome, cpf) VALUES (:nome, :cpf)");
        $inserirCliente->bindParam(':nome', $nome, PDO::PARAM_STR);
        $inserirCliente->bindParam(':cpf', $cpf, PDO::PARAM_STR);
        $inserirCliente->execute();
    }
} catch (PDOException $err) {
    $erro = "Aconteceu um erro na ligação.";
}

// Fechar a ligação
$ligacao = null;
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap.min.css">
</head>

<body>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-5">
            <form method="get">
                <label for="client_id">ID do Cliente:</label>
                <input type="text" name="client_id" id="client_id">
                <button type="submit">Buscar</button>
            </form>
        </div>
    </div>
</div>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-5">
            <form method="post">
                <label for="nome">Nome do Cliente:</label>
                <input type="text" name="nome" id="nome">
                <label for="cpf">cpf do Cliente:</label>
                <input type="text" name="cpf" id="cpf">
                <button type="submit">Adicionar Cliente</button>
            </form>
        </div>
    </div>
</div>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-5">

            <?php if (!empty($erro)) : ?>
                <p class="text-center text-danger"><?= $erro ?></p>
            <?php else : ?>
                <?php if (isset($resultados) && $resultados->rowCount() == 0) : ?>
                    <p class="text-center">Não foram encontrados dados.</p>
                <?php else : ?>

                    <?php foreach ($resultados as $clientes) : ?>
                        <div class="card p-3 mb-2 bg-light text-center">
                            <h5 class="text-primary"><?= $clientes['nome'] ?></h5>
                            <h5 class="text-primary"><?= $clientes['data_nascimento'] ?></h5>
                             <h3 class="text-primary"> CPF: <?= $clientes['cpf'] ?></h5> 
                        </div>
                    <?php endforeach; ?>

                <?php endif; ?>
            <?php endif; ?>

        </div>
    </div>
</div>

</body>

</html>

