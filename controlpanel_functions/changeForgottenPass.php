<?php
include_once '../app/functions.php';
include_once '../app/class/User.class.php';
include_once '../app/dao/UserDao.class.php';

$emailForgottenPass = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_STRING);
$user = UserDao::getUserByEmail($emailForgottenPass);

//IF THERE IS AN E-MAIL TO RECOVER PASS
if(!empty($emailForgottenPass)):
    session_start();
    $pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);
    $rePass = filter_input(INPUT_POST, 'rePass', FILTER_SANITIZE_STRING);
    $samePass = true;
    
    //WAS FORM SUBMITTED?
    if($emailForgottenPass && (!empty($pass) && !empty($rePass))):
        $user = UserDao::getUserByEmail(filter_input(INPUT_POST, 'userEmail', FILTER_SANITIZE_STRING));
        //DO PASS AND REPASS CHECK?
        if($pass == $rePass):
            //TRY TO UPDATE
            if(UserDao::updateUserPassById($user->getId(), md5($pass))):
                $_SESSION["logged"] = true;
                updateLog('changePass', $user, null, true);
                header('Location: result.php?resultMessage=changeForgottenPass-success');
            else:
                $_SESSION["logged"] = true;
                updateLog('changePass', $user, null, false);
                header('Location: result.php?resultMessage=changeForgottenPass-failure');
            endif;
        else:
            $samePass = false;
        endif;
    endif;
else:
    header('Location: ../index.php');
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
            <form action="changeForgottenPass.php?email=true" method="POST">
                <legend>Alterar a senha do usuário</legend>
                <p><b>Email:</b> <?= $user->getEmail(); ?></p>
                <label for="pass">
                    <input type="password" name="pass" placeholder="Insira a nova senha." required>
                </label>
                <label for="rePass">
                    <input type="password" name="rePass" placeholder="Repita a nova senha." required>
                </label>
                <label for="userEmail">
                    <input type="hidden" name="userEmail" value="<?=$user->getEmail();?>">
                </label>
                <button type="submit"><i class="fa fa-user-plus"></i> Alterar senha</button>

                <?= !$samePass ? "<p id='passNoMatch'>A senha não confere. </p>" : "" ?>
            </form>
        </div>
    </body>
</html>