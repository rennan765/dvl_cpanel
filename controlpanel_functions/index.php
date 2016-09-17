<?php

include '../app/functions.php';
include '../app/class/User.class.php';

session_start();
if (!sessionCheck()):   //If user is not logged, then return to the index.php
    header('Location: ../index.php');
else:
    header('Location: ../controlpanel.php');
endif;

