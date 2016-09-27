<?php
include_once '../functions.php';

switch (sessionCheck()):
    case 'userIsLogged':
        //NO ACTION SET
        break;
    case 'userIsNotLogged':
        header('Location: ../../index.php');
        break;
    case 'sessionTimeOut':
        header('Location: ../../controlpanel_functions/result.php?resultMessage=timeOut');
        break;
    default:
        //NO ACTION SET
        break;
endswitch;

resetPass(filter_input(INPUT_POST, 'idUser', FILTER_SANITIZE_NUMBER_INT));