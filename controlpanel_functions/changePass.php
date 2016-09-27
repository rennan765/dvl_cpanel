<?php
include_once '../app/functions.php';
include_once '../app/dao/UserDao.class.php';

switch (sessionCheck()):
    case 'userIsLogged':
        //NO ACTION SET
        break;
    case 'userIsNotLogged':
        header('Location: ../index.php');
        break;
    case 'sessionTimeOut':
        header('Location: result.php?resultMessage=timeOut');
        break;
    default:
        //NO ACTION SET
        break;
endswitch;

$pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);
$rePass = filter_input(INPUT_POST, 'rePass', FILTER_SANITIZE_STRING);
$samePass = true;

if (!empty($pass) && !empty($rePass)): //FORM IS EMPTY?
    //IS PASS AND REPASS CHECK?
    if ($pass == $rePass):
        //TRY TO UPDATE
        if (UserDao::updateUserPassById($_SESSION["userId"], md5($pass))):
            updateLog('changePass', UserDao::getUserById($_SESSION["userId"]), null, true);
            header('Location: result.php?resultMessage=changePass-success');
        else:
            updateLog('changePass', UserDao::getUserById($_SESSION["userId"]), null, false);
            header('Location: result.php?resultMessage=changePass-failure');
        endif;
    else:
        $samePass = false;
    endif;
endif;
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <title>Alterar a Senha do Usuário</title>
        <link rel="stylesheet" href="../assets/css/frame.css">
        <link rel="stylesheet" href="../assets/css/controlpanel_functions/changePass.css">
        <link rel="stylesheet" href="../assets/framewarp/assets/framewarp/framewarp.css">
        <link rel="stylesheet" href="../assets/css/font-awesome.css">
    </head>
    <body>
        <div class="frame">
            <form action="changePass.php" method="POST">
                <legend>Alterar a senha do usuário</legend>
                <p><b>Email:</b> <?= $_SESSION["userEmail"]; ?></p>
                <label for="pass">
                    <input type="password" name="pass" placeholder="Insira a nova senha." required>
                </label>
                <label for="rePass">
                    <input type="password" name="rePass" placeholder="Repita a nova senha." required>
                </label>
                <button type="submit"><i class="fa fa-user-plus"></i> Alterar senha</button>

                <?= !$samePass ? "<p id='passNoMatch'>A senha não confere. </p>" : "" ?>
            </form>
        </div>
    </body>
</html>