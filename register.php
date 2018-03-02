<?php
require_once './app/Config.inc.php';
$create = new Create();
$read = new Read();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Register</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <center>
                    <h1>REGISTER</h1>
                    <img src="./drone.png">
                    <form method="POST">
                        <div class="input-group col-md-6">
                            <input type="nome" class="form-control" placeholder="Nome" name="nome">
                        </div>
                        <br>
                        <div class="input-group col-md-6">
                            <input type="email" class="form-control" placeholder="Email" name="email">
                        </div>
                        <br>
                        <div class="input-group col-md-6">
                            <input type="password" class="form-control" placeholder="Senha" name="senha">
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary btn-lg" name="register">
                            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Cadastrar
                        </button>
                        <a href="index.php">
                            <button type="button" class="btn btn-info btn-lg">
                                <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Voltar
                            </button>
                        </a>
                    </form>
                </center>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <?php
        if (isset($_POST['register'])) {
            if (!empty($_POST['email']) && !empty($_POST['nome']) && !empty($_POST['senha'])) {
                $dados = [
                    "email" => addslashes($_POST['email']),
                    "nome" => addslashes($_POST['nome']),
                    "senha" => sha1(addslashes($_POST['senha']))
                ];

                $read->exeRead("usuarios", "WHERE email = :email", "email={$dados["email"]}");
                if ($read->getRowCount() >= 1) {
                    frontErro("Email ja cadastrado", MS_ALERT);
                } else {
                    $create->exeCreate("usuarios", $dados);
                    session_start();
                    $_SESSION['usuario'] = $dados["email"];
                    $_SESSION['senha'] = $dados["senha"];
                    header("location: home.php");
                }
            } else {
                frontErro("Algum campo esta vazio!", MS_ALERT);
            }
        }
        ?>
    </body>
</html>


