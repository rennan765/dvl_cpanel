<?php
include_once '../functions.php';

if(!sessionCheck()):
    //IF WAS BY SESSION TIME OUT
    if($_SESSION["startTime"] == 'timeOut'):
        header('Location: ../../controlpanel_functions/result.php');
    else:
        session_destroy();
        header('Location: ../../index.php');
    endif;
endif;

activateUser(filter_input(INPUT_POST, 'idUser', FILTER_SANITIZE_NUMBER_INT));