<?php

ini_set('display_errors', 1);
//Caracteres usados na senha
$itens = "abcdefghijklmnopkrstuvxyzABCDEFGHIJKLMNOPQRSTUVXWZ0123456789!@#$%&*()_-+{}:><,.;/~[]";

//Gera a senha aleatória após repetir 2 vezes a string acima
$rand = str_shuffle(str_repeat($itens, 2));

//Pega o tamanho da senha do form abaixo
$size = filter_input(INPUT_POST, 'size', FILTER_SANITIZE_NUMBER_INT);
if ($size) {
    $pass =  substr($rand, 0, $size);
}

//Exibe um erro se ficar em branco
$error = "Digite um valor acima";

//Calculando a combinação de senhas;
$n = strlen($rand);
$p = $size;

//Total de senhas que podem ser geradas C = n!/p!(n-p)!
if(isset($_POST['size']) && !empty($_POST['size'])){
    $c = (array_product(range($n, 1))) / (array_product(range($p, 1)) * array_product(range($n - $p, 1)));
}
?>
<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title> Simple Password Generator </title>
    <link href="assets/css/custom.css" rel="stylesheet">
</head>

<body>

    <div class="container col-6">
        <h1>Simple Password Generator</h1>
        <form action="" method="post">
            <div class="mb-3">
                <label for="" class="form-label">Enter lenght of password </label>
                <input type="number" name="size" id="size" class="form-control" placeholder="" aria-describedby="helpId">
                <div class='mt-2'>
                    <?php if (isset($pass) && !empty($pass)) : ?>
                        <p>The password is <strong><?= $pass; ?></strong></p>
                        <p>There are <strong><?= number_format($c, 0, ',', '.') ?></strong> passwords with <strong><?= $size ?></strong> caracters between <strong><?= $n ?></strong></p>
                    <?php else : ?>
                        <div class="alert alert-primary text-center" role="alert">
                            <p><?php if ($error) echo $error; ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-danger">Submit</button>
            </div>
        </form>


        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js"></script>
</body>

</html>