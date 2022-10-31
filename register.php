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
             * {margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif;}
            body {
                background: #666; background-size: cover; min-height: 100vh; display: flex; justify-content: center; align-items: center;
            }
            form {
                background: #eee; padding: 20px; display: inline-flex; flex-direction: column; align-items: center; border-radius: 8px; width: 300px; height: 500px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.8);
            }
            form h2 {margin-top: 20px; font-size: 55px; margin-bottom: 30px; color: #000;}
            form input {
                border: none; background: none; text-align: center; outline: none; padding: 9px; margin: 20px; color: #000; height: 30px; width: 70%; 
                border-radius: 40px; transition: 0.2s ease-in;
            }
            form input[type="text"], form input[type="password"] {border: 2px solid #0ca711;}
            form input[type="submit"] {
                border: 2px solid #0ca711; box-sizing: border-box; height: 50px; width: 40%; cursor: pointer; background: #0ca711;
                color: #fff; font-weight: 600;
            }
            form input[type="text"]:focus, form input[type="password"]:focus {width: 80%; border: 2px solid #0ca711;}
            form input[type="submit"]:focus {background: #0ca711; color: #182c61;}
            form .alreadyAcc {padding-top: 2rem; font-weight: 600;} 
            form .alreadyAcc:hover {color: #0ca711;}
        </style>
    </head>
    <body>
    <form action="" method="POST">
            <h2>SignUp</h2>
            <input type="text" name="username" id="username" placeholder="Username" autocomplete="false">
            <input type="password" name="password" id="password" placeholder="Password">
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password">
            <input type="submit" value="SignUp">   
            <a href="login.php" class="alreadyAcc">Already have Account? LogIn</a>
        </form>
    </body>
</html>
<!-- HTML Ends Here... -->