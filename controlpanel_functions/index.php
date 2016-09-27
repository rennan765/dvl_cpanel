<?php
include_once '../app/functions.php';

switch (sessionCheck()):
    case 'userIsLogged':
        header('Location: ../controlpanel.php');
        break;
    case 'userIsNotLogged':
        header('Location: ../index.php');
        break;
    case 'sessionTimeOut':
        header('Location: result.php?resultMessage=timeOut');
        break;
    default:
        header('Location: ../index.php');
        break;
endswitch;
