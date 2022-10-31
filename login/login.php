<!-- Php Starts Here... -->
<?php
//This script will handle login
session_start();

// check if the user is already logged in
if(isset($_SESSION['username'])){
    header("location: register.php");
    exit;
}
require_once "config.php";

$username = $password = "";
$err = "";

// if request method is post
if ($_SERVER['REQUEST_METHOD'] == "POST"){
    if(empty(trim($_POST['username'])) || empty(trim($_POST['password']))){
        $err = "Please enter username + password";
    }else{
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
    }
    if(empty($err)){
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        $param_username = $username;
        // Try to execute this statement
        if(mysqli_stmt_execute($stmt)){
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt) == 1){
                mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                if(mysqli_stmt_fetch($stmt)){
                    if(password_verify($password, $hashed_password)){
                        // this means the password is corrct. Allow user to login
                        session_start();
                        $_SESSION["username"] = $username;
                        $_SESSION["id"] = $id;
                        $_SESSION["loggedin"] = true;
                        //Redirect user to welcome page
                        header("location: register.php");
                            
                    }
                }
            }
        }   
    }    
}

?>
<!-- Php Ends Here... -->

<!-- HTML Starts Here... -->
<!DOCTYPE html>
<html>
    <head>
        <title>LogIn</title><meta charset="UTF-8">
        <style>
            * {margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif;}
            body {
                background: #262626 url('https://c4.wallpaperflare.com/wallpaper/969/697/87/square-shapes-black-dark-wallpaper-preview.jpg');
                background-size: cover; min-height: 100vh; display: flex; justify-content: center; align-items: center;
            }
            form {
                background: #262626; padding: 20px; display: inline-flex; flex-direction: column; align-items: center; border-radius: 8px; width: 300px; height: 400px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.8);
            }
            form h2 {margin-top: 20px; font-size: 55px; margin-bottom: 30px; color: #fff;}
            form input {
                border: none; background: none; text-align: center; outline: none; padding: 10px; margin: 20px; color: #fff; height: 30px; width: 70%; 
                border-radius: 40px; transition: 0.2s ease-in;
            }
            form input[type="text"], form input[type="password"] {border: 2px solid #1b9fcf;}
            form input[type="submit"] {border: 2px solid #55e6c1; box-sizing: border-box; height: 50px; width: 40%; cursor: pointer;}
            form input[type="text"]:focus, form input[type="password"]:focus {width: 80%; border: 2px solid #55e6c1;}
            form input[type="submit"]:focus {background: #55e6c1; color: #182c61;}
        </style>
    </head>
    <body>
        <form action="" method="POST">
            <h2>LogIn</h2>
            <input type="text" name="email-log" placeholder="E-Mail" autocomplete="false">
            <input type="password" name="password-log" placeholder="PassWord">
            <input type="submit" value="Submit">   
        </form>
    </body>
</html>