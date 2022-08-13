<?php
session_start();
    if(!isset($_SESSION["username"])){
        header("location: login.php");
    }
require_once 'includes/functions.inc.php';
require_once 'includes/dbh.inc.php';

$maxGuesses  = 12;                           // max guesses that can be made before game ends
$guesses = $maxGuesses;                     // number of guesses the player has left
$available = "abcdefghijklmnopqrstuvwxyz";   // letters available to be guessed

if (isset($_SESSION["word"]) && !isset($_POST["newgame"])) {
	$word = $_SESSION["word"];    // read previously chosen word from session variable
	$available = $_SESSION["available"];
	$guesses = $_SESSION["guesses"];
} else {
	$word = getRandomWord($conn);   //get a random word from the database
    $_SESSION['word'] = $word;                          // set variables to session variables
    $_SESSION['available'] = $available;
    $_SESSION['guesses'] = $guesses;
	
}
if (isset($_GET["guess"]) && $guesses > 0) {   //"guess" button is pressed and the player has remaining guesses
	$guess = $_GET["guess"];            
	if (preg_match("/$guess/", $available)) {   // not already guessed before; make the guess	                                       
		$available = preg_replace("/$guess/", "", $available); // replace in the available letter string
        $_SESSION['available'] = $available;        //set string to session variable
		
		if (!preg_match("/$guess/", $word )) {   // incorrect guess
			$guesses--;                         // decrement guess count
            $_SESSION['guesses'] = $guesses;    // store into session variable
		}
	}
}


# produce current clue string based on available letters
$clue = preg_replace("/[$available]/", " _ ", $word);


?>

<!DOCTYPE html>
<html>
  <head>
    <title>Hangman</title>
    
  </head>
  
  <body>
    <h1>Welcome to Hangman, <?php echo $_SESSION['username']?>!</h1>
    <div>
        <p> <a href='includes/logout.inc.php'>log out</a></p>
    </div>
    <div>
      (<?= $guesses ?> guesses remaining)
    </div>
    
    <div id="clue"> <?= $clue ?> </div>
    <?php if ($clue != $word && $guesses != 0) { ?>
		<form name="guessForm" action="hangman.php">
			<input name="guess" type="text" size="1" maxlength="1" />
			<input type="submit" value="Guess" />
		</form>
    <?php } ?>




    <?php if ($clue == $word && $guesses > 0) { ?>
    	<div id="win"> Congratulations!  You win! </div>
        <form action="hangman.php" method="post">                   
			<input name="newgame" type="hidden" value="true" />
			<input type="submit" value="Play Again?" />
		</form>
             <table>
        <thead>
            <div class="title center">
                <h3>
                    High Scores Table for <? echo strlen($_SESSION["word"]) ?> Letter Words
                </h3>
            </div>
            <tr>
                <th>Username</td>
                <th>Guesses Used</td>
                <th>Word</td>
            </tr>
        </thead>
        <tbody>
            <?php
                postScore($conn, $_SESSION["username"], $word, $guesses);
                $result = getScoreBoard($conn, strlen($_SESSION["word"]));
                while ($row = $result->fetch_assoc()) {
                    echo "
                <tr>
                    <td>" . $row['username'] . "</td>
                    <td>" . $row["guessesUsed"] . "</td>
                    <td>" . $row["word"] . "</td>
                </tr>
                ";
                }
            ?>
        </tbody>
    </table>
    <?php } ?>

    <?php if ($guesses == 0) { ?>
    	<div id="lose"> Game over! You lost! </div>
        <div>the word was <?php echo $word ?></div>
        <form action="hangman.php" method="post">
			<input name="newgame" type="hidden" value="true" />
			<input type="submit" value="Play Again?" />
		</form>
        <table>
                <thead>
            <div class="title center">
                <h3>
                    High Scores Table for <? echo strlen($_SESSION["word"]) ?> Letter Words
                </h3>
            </div>
            <tr>
                <th>Username</td>
                <th>Guesses Used</td>
                <th>Word</td>
            </tr>
        </thead>
        <tbody>
            <?php
                $result = getScoreBoard($conn, strlen($_SESSION["word"]));
                while ($row = $result->fetch_assoc()) {
                    echo "
                <tr>
                    <td>" . $row['username'] . "</td>
                    <td>" . $row["guessesUsed"] . "</td>
                    <td>" . $row["word"] . "</td>
                </tr>
                ";
                }
            ?>
        </tbody>
    </table>
    <?php } ?>

    <div id="hint">
    	Available letters are: <code>"<?= $available ?>"</code>
    </div>

  </body>
</html>