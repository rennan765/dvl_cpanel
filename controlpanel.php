<?php
include 'app/functions.php';

if(!sessionCheck()):
    header('Location: index.php');
endif;
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <title>Painel de controle</title>
        <link rel="stylesheet" href="assets/css/stylePortrait.css">
        <link rel="stylesheet" href="assets/css/styleLandscape.css">
        <link rel="stylesheet" href="assets/css/font-awesome.css">
        <!-- Frame Warp -->
        <link rel="stylesheet" href="assets/framewarp/assets/framewarp/framewarp.css">
    </head>
    <body>
        <h1 class="fontzero">Painel de controle</h1>
        <header>
            <div class="container">
                <div class="imgHeader"><img src="assets/img/logo.png" alt="Logo"></div>
                <h2>Seja bem vindo!</h2>
            </div>
        </header>

        <section>
            <div class="boxPanel">
                <?php
                if ($_SESSION["userType"] == 0):
                    ?>
                    <div class="function" id="createUser">
                        <i class="fa fa-user-plus"></i>
                        <p>Criar usuário</p>
                    </div>
                    <?php
                endif;
                ?>
                <div class="function" id="changePass">
                    <i class="fa fa-unlock-alt"></i>
                    <p>Alterar senha</p>
                </div>
                <div class="function" id="showUsers">
                    <i class="fa fa-users" aria-hidden="true"></i>
                    <p>Listar usuários</p>
                </div>
                <div href="#" id="signOut" onclick="logOut()">
                    <i class="fa fa-sign-out"></i>
                    <p>Log-off</p>
                </div>				
            </div>
        </section>

        <footer>
            <div class="container">
                <a href="http://www.devloopers.com.br" target="_blank"><img src="assets/img/logo.png" alt="Logo"></a>
                <p><b>DevLoopers</b> &copy 2016</p>
            </div>
        </footer>

        <script src="assets/js/script.js"></script>
        <!-- FrameWarp -->
        <script src="assets/js/jquery-1.7.2.min.js"></script>
        <script src="assets/framewarp/assets/js/jquerypp.custom.js"></script>
        <script src="assets/framewarp/assets/framewarp/framewarp.js"></script>
        <script src="assets/framewarp/assets/js/script.js"></script>
    </body>
</html>