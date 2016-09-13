<?php

include '../dao/UserDao.class.php';

//DATABASE FUNCTIONS BEGIN

function databaseConnect($dbHost, $dbUser, $dbPass, $dbName) {
    //Attempt to connect to the MySQL server
    $dbConn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    if ($dbConn->connect_error):
        die("<h1>Erro ao tentar se conectar ao banco de dados!<h1>");
    endif;

    return $dbConn;
}

function databaseDisconnect($dbConn) {
    $dbConn->close();
}

//DATABASE FUNCTIONS END
//AUTHENTICATION FUNCTIONS BEGIN

function logIn($user) {
    session_start();
    $_SESSION["logged"] = true;
    $_SESSION["userId"] = $user->getId();
    $_SESSION["userEmail"] = $user->getEmail();
    $_SESSION["userPass"] = $user->getPass();
    $_SESSION["userType"] = $user->getType();
    $_SESSION["userActive"] = $user->getActive();
    header('Location: controlpanel.php');
}

function sessionCheck() {
    session_start();
    if (!$_SESSION['logged']):   //If user is not logged, then return to the index.php
        session_cache_expire(10);
        header('Location: ../index.php');
    endif;
}

function isAdm() {
    if ($_SESSION["userType"] == 0):
        return true;
    else:
        return false;
    endif;
}

//AUTHENTICATION FUNCTIONS END
//SEND E-MAIL FUNCTIONS BEGIN

function emailForgotPass($email) {
    session_start();
    $message = "";
    if (sendEmail($email, $message)):
        $_SESSION["sendEmail"] = 'success';
        header('Location: controlpanel_functions/result.php');
    else:
        $_SESSION["sendEmail"] = 'failure';
        header('Location: controlpanel_functions/result.php');
    endif;
}

function sendEmail($email, $message) {
    return true;
}

//SEND E-MAIL FUNCTIONS END
//UPDATE LOG FUNCTIONS BEGIN 

function writeFile($message) {
    $file = '/Applications/MAMP/htdocs/dvl_cpanel/app/log.txt';
    //CHECK IF THE FILE IS WRITABLE     
    if (is_writable($file)):
        //TRY TO OPEN
        $handle = fopen($file, 'a+');   //a+ TO READ AND WRITE, WITH THE POINTER AT THE FINAL OF THE FILE
        //IF COULD OPEN
        if ($handle):
            //TRY TO MANIPULATE
            if (fwrite($handle, $message)):
                fclose($handle);
            else:
                fclose($handle);
            endif;
        endif;
    endif;
}

function updateLog($operation, $userAction, $userUpdated, $success) {
    //GET ACTUAL DATE
    date_default_timezone_set('Brazil/East');
    $data = new DateTime();
    $message = "";
    //TREAT $success
    if ($success):
        $success = 'efetuada com sucesso';
    else:
        $success = 'falhou';
    endif;

    switch ($operation) {
        //LOGIN
        case 'login':
            //HAS AN USER BEEN PASSED BY PARAM?
            if(!empty($userAction)):
                //IS THIS USER ACTIVE?
                if($userAction->getActive()):
                    $message = "{$data->format('d-m-Y H:i:s')} - tentativa de login de {$userAction->getEmail()} {$success}\n";
                else:
                    $message = "{$data->format('d-m-Y H:i:s')} - tentativa de login de {$userAction->getEmail()} {$success}. Usuario inativo\n";
                endif;
            else:
                $message = "{$data->format('d-m-Y H:i:s')} - tentativa de login {$success}. Nome de usuario ou senha incorretos ou inexistentes.\n";
            endif;
            break;
        //LOGOUT
        case 'logout':
            $message = "{$data->format('d-m-Y H:i:s')} - tentativa de logout {$success}\n";
            break;
        //FORGOT PASS
        case 'forgotPass':
            if (!empty($userAction)):
                $message = "{$data->format('d-m-Y H:i:s')} - solicitacao de senha do usuario {$userAction->getEmail()} {$success}\n";
            else:
                $message = "{$data->format('d-m-Y H:i:s')} - solicitacao de senha do usuario {$success}\n";
            endif;
            break;
        //CREATE USER
        case 'createUser':
            $message = "{$data->format('d-m-Y H:i:s')} - criacao do usuario {$userUpdated->getEmail()} solicitada pelo usuario {$userAction->getEmail()} {$success}\n";
            break;
        //CHANGE PASS
        case 'changePass':
            $message = "{$data->format('d-m-Y H:i:s')} - alteracao da senha do usuario {$userAction->getEmail()} {$success}\n";
            break;
        //ACTIVATE USER
        case 'activateUser':
            $message = "{$data->format('d-m-Y H:i:s')} - ativar/desativar usuario {$userUpdated->getEmail()} solicitado pelo usuario {$userAction->getEmail()} {$success}\n";
            break;
        //RESET PASS
        case 'resetPass':
            $message = "{$data->format('d-m-Y H:i:s')} - reset da senha do usuario {$userUpdated->getEmail()} solicitada pelo usuario {$userAction->getEmail()} {$success}\n";
            break;
        //DELETE USER
        case 'deleteUser':
            $message = "{$data->format('d-m-Y H:i:s')} - exclusao do usuario {$userUpdated->getEmail()} solicitada pelo usuario {$userAction->getEmail()} {$success}\n";
            break;
        //UPDATE USER
        case 'updateUser':
            $message = "{$data->format('d-m-Y H:i:s')} - atualizacao dos dados do usuario {$userUpdated->getEmail()} solicitada pelo usuario {$userAction->getEmail()} {$success}\n";
            break;
        default:
            //NO ACTION SET
            break;
    }
    
    writeFile($message);
}

