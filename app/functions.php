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
                header('Location: ../../controlpanel_functions/result.php');
            else:
                $_SESSION["activateUser"] = 'failure';
                header('Location: ../../controlpanel_functions/result.php');
            endif;
        else:
            if (UserDao::updateUserActiveById($user->getId(), false)):
                $_SESSION["activateUser"] = 'deactivated';
                header('Location: ../../controlpanel_functions/result.php');
            else:
                $_SESSION["activateUser"] = 'failure';
                header('Location: ../../controlpanel_functions/result.php');
            endif;

        endif;
    else:
        $_SESSION["activateUser"] = "noPermission";
        header('Location: ../../controlpanel_functions/result.php');
    endif;
}

function resetPass($idUser) {
    $user = UserDao::getUserById($idUser);

    if (isAdm()):
        //TRY TO UPDATE
        if (UserDao::updateUserPassById($user->getId(), md5('0000'))):
            $_SESSION["resetPass"] = "success";
            header('Location: ../../controlpanel_functions/result.php');
        else:
            $_SESSION["resetPass"] = "failure";
            header('Location: ../../controlpanel_functions/result.php');
        endif;
    else:
        $_SESSION["resetPass"] = "noPermission";
        header('Location: ../../controlpanel_functions/result.php');
    endif;
}

function deleteUser($idUser) {
    //LOGGED USER IS ADM OR TRYING TO DELETE AN NON-ADM USER?
    if (isAdm() && UserDao::getUserById($idUser)):
        //TRY TO DELETE
        if (UserDao::deleteUserById($idUser)):
            $_SESSION["deleteUser"] = "success";
            header('Location: ../../controlpanel_functions/result.php');
        else:
            $_SESSION["deleteUser"] = "failure";
            header('Location: ../../controlpanel_functions/result.php');
        endif;
    else:
        $_SESSION["deleteUser"] = "noPermission";
        header('Location: ../../controlpanel_functions/result.php');
    endif;
}

//SHOW USER FUNCTIONS END

//SEND E-MAIL FUNCTION

function emailForgotPass($email){
    session_start();
    $message = "";
    if(sendEmail($email, $message)):
        $_SESSION["sendEmail"] = 'success';
        header('Location: controlpanel_functions/result.php');
    else:
        $_SESSION["sendEmail"] = 'failure';
        header('Location: controlpanel_functions/result.php');
    endif;
}

function sendEmail($email, $message){
    return true;
}