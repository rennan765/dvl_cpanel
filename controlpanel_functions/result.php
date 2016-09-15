<?php
include '../app/functions.php';

if(!sessionCheck()):
    header('Location: ../index.php');
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
            //CREATE USER
            if (isset($_SESSION["createUser"])):
                if ($_SESSION["createUser"] == 'alreadySign'):
                    echo "<i class='fa fa-times'></i><p>Já existe um usuário com este endereço de e-mail.</p>";
                    unset($_SESSION["createUser"]);
                else:
                    if ($_SESSION["createUser"] == 'success'):
                        echo "<i class='fa fa-check'></i><p>Novo usuário criado com sucesso.</p>";
                        unset($_SESSION["createUser"]);
                    else:
                        if ($_SESSION["createUser"] == 'failure'):
                            echo "<i class='fa fa-times'></i><p>Falha ao criar o novo usuário.</p>";
                            unset($_SESSION["createUser"]);
                        endif;
                    endif;
                endif;
            endif;

            //CHANGE PASSWORD
            if (isset($_SESSION["changePass"])):
                if ($_SESSION["changePass"]):
                    echo "<i class='fa fa-check'></i><p>A senha foi alterada com sucesso.</p>";
                    unset($_SESSION["changePass"]);
                else:
                    echo "<i class='fa fa-times'></i><p>Erro ao efetuar a alteração da senha.</p>";
                    unset($_SESSION["changePass"]);
                endif;
            endif;

            //ACTIVATE/DEACTIVATE USER
            if (isset($_SESSION["activateUser"])):
                if ($_SESSION["activateUser"] == 'noPermission'):
                    echo "<i class='fa fa-times'></i><p>Sem permissão para ativar/desativar usuário. Contate o administrador do sistema.</p>";
                    unset($_SESSION["activateUser"]);
                else:
                    if ($_SESSION["activateUser"] == 'activated'):
                        echo "<i class='fa fa-check'></i><p>Usuário ativado com sucesso.</p>";
                        unset($_SESSION["activateUser"]);
                    else:
                        if ($_SESSION["activateUser"] == 'deactivated'):
                            echo "<i class='fa fa-check'></i><p>Usuário desativado com sucesso.</p>";
                            unset($_SESSION["activateUser"]);
                        else:
                            if ($_SESSION["activateUser"] == 'failure'):
                                echo "<i class='fa fa-times'></i><p>Erro ao ativar/desativar usuário.</p>";
                                unset($_SESSION["activateUser"]);
                            endif;
                        endif;
                    endif;
                endif;
            endif;

            //RESET PASSWORD
            if (isset($_SESSION["resetPass"])):
                if ($_SESSION["resetPass"] == 'noPermission'):
                    echo "<i class='fa fa-times'></i><p>Sem permissão para resetar a senha do usuário. Contate o administrador do sistema.</p>";
                    unset($_SESSION["resetPass"]);
                else:
                    if ($_SESSION["resetPass"] == 'success'):
                        echo "<i class='fa fa-check'></i><p>A senha do usuário foi resetada para '0000' com sucesso..</p>";
                        unset($_SESSION["resetPass"]);
                    else:
                        if ($_SESSION["resetPass"] == 'failure'):
                            echo "<i class='fa fa-times'></i><p>Erro ao resetar a senha.</p>";
                            unset($_SESSION["resetPass"]);
                        endif;
                    endif;
                endif;
            endif;

            //DELETE USER
            if (isset($_SESSION["deleteUser"])):
                if ($_SESSION["deleteUser"] == 'noPermission'):
                    echo "<i class='fa fa-times'></i><p>Sem permissão para excluir o usuário. Contate o administrador do sistema.</p>";
                    unset($_SESSION["deleteUser"]);
                else:
                    if ($_SESSION["deleteUser"] == 'success'):
                        echo "<i class='fa fa-check'></i><p>O usuário foi excluído com sucesso.</p>";
                        unset($_SESSION["deleteUser"]);
                    else:
                        if ($_SESSION["deleteUser"] == 'failure'):
                            echo "<i class='fa fa-times'></i><p>Erro ao excluir o usuário.</p>";
                            unset($_SESSION["deleteUser"]);
                        endif;
                    endif;
                endif;
            endif;

            //FORGOT PASS
            if (isset($_SESSION["sendEmail"])):
                if ($_SESSION["sendEmail"] == 'noEmail'):
                    echo "<i class='fa fa-times'></i><p>Este e-mail não está cadastrado neste painel de controle.</p>";
                    unset($_SESSION["sendEmail"]);
                    unset($_SESSION['logged']);
                    session_destroy();
                else:
                    if ($_SESSION["sendEmail"] == 'success'):
                        echo "<i class='fa fa-check'></i><p>Foi enviado um e-mail de recuperação de senha para o usuário.</p>";
                        unset($_SESSION["sendEmail"]);
                        unset($_SESSION['logged']);
                        session_destroy();
                    else:
                        if ($_SESSION["sendEmail"] == 'failure'):
                            echo "<i class='fa fa-times'></i><p>Erro ao enviar o e-mail. Contate o administrador do sistema.</p>";
                            unset($_SESSION["sendEmail"]);
                            unset($_SESSION['logged']);
                            session_destroy();
                        endif;
                    endif;
                endif;
            endif;
            
            //CHANGE  FORGOTTEN PASSWORD
            if (isset($_SESSION["changeForgottenPass"])):
                if ($_SESSION["changeForgottenPass"]):
                    echo "<i class='fa fa-check'></i><p>A senha foi alterada com sucesso. <a href='../index.php'>Clique Aqui</a> para efetuar o login.</p>";
                    unset($_SESSION["changeForgottenPass"]);
                    unset($_SESSION['logged']);
                    session_destroy();
                else:
                    echo "<i class='fa fa-times'></i><p>Erro ao efetuar a alteração da senha. Contate o administrador do sistema.</p>";
                    unset($_SESSION["changeForgottenPass"]);
                    unset($_SESSION['logged']);
                    session_destroy();
                endif;
            endif;
            ?>
        </div>
    </body>
</html>