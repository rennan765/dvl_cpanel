<?php
include_once '../functions.php';

session_start();

if($_SESSION["logged"]):
    updateLog('logout', null, null, true);
    session_destroy();
    header('Location: ../../index.php');
else:
    header('Location: ../../index.php');
endif;