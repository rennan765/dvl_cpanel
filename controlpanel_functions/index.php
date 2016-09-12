<?php

include '../app/functions.php';
include '../app/class/User.class.php';

session_start();
if (!$_SESSION['logged']):   //If user is not logged, then return to the index.php
    header('Location: ../index.php');
else:
    session_cache_expire(10);
    header('Location: ../controlpanel.php');
endif;

