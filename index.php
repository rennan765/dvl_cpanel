<?php
include 'app/class/User.class.php';
include 'app/dao/UserDao.class.php';
include 'app/functions.php';

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
$pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);
$success = true;
$active = true;

if (!empty($email) || !empty($pass)):
    //TRY TO FIND
    $user = UserDao::getUserByEmailPass($email, md5($pass));
    //IF SUCCESS
    if (!empty($user)):
        if ($user->getActive()):
            updateLog('login', $user, null, true);
            logIn($user);
        else:
            updateLog('login', $user, null, false);
            $active = false;
        endif;
    else:
        updateLog('login', null, null, false);
        $success = false;
    endif;
endif;
?>

<!DOCTYPE html>
<html lang="ptBR">
    <head>
        <meta charset="UTF-8">
        <title>Login - Painel de controle</title>
        <link rel="stylesheet" href="assets/css/stylePortrait.css">
        <link rel="stylesheet" href="assets/css/styleLandscape.css">
        <link rel="stylesheet" href="assets/css/font-awesome.css">
        <!-- Frame Warp -->
        <link rel="stylesheet" href="assets/framewarp/assets/framewarp/framewarp.css">
    </head>
    <body>
        <section>
            <div class="loginBox">
                <h1 class="fontzero">Login no painel de controle</h1>
                <form action="index.php" method="POST">
                    <div class="formHeader">
                        <img src="assets/img/logo.png" alt="Logo">
                        <legend><p>Efetue o login</p></legend>	
                    </div>
                    <div class="formFields">
                        <label for="email">
                            <input type="email" name="email" placeholder="Insira o seu e-mail." required>
                        </label>
                        <label for="pass">
                            <input type="password" name="pass" placeholder="Insira a sua senha." required>
                        </label>
                        <button type="submit"><i class="fa fa-sign-in"></i> Login</button>
                    </div>
                    <div class="formFooter">
                        <div id="wrongPass"><?= !$success ? "<p>Nome de usuário/senha incorretos.</p>" : (!$active ? "<p>Usuário inativo. Contate o administrador do sistema.</p>" : ""); ?></div> 
                        <a href="controlpanel_functions/forgotPass.php" id="forgotPass">Esqueci minha senha.</a>
                    </div>
                </form>
                <div class="test"></div>
            </div>
        </section>

        <!-- FrameWarp -->
        <script src="assets/js/jquery-1.7.2.min.js"></script>
        <script src="assets/framewarp/assets/js/jquerypp.custom.js"></script>
        <script src="assets/framewarp/assets/framewarp/framewarp.js"></script>
        <script src="assets/framewarp/assets/js/script.js"></script>
    </body>
</html>