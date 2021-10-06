<?php
if (isset($_POST['submit'])) {
    //ADD A DATABASE CONNECTION HERE IN THIS SCRIPT
    require 'database.php';

    $username = $_POST['username']; //sets the username inputted from the page to this variable
    $password = $_POST['password']; //sets the password from the web page to this variable 
    $confirmPassword = $_POST['confirmPassword']; //sets the confirmed password variable

    if (empty($username) || empty($password) || empty($confirmPassword)) {
        header("Location: ../register.php?error=emptyfield&username" . $username);
        exit(); //to jump out of the code to execute anything else being executed
    } else if (!preg_match("/^[a-zA-Z0-9]*/", "$username")) {
        //preg_match searches in a string for a pattern and returns true or false if it exists..takes two params
        header("Location: ../register.php?error=invalidusername&username" . $username);
        exit();
    } elseif ($password !== $confirmPassword) {
        header("Location: ../register.php?error=passwordsdonotmatch&username" . $username);
        exit();
    } else {
        
        $sql = "SELECT username FROM users WHERE username = ?";
        $statement = mysqli_stmt_init($connection); //initialises a statement and returns an object to use the function mysqli_stmt_prepare()... takes 1 param

        if (!mysqli_stmt_prepare($statement, $sql)) {
            header("Location: ../register.php?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($statement, "s", $username); //bind 
            mysqli_stmt_execute($statement); //statement to execute
            mysqli_stmt_store_result($statement); //stores result from the database and stores them at $statement
            $rowsCount = mysqli_stmt_num_rows($statement);

            if ($rowsCount > 0) {
                header("Location: ../register.php?error=usernametaken");
                exit();
            } else {
                $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
                $statement = mysqli_stmt_init($connection); //initialises a statement and returns an object to use the function mysqli_stmt_prepare()... takes 1 param
                if (!mysqli_stmt_prepare($statement, $sql)) {
                    header("Location: ../register.php?error=sqlerror");
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    mysqli_stmt_bind_param($statement, "ss", $username, $hashedPassword);
                    mysqli_stmt_execute($statement);
                    header("Location: ../register.php?success=registered");
                    exit();
                }
            }
        }
    }
    mysqli_stmt_close($statement);
    mysqli_close($connection);
}
