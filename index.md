## Welcome to my Github Portfolio!

This is a portfolio of my projects, originally created for CS 3750 and Weber State Univserity and currently only includes assignments from that class.

In this class, my group created:
 - [A hangman game entirely in PHP](#-Hangman)
 - [A banking app that uses .NET, a database, and a login system that uses password hashing and a salt](#-Banking-App)
 - [A stock investing practice website that uses asynchronous communication through Web Sockets](#-Stock-Investing-Practice)
 - [Nerdle, a "Boggle" like game that allows two players to play at the same time](#-Nerdle)

# Hangman
https://github.com/parkeradams/Hangman-Game

this hangman game was my first project created in PHP. It had a functiona login system with error feedback, used session variables to allow the user to guess a letter, and used the database to store words and keep a high score table.

### Game play page
![Screen Shot 2022-08-13 at 9 01 28 AM](https://user-images.githubusercontent.com/92191495/184499706-f6e24638-4246-46fa-919e-00dafac542e9.png)

### results screen with score board
![Screen Shot 2022-08-13 at 9 05 28 AM](https://user-images.githubusercontent.com/92191495/184499841-bfcfc32a-8f19-43b4-9337-f195117bbaee.png)

### code where the user is created and password is salted
```php
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
```

# Banking App
back end https://github.com/n8pickle/bankproject
front end https://github.com/n8pickle/bankproject-ui

This banking app used .NET to simulate an app having transactions between accounts. It used bankers rounding and integers to make sure the numbers were accurate

### Screenshot of the app running
withdrawal and deposit to the account
![Screen Shot 2022-08-13 at 9 16 57 AM](https://user-images.githubusercontent.com/92191495/184500335-857082d7-60c3-4f60-8719-bf40859309b8.png)


### sample code of Deleting and gettin transactions from the database
```C#
    public async Task DeleteTransactionById(int transactionId){
        //get transaction
        var dbTransaction = await _dbContext.Transaction.Where((a) => a.Id == transactionId).FirstOrDefaultAsync();
        //throw exception if its null
        if (dbTransaction == null){
            throw new Exception("The Transaction could not be found");
        }
        // "delete" the transaction
        dbTransaction.Deleted = 1;
        //save changes
        await _dbContext.SaveChangesAsync();
    }

    public async Task<Transaction> GetTransactionById(int transactionId){
        //get transaction
        var dbTransaction = await _dbContext.Transaction.Where((a) => a.Id == transactionId).FirstOrDefaultAsync();
        //throw exception if its null
        if (dbTransaction == null){
            throw new Exception("The Transaction could not be found");
        }
        //return the transaction
        return dbTransaction;
    }

```
# Stock Investing Practice
back end https://github.com/riv-2009/StockInvesting
front end https://github.com/riv-2009/stock-investing-ui

This app lets the user guess the price of a stock over the course of a 7 days and buy and sell accordingly. It has a slider, circle picker, and manual text entry.

### screnshots of the game
![Screen Shot 2022-08-13 at 9 40 02 AM](https://user-images.githubusercontent.com/92191495/184501063-21f1608b-576c-4561-aec0-91732b0c7922.png)

![Screen Shot 2022-08-13 at 9 45 16 AM](https://user-images.githubusercontent.com/92191495/184501207-76448b0c-f9a9-48ad-ad8b-03e7073e2f20.png)

### Sample code wehre we retrieve the stock information
```C#
public async Task StockTickerMessage(string message)
        {
            //Returns a random day between (august 1, 2020 and febuary 5, 2022)
            RandomDay randomDay = new RandomDay();
            DateTime date = randomDay.GetRandomDay();

            string url = $"https://api.polygon.io/v2/aggs/ticker/" +
                $"{message.ToUpper()}/range/1/day/{date.ToString("yyyy-MM-dd")}/" +
                $"{date.AddDays(14).ToString("yyyy-MM-dd")}?apiKey=IIrxKiZhCrJaV0LKZpUVYatFWopiLbxO";

            var client = new RestClient(url);
            var request = new RestRequest();
            var response = await client.GetAsync(request);

            message = response.Content;

            await Clients.All.ReceiveMessage(message);
        }
```

# Nerdle
back end https://github.com/n8pickle/word-grid-back-end
front end https://github.com/DenverJensen/word-grid-ui


This game is the boggle clone that my group worked on. It uses signalR to send messages asynchronously 

### Screenshot of the gameplay with two players
![Screen Shot 2022-08-13 at 9 53 47 AM](https://user-images.githubusercontent.com/92191495/184501534-82ecaf2b-31c5-4ea5-ae21-33e9353fb252.png)


### sample code of the wordbank creation in the database
```C#
    public async Task ImportAllWordsToDb() {
        const Int32 BufferSize = 128;
        using (var fileStream = File.OpenRead(@"./words_alpha.txt")) {
            using (var streamReader = new StreamReader(fileStream, Encoding.UTF8, true, BufferSize)){
                String line = null;
                while((line = streamReader.ReadLine()) != null) {
                    var word = new DictionaryWord();
                    word.Word = line;
                    await _dbContext.Words.AddAsync(word);
                    await _dbContext.SaveChangesAsync();
                }
            }
        }
    }
```
