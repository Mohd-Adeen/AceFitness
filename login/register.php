<?php
require_once "config.php";

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

if ($_SERVER['REQUEST_METHOD'] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Username cannot be blank";
    }
    else{
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt)
        {
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set the value of param username
            $param_username = trim($_POST['username']);

            // Try to execute this statement
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1)
                {
                    $username_err = "This username is already taken"; 
                }
                else{
                    $username = trim($_POST['username']);
                }
            }
            else{
                echo "Something went wrong";
            }
        }
    }

    mysqli_stmt_close($stmt);
    
    // Check for password
    if(empty(trim($_POST['password']))){
        $password_err = "Password cannot be blank";
    }elseif(strlen(trim($_POST['password'])) < 5){
        $password_err = "Password cannot be less than 5 characters";
    }else{
        $password = trim($_POST['password']);
    }
    
    // Check for confirm password field
    if(trim($_POST['password']) !=  trim($_POST['confirm_password'])){
        $password_err = "Passwords should match";
    }
    
    // If there were no errors, go ahead and insert into the database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt){
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Set these parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            // Try to execute the query
            if (mysqli_stmt_execute($stmt)){
                header("location: login.php");
            }else{
                echo "Something went wrong... cannot redirect!";
            }
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
}
?>
<!-- Php Ends Here... -->

<!-- HTML Starts Here... -->
<!DOCTYPE html>
<html>
    <head>
        <title>SignUp</title><meta charset="UTF-8">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700&display=swap');
            * {margin: 0; border: 0; padding: 0; box-sizing: border-box; font-family: "Poppins", sans-serif;}
            body {
                background: #666 no-repeat; min-height: 100vh; min-width: 100vw;
                display: flex; align-items: center; justify-content: center; 
            }
            main.container {
                background: white; min-width: 320px; min-height: 40vh; padding: 2rem; box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.2);
                border-radius: 8px;
            }
            main h2 {font-weight: 600; margin-bottom: 2rem; position: relative;}
            main h2::before {
                content: ''; position: absolute; height: 4px; width: 25px; bottom: 3px; left: 0; border-radius: 8px;
                background: linear-gradient(45deg, #0ca711, #0ca752);
            }
            form {display: flex; flex-direction: column;}
            .input-field {position: relative;}
            form .input-field:first-child {margin-bottom: 1.5rem;}
            .input-field .underline::before {
                content: ''; position: absolute; height: 1px; width: 100%; bottom: -5px; left: 0; background: rgba(0, 0, 0, 0.2);
            }
            .input-field .underline::after {
                content: ''; position: absolute; height: 1px; width: 100%; bottom: -5px; left: 0; background: #0ca711;
                transform: scaleX(0); transition: all .3s ease-in-out; transform-origin: left; 
            }
            .input-field input:focus ~ .underline::after {transform: scaleX(1);}
            .input-field input {outline: none; font-size: 0.9rem; color: rgba(0, 0, 0, 0.7); width: 100%;}
            .input-field input::placeholder {color: rgba(0, 0, 0, 0.5);}
            form input[type="submit"] {
                margin-top: 2rem; padding: 0.4rem; width: 100%; background: #0ca711; cursor: pointer; color: white; 
                font-size: 0.9rem; font-weight: 300; border-radius: 4px; transition: all 0.3s ease;
            }
            form input[type="submit"]:hover {letter-spacing: 0.5px; background: #0ca711;}
        </style>
    </head>
    <body>
        <main class="container">
            <h2>SignUp</h2>
            <form action="" method="POST">
                <div class="input-field">
                    <input type="text" name="username" id="username" placeholder="Enter Your Username">
                    <div class="underline"></div>
                </div>
                <div class="input-field">
                    <input type="password" name="password" id="password" placeholder="Enter Your Password">
                    <div class="underline"></div>
                </div>
                <div class="input-field">
                    <input type="password" class="form-control" name ="confirm_password" id="inputPassword" placeholder="Confirm Password">
                </div>
                <input type="submit" value="SignUp">
            </form>
        </main>
    </body>
</html>
<!-- HTML Ends Here... -->