 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div>
        <h2>Sign Up</h2>
        <?php
    if(isset($_GET["error"])){
        if ($_GET["error"] == "emptyinput"){
            echo "<p>Missing Fields</p>";
        }
        else if ($_GET["error"] == "nonmatchingpasswords"){
            echo "<p>passwords dont match</p>";
        }
        else if ($_GET["error"] == "usernametaken"){
            echo "<p>username taken</p>";
        }
        else if ($_GET["error"] == "stmtfailed"){
            echo "connection error";
        }
        else if ($_GET["error"] == "none"){
            echo "<p>sign up successful!</p>";
            header("location: hangman.php");
        }
    }
        ?>
        <p>Please fill this form to create an account.</p>
        <form action="includes/signup.inc.php" method="POST">
            <div>
                <label>Username: </label>
                <input type="text" name="username" >
                
            </div>    
            <div>
                <label>Password: </label>
                <input type="password" name="password">
            </div>
            <div>
                <label>Confirm Password: </label>
                <input type="password" name="repeatPassword">
            </div>
            <div>
                <input type="submit" name="submit" value="Submit">
                <input type="reset" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
    
</body>


</html>