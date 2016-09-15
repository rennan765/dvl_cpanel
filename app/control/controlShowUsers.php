<?php
include '../class/User.class.php';
include '../functions.php';

if(!sessionCheck()):
    header('Location: ../../index.php');
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
