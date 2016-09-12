<?php
include '../app/class/User.class.php';
include '../app/dao/UserDao.class.php';

$email = filter_input(input_GET, 'email', FILTER_SANITIZE_STRING);

if (!empty($email)):    //IF FORM HAS SENT
    $user = UserDao::getUserByEmail($email);
    //IF THERE IS AN USER WITH THIS E-MAIL
    if(!empty($user)):
        emailForgotPass($email);
    else:
        session_start();
        $_SESSION["sendEmail"] = 'noEmail';
        header('Location: controlpanel_functions/result.php');
    endif;
endif;
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <title>Esqueceu sua senha</title>
        <link rel="stylesheet" href="../assets/css/frame.css">
        <link rel="stylesheet" href="../assets/css/controlpanel_functions/forgotPass.css">
        <link rel="stylesheet" href="../assets/framewarp/assets/framewarp/framewarp.css">
        <link rel="stylesheet" href="../assets/css/font-awesome.css">
    </head>
    <body>
        <div class="frame">
            <form action="" method="GET">
                <legend>Esqueceu sua senha?</legend>
                <label for="email">
                    <input type="email" name="email" placeholder="Insira aqui o seu e-mail" required>
                </label>
                <button type="submit"><i class="fa fa-sign-in"></i> Enviar</button>
            </form>
        </div>
    </body>
</html>