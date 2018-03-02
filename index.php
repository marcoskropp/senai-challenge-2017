<?php
require_once './app/Config.inc.php';

session_start();
if (isset($_SESSION['email'])) {
    header("location: home.php");
}

$read = new Read();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>HOME</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <center>
                    <h1>LOGIN</h1>
                    <img src="./drone.png">
                    <form method="POST">
                        <div class="input-group col-md-6">
                            <input type="email" class="form-control" placeholder="Email" name="email">
                        </div>
                        <br>
                        <div class="input-group col-md-6">
                            <input type="password" class="form-control" placeholder="Senha" name="senha">
                        </div>
                        <br>
                        <button id="botaoLogin" type="submit" class="btn btn-primary btn-lg" name="login">
                            <span id="spanLogin" class="glyphicon glyphicon-ok" aria-hidden="true"></span> Login
                        </button>
                        <a href="register.php">
                            <button type="button" class="btn btn-info btn-lg">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Cadastro
                            </button>
                        </a>
                    </form>
                </center>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <?php
        if (isset($_POST['login'])) {
            $dados = [
                "email" => addslashes($_POST['email']),
                "senha" => addslashes($_POST['senha'])
            ];
            $read->exeRead("usuarios", "WHERE email = :email AND senha = sha1(:senha)", "email={$dados["email"]}&senha={$dados["senha"]}");

            if ($read->getRowCount() == 1) {
                session_start();
                $_SESSION['email'] = $dados["email"];
                $_SESSION['senha'] = $dados["senha"];
                header("location: home.php");
                exit();
            } else {
                echo "<script>"
                . "$('#spanLogin').removeClass('glyphicon-ok');"
                . "$('#spanLogin').addClass('glyphicon-remove');"
                . "$('#botaoLogin').removeClass('btn-primary');"
                . "$('#botaoLogin').addClass('btn-danger');"
                . "</script>";
            }
        }
        ?>
    </body>
</html>