//UPDATE LOG FUNCTIONS END

//SHOW USER FUNCTIONS BEGIN

function getPostAction($name) {
    $params = func_get_args();

    foreach ($params as $name) {

        if (!empty(filter_input(INPUT_POST, $name, FILTER_SANITIZE_STRING))) {
            return $name;
        }
    }
}

function activateUser($idUser) {
    $user = UserDao::getUserById($idUser);

    //LOGGED USER IS ADM OR TRYING TO ACTIVATE AN NON-ADM USER?
    if (isAdm() && UserDao::getUserById($idUser)->getType() != 0):
        if (!$user->getActive()):
            if (UserDao::updateUserActiveById($user->getId(), true)):
                $_SESSION["activateUser"] = 'activated';
                updateLog('activateUser', UserDao::getUserById($_SESSION["userId"]), $user, true);
                header('Location: ../../controlpanel_functions/result.php');
            else:
                $_SESSION["activateUser"] = 'failure';
                updateLog('activateUser', UserDao::getUserById($_SESSION["userId"]), $user, false);
                header('Location: ../../controlpanel_functions/result.php');
            endif;
        else:
            if (UserDao::updateUserActiveById($user->getId(), false)):
                $_SESSION["activateUser"] = 'deactivated';
                updateLog('activateUser', UserDao::getUserById($_SESSION["userId"]), $user, true);
                header('Location: ../../controlpanel_functions/result.php');
            else:
                $_SESSION["activateUser"] = 'failure';
                updateLog('activateUser', UserDao::getUserById($_SESSION["userId"]), $user, false);
                header('Location: ../../controlpanel_functions/result.php');
            endif;

        endif;
    else:
        $_SESSION["activateUser"] = "noPermission";
        updateLog('activateUser', UserDao::getUserById($_SESSION["userId"]), $user, false);
        header('Location: ../../controlpanel_functions/result.php');
    endif;
}

function resetPass($idUser) {
    $user = UserDao::getUserById($idUser);

    if (isAdm()):
        //TRY TO UPDATE
        if (UserDao::updateUserPassById($user->getId(), md5('0000'))):
            $_SESSION["resetPass"] = "success";
            updateLog('resetPass', UserDao::getUserById($_SESSION["userId"]), $user, true);
            header('Location: ../../controlpanel_functions/result.php');
        else:
            $_SESSION["resetPass"] = "failure";
            updateLog('resetPass', UserDao::getUserById($_SESSION["userId"]), $user, false);
            header('Location: ../../controlpanel_functions/result.php');
        endif;
    else:
        $_SESSION["resetPass"] = "noPermission";
        updateLog('resetPass', UserDao::getUserById($_SESSION["userId"]), $user, false);
        header('Location: ../../controlpanel_functions/result.php');
    endif;
}

function deleteUser($idUser) {
    $user = UserDao::getUserById($idUser);
    //LOGGED USER IS ADM OR TRYING TO DELETE AN NON-ADM USER?
    if (isAdm() && ($user->getType() != 0)):
        //TRY TO DELETE
        if (UserDao::deleteUserById($idUser)):
            $_SESSION["deleteUser"] = "success";
            updateLog('deleteUser', UserDao::getUserById($_SESSION["userId"]), $user, true);
            header('Location: ../../controlpanel_functions/result.php');
        else:
            $_SESSION["deleteUser"] = "failure";
            updateLog('deleteUser', UserDao::getUserById($_SESSION["userId"]), $user, false);
            header('Location: ../../controlpanel_functions/result.php');
        endif;
    else:
        $_SESSION["deleteUser"] = "noPermission";
        updateLog('deleteUser', UserDao::getUserById($_SESSION["userId"]), $user, false);
        header('Location: ../../controlpanel_functions/result.php');
    endif;
}

//SHOW USER FUNCTIONS END