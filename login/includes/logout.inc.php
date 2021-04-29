<?php 
    session_start(); 

    session_unset();
    session_destroy();
    //set user to offline here
    header("location: /login/login.php");