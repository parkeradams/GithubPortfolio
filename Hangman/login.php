<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log In</title>
    <style>
        body{ font: 14px sans-serif; }
        
    </style>
</head>
<body>
    <div>
        <h2>Log In</h2>
    <?php
    if(isset($_GET["error"])){
        if ($_GET["error"] == "emptyinput"){
            echo "<p>Missing Fields</p>";
        }
        else if ($_GET["error"] == "wrongpassword"){
            echo "<p>incorrect password</p>";
        }
        else if ($_GET["error"] == "usernamedoesntexist"){
            echo "<p>invalid username</p>";
        }
        else if ($_GET["error"] == "stmtfailed"){
            echo "connection error";
        }
        else if ($_GET["error"] == "none"){
            echo "<p>log in successful!</p>";
        }
    }    
    ?>  
        <p>Please log in to your account</p>
        <form action="includes/login.inc.php" method="POST">
            <div>
                <label>Username: </label>
                <input type="text" name="username">
            </div>    
            <div>
                <label>Password: </label>
                <input type="password" name="password">
            </div>
            <div>
                <input type="submit" value="Submit" name="submit">
            </div>
            <p>Need to sign up for an account? <a href="signup.php">Sign up here</a>.</p>
        </form>
    </div>  
</body>
</html>