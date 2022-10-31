<!-- Php Starts Here... -->
<?php
//This script will handle login
session_start();

// check if the user is already logged in
if(isset($_SESSION['username'])){
    header("location: login.php");
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
                        //Redirect user to Home page
                        header("location: Project");
                            
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
        <!-- Font Awesome Link -->
        <script src="https://kit.fontawesome.com/1ab94d0eba.js" crossorigin="anonymous"></script>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700&display=swap');
            * {margin: 0; border: 0; padding: 0; box-sizing: border-box; font-family: "Poppins", sans-serif;}
            body {
                background: linear-gradient(45deg, #8e2de2, #4a00e0); background-repeat: no-repeat; min-height: 100vh; min-width: 100vw;
                display: flex; align-items: center; justify-content: center; 
            }
            main.container {
                background: white; min-width: 320px; min-height: 40vh; padding: 2rem; box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.2);
                border-radius: 8px;
            }
            main h2 {font-weight: 600; margin-bottom: 2rem; position: relative;}
            main h2::before {
                content: ''; position: absolute; height: 4px; width: 25px; bottom: 3px; left: 0; border-radius: 8px;
                background: linear-gradient(45deg, #8e2de2, #4a00e0);
            }
            form {display: flex; flex-direction: column;}
            .input-field {position: relative;}
            form .input-field:first-child {margin-bottom: 1.5rem;}
            .input-field .underline::before {
                content: ''; position: absolute; height: 1px; width: 100%; bottom: -5px; left: 0; background: rgba(0, 0, 0, 0.2);
            }
            .input-field .underline::after {
                content: ''; position: absolute; height: 1px; width: 100%; bottom: -5px; left: 0; background: linear-gradient(45deg, #8e2de2, #4a00e0);
                transform: scaleX(0); transition: all .3s ease-in-out; transform-origin: left; 
            }
            .input-field input:focus ~ .underline::after {transform: scaleX(1);}
            .input-field input {outline: none; font-size: 0.9rem; color: rgba(0, 0, 0, 0.7); width: 100%;}
            .input-field input::placeholder {color: rgba(0, 0, 0, 0.5);}
            form input[type="submit"] {
                margin-top: 2rem; padding: 0.4rem; width: 100%; background: linear-gradient(to left, #4776E6, #8e54e9);
                cursor: pointer; color: white; font-size: 0.9rem; font-weight: 300; border-radius: 4px; transition: all 0.3s ease;
            }
            form input[type="submit"]:hover {letter-spacing: 0.5px; background: linear-gradient(to right, #4776E6, #8e54e9);}
        </style>
    </head>
    <body>
        <main class="container">
            <h2>Login</h2>
            <form action="" method="POST">
                <div class="input-field">
                    <input type="text" name="username" id="username" placeholder="Enter Your Username">
                    <div class="underline"></div>
                </div>
                <div class="input-field">
                    <input type="password" name="password" id="password" placeholder="Enter Your Password">
                    <div class="underline"></div>
                </div>
                <input type="submit" value="LogIn">
            </form>
        </main>
    </body>
</html>