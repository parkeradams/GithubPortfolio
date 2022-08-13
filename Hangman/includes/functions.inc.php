<?php

function emptyInputSignup($username, $password, $repeatPassword) {

    $result;

    if (empty($username) || empty($password) || empty($repeatPassword)){
        $result = true;
    } else {
        $result = false;
    }

    return $result;
}

function passwordMatch($password, $repeatPassword){
    $result;
    if ($password !== $repeatPassword){
        $result = true;
    }
    else {
        $result = false;
    }

    return $result;
}

function usernameExists($conn, $username) {
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)){
        return $row;
    }
    else{
        $result = false;
        return $result;
    }

    mysqli_stmt_close($stmt);
}

function createUser($conn, $username, $password){
    $sql = "INSERT INTO users (username, salt, hPassword) VALUES (?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }
    //generate salt and hash password here
    $salt = random_bytes(10);
    $passSalt = $password . $salt;
    $hashPassword = password_hash($passSalt, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sss", $username, $salt, $hashPassword);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    loginUser($conn, $username, $password);
    exit();

 }

 function emptyInputLogin($username, $password){
     $result;
     if(empty($username) || empty($password)){
         $result = true;
     }
     else{
         $result = false;
     }
     return $result;
 }

 function loginUser($conn, $username, $password){
     $userExists = usernameExists($conn, $username);

    if ($userExists === false){
        header("location: ../login.php?error=usernamedoesntexist");
        exit();
    }

    $hashPassword = $userExists["hPassword"];
    $passSalt = $password . $userExists["salt"];

    $checkPassword = password_verify($passSalt, $hashPassword);

    if ($checkPassword === false){
        header("location: ../login.php?error=wrongpassword");
    }
    else if($checkPassword === true){
        session_start();
        $_SESSION["userid"] = $userExists["userKey"]; 
        $_SESSION["username"] = $userExists["username"]; 
        //send the user to the game
        header("location: ../hangman.php");
        exit();
    }
 }

 function getRandomWord($conn){
    $sql = "SELECT * FROM words";
      $results = $conn->query($sql);
      if($results->num_rows > 0){
          while($row = $results->fetch_assoc()){
              $words[] = $row['word'];
          }
      }
    
    $index = rand(0,count($words));
    $word = $words[$index];

    if ($word == ""){
        $word = "default";
    }
    return $word;

 }

 function postScore($conn, $username, $word, $guesses){
    $guessesUsed = 12 - $guesses; 
    $sql = "INSERT INTO `scoreboard` (`scoreKey`, `username`, `guessesUsed`, `word`, `wordLen`) VALUES (NULL, '".$username."', ".$guessesUsed.", '".$word."', ".strlen($word).")";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../hangman.php?error=stmtfailed");
        exit();
    }
    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return;

 }

 function getScoreBoard($conn, $wordLen){
    $sql ="SELECT * FROM scoreboard WHERE wordLen = ".$wordLen." ORDER BY guessesUsed ASC LIMIT 10";
    $result = $conn->query($sql);
    return $result;
 }