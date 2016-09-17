<?php
include '../class/User.class.php';
include '../functions.php';

if(!sessionCheck()):
    //IF WAS BY SESSION TIME OUT
    if($_SESSION["startTime"] == 'timeOut'):
        header('Location: ../../controlpanel_functions/result.php');
    else:
        session_destroy();
        header('Location: ../../index.php');
    endif;
endif;

switch(getPostAction('activateUser', 'resetPass', 'deleteUser')):
    case 'activateUser':
        activateUser(filter_input(INPUT_POST, 'idUser', FILTER_SANITIZE_NUMBER_INT));
        break;
    case 'resetPass':
        resetPass(filter_input(INPUT_POST, 'idUser', FILTER_SANITIZE_NUMBER_INT));
        break;
    case 'deleteUser':
        deleteUser(filter_input(INPUT_POST, 'idUser', FILTER_SANITIZE_NUMBER_INT));
        break;
    default:
        //NO ACTION SET
        break;
endswitch;
