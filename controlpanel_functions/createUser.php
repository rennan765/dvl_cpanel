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
        header('Location: result.php');
        break;
    default:
        //NO ACTION SET
        break;
endswitch;

if (!isAdm()):
    header('Location: ../controlpanel.php');
endif;

if (!empty(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING)) || !empty(filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING))):  //IF FORM HAS BEEN SUBMITED
    //INICIATE USER ARRAY
    $listUser = UserDao::getUserList();

    //INICIATE THE NEW USER
    $newUser = new User(null, filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING), md5(filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING)), 1, true);

    //CHECK TO CREATE
    if (!empty($newUser)):  //OBJECT IS NOT EMPTY
        //CHECK ALL USERS
        foreach ($listUser as $user):
            if ($user->getEmail() != $newUser->getEmail()):
                $sameEmail = false;
                continue;
            else:
                $sameEmail = true;
                break;
            endif;
        endforeach;

        //ISN'T THIS E-MAIL SIGNED IN YET?
        if (!$sameEmail):
            //TRY TO CREATE
            if (UserDao::createUser($newUser)):
                $_SESSION["createUser"] = 'success';
                updateLog('createUser', UserDao::getUserById($_SESSION["userId"]), $newUser, true);
                header('Location: result.php');
            else:
                $_SESSION["createUser"] = 'failure';
                updateLog('createUser', UserDao::getUserById($_SESSION["userId"]), $newUser, false);
                header('Location: result.php');
            endif;
        else:
            $_SESSION["createUser"] = 'alreadySign';
            updateLog('createUser', UserDao::getUserById($_SESSION["userId"]), $newUser, false);
            header('Location: result.php');
        endif;
    endif;
endif;
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <title>Novo Usuário</title>
        <link rel="stylesheet" href="../assets/css/frame.css">
        <link rel="stylesheet" href="../assets/css/controlpanel_functions/createUser.css">
        <link rel="stylesheet" href="../assets/framewarp/assets/framewarp/framewarp.css">
        <link rel="stylesheet" href="../assets/css/font-awesome.css">
    </head>
    <body>
        <div class="frame">
            <form action="createUser.php" method="POST">
                <legend>Formulário de cadastro de usuários</legend>
                <label for="email">
                    <input type="email" name="email" placeholder="Insira o e-mail." required>
                </label>
                <label for="pass">
                    <input type="password" name="pass" placeholder="Insira a senha." required>
                </label>
                <button type="submit"><i class="fa fa-user-plus"></i> Efetuar cadastro</button>
            </form>
        </div>
    </body>
</html>