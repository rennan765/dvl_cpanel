<?php
include '../app/class/User.class.php';
include '../app/dao/UserDao.class.php';
include '../app/functions.php';

if(!sessionCheck()):
    header('Location: ../index.php');
endif;

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
            <form name="showUserForm" action="../app/control/controlShowUsers.php" method="POST">
                <legend>Lista de usuários</legend>
                <div class="userList">
                    <?php
                    foreach ($listUser as $user):
                        ?>
                        <div class="user">
                            <input type="radio" name="idUser" value="<?=$user->getId()?>" required>
                           <p><?=$user->getEmail()?></p>
                           <i class="<?=$user->getActive() ?  'fa fa-check' : 'fa fa-times'?>"></i>
                        </div>
                        <?php
                    endforeach;
                    ?>
                </div>
                <div class="formButtons">
                    <button type="submit" name="activateUser" value="activateUser">Ativar Usuário</button>
                    <button type="submit" name="resetPass" value="resetPass">Resetar Senha</button>
                    <button type="submit" name="deleteUser" onclick="confirmDeleteUser()" value="deleteUser">Excluir Usuário</button>
                </div>
            </form>
        </div>
        <script src="../assets/js/script.js"></script>
    </body>
</html>