<?php 
if(isset($_POST['submit'])){
    require 'database.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

    //check if there are empty fields
    if(empty($username) || empty($password)){
        header("Location: ../index.php?error=emptyfields");
        exit();
    }else{
        $sql = "SELECT * FROM users WHERE username = ?";
        $statement = mysqli_stmt_init($connection);
        if(!mysqli_stmt_prepare($statement, $sql)){
            header("Location: ../index.php?error=sqlerror");
        }else{
            mysqli_stmt_bind_param($statement, "s", $username);
            mysqli_stmt_execute($statement);
            $result = mysqli_stmt_get_result($statement);

            if($row = mysqli_fetch_assoc($result)){
                $passCheck = password_verify($password, $row['password']);
                if($passCheck == false){
                    header("Location: ../index.php?error=wrongpassword");
                    exit();
                }elseif($passCheck == true){
                    session_start();
                    $_SESSION['sessionId'] = $row['id'];
                    $_SESSION['sessionUser'] = $row['username'];
                    header("Location: ../index.php?success=loggedin");
                    exit();
                }else{
                    header("Location: ../index.php?error=nouser");
                    exit();
                }
            }else{
                header("Location: ../index.php?error=nouser");
                exit();
            }
        }
    }

}else{
    header("Location: ../index.php?error=accessforbidden");
    exit();
}
?>