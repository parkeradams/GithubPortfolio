<?php

if (isset($_POST["submit"])){
     $username = $_POST["username"];
     $password = $_POST["password"];
     $repeatPassword = $_POST["repeatPassword"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    if (emptyInputSignup($username, $password, $repeatPassword) !== false){
        header("location: ../signup.php?error=emptyinput");
        exit();
    }

    if (passwordMatch($password, $repeatPassword) !== false){
        header("location: ../signup.php?error=nonmatchingpasswords");
        exit();
    }

    if (usernameExists($conn, $username) !== false){
        header("location: ../signup.php?error=usernametaken");
        exit();
    }

    createUser($conn, $username, $password);
}
else{
    header("location: ../signup.php");
    exit();
}