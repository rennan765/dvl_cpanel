<?php
include_once '../app/functions.php';
include_once '../app/class/User.class.php';
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

//CREATING THE USER LIST
$listUser = UserDao::getUserList();
//ERROR MESSAGE
if(empty($listUser)):
    die ("<h1>Erro ao acessar o banco de dados. </h1>");
endif;
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <title>Esqueceu sua senha</title>
        <link rel="stylesheet" href="../assets/css/frame.css">
        <link rel="stylesheet" href="../assets/css/controlpanel_functions/showUsers.css">
        <link rel="stylesheet" href="../assets/framewarp/assets/framewarp/framewarp.css">
        <link rel="stylesheet" href="../assets/css/font-awesome.css">
    </head>
    <body>
        <div class="frame">
            <form name="showUserForm" action="../app/control/controlDeleteUser.php" id="showUsersForm" method="POST">
                <legend>Lista de usuários</legend>
                <div class="userList">
                    <?php
                    foreach ($listUser as $user):
                        ?>
                        <div class="user">
                            <input type="radio" name="idUser" value="<?=$user->getId()?>" required <?= ($user->getId() == 1) ? "checked" : ""?>>
                            <p <?=  isUserAdm($user) ? "style='color: red; font-weight: bold;'" : ""?>><?=$user->getEmail()?></p>
                           <i class="<?=$user->getActive() ?  'fa fa-check' : 'fa fa-times'?>"></i>
                        </div>
                        <?php
                    endforeach;
                    ?>
                </div>
                <div class="formButtons">
                    <button type="button" name="activateUser" value="activateUser" onclick="sendForm(1)">Ativar Usuário</button>
                    <button type="button" name="resetPass" value="resetPass" onclick="sendForm(2)">Resetar Senha</button>
                    <button type="button" name="deleteUser" value="deleteUser" onclick="confirmDeleteUser()">Excluir Usuário</button>
                </div>
            </form>
        </div>
        <script src="../assets/js/script.js"></script>
    </body>
</html>