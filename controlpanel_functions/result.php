<?php
session_start();

function printResultMessage($html) {
    //IF THE USER IS NOT LOGGED, RETURN TO CONTROLPANEL.PHP
    if (!isset($_SESSION['logged'])):
        header('Location: ../controlpanel.php');
    //IF THE USER IS LOGGED, PRINT THE RESULT MESSAGE
    else:
        echo $html;
    endif;
}

$message = filter_input(INPUT_GET, 'resultMessage', FILTER_SANITIZE_STRING);
if(empty($message)):
    header('Location: ../controlpanel.php');
endif;
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <title>Resultado</title>
        <link rel="stylesheet" href="../assets/css/frame.css">
        <link rel="stylesheet" href="../assets/css/controlpanel_functions/result.css">
        <link rel="stylesheet" href="../assets/framewarp/assets/framewarp/framewarp.css">
        <link rel="stylesheet" href="../assets/css/font-awesome.css">
    </head>
    <body>
        <div class="frame">
            <?php
            //SWITCH MESSAGE PASSED BY LINK BEGIN
            switch ($message):
                //CREATE USER
                case 'createUser-success':
                    printResultMessage("<i class='fa fa-check'></i><p>Novo usuário criado com sucesso.</p>");
                    break;
                case 'createUser-failure':
                    printResultMessage("<i class='fa fa-times'></i><p>Falha ao criar o novo usuário.</p>");
                    break;
                case 'createUser-alreadySigned':
                    printResultMessage("<i class='fa fa-times'></i><p>Já existe um usuário com este endereço de e-mail.</p>");
                    break;
                //CHANGE PASSWORD
                case 'changePass-success':
                    printResultMessage("<i class='fa fa-check'></i><p>A senha foi alterada com sucesso.</p>");
                    break;
                case 'changePass-failure':
                    printResultMessage("<i class='fa fa-times'></i><p>Erro ao efetuar a alteração da senha.</p>");
                    break;
                //ACTIVATE USER
                case 'activateUser-noPermission':
                    printResultMessage("<i class='fa fa-times'></i><p>Sem permissão para ativar/desativar usuário. Contate o administrador do sistema.</p>");
                    break;
                case 'activateUser-activated':
                    printResultMessage("<i class='fa fa-check'></i><p>Usuário ativado com sucesso.</p>");
                    break;
                case 'activateUser-deactivated':
                    printResultMessage("<i class='fa fa-check'></i><p>Usuário desativado com sucesso.</p>");
                    break;
                case 'activateUser-failure':
                    printResultMessage("<i class='fa fa-times'></i><p>Erro ao ativar/desativar usuário.</p>");
                    break;
                //RESET PASSWORD
                case 'resetPass-success':
                    printResultMessage("<i class='fa fa-check'></i><p>A senha do usuário foi resetada para '0000' com sucesso..</p>");
                    break;
                case 'resetPass-failure':
                    printResultMessage("<i class='fa fa-times'></i><p>Erro ao resetar a senha.</p>");
                    break;
                case 'resetPass-noPermission':
                    printResultMessage("<i class='fa fa-times'></i><p>Sem permissão para resetar a senha do usuário. Contate o administrador do sistema.</p>");
                    break;
                //DELETE USER
                case 'deleteUser-success':
                    printResultMessage("<i class='fa fa-check'></i><p>O usuário foi excluído com sucesso.</p>");
                    break;
                case 'deleteUser-failure':
                    printResultMessage("<i class='fa fa-times'></i><p>Erro ao excluir o usuário.</p>");
                    break;
                case 'deleteUser-noPermission':
                    printResultMessage("<i class='fa fa-times'></i><p>Sem permissão para excluir o usuário. Contate o administrador do sistema.</p>");
                    break;
                //SEND E-MAIL
                case 'sendEmail-success':
                    echo "<i class='fa fa-check'></i><p>Foi enviado um e-mail de recuperação de senha para o usuário.</p>";
                    unset($_SESSION['logged']);
                    session_destroy();
                    break;
                case 'sendEmail-failure':
                    echo "<i class='fa fa-times'></i><p>Erro ao enviar o e-mail. Contate o administrador do sistema.</p>";
                    unset($_SESSION['logged']);
                    session_destroy();
                    break;
                case 'sendEmail-noEmail':
                    echo "<i class='fa fa-times'></i><p>Este e-mail não está cadastrado neste painel de controle.</p>";
                    unset($_SESSION['logged']);
                    session_destroy();
                    break;
                //CHANGE FORGOTTEN PASS
                case 'changeForgottenPass-success':
                    echo "<i class='fa fa-check'></i><p>A senha foi alterada com sucesso. <a href='../index.php'>Clique Aqui</a> para efetuar o login.</p>";
                    unset($_SESSION['logged']);
                    session_destroy();
                    break;
                case 'changeForgottenPass-failure':
                    echo "<i class='fa fa-times'></i><p>Erro ao efetuar a alteração da senha. Contate o administrador do sistema.</p>";
                    unset($_SESSION['logged']);
                    session_destroy();
                    break;
                //TIME OUT
                case 'timeOut':
                    printResultMessage("<i class='fa fa-times'></i><p>Sua seção expirou. <br> <a href='../index.php'>Voltar à página inicial.</a></p>");
                    unset($_SESSION['logged']);
                    session_destroy();
                    break;
                //IF THERE IS NO MESSAGE
                default:
                    header('Location: ../controlpanel.php');
                    break;
            endswitch;
            //SWITCH MESSAGE PASSED BY LINK END
            ?>
        </div>
    </body>
</html>