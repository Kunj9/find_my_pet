<?php
require_once(__DIR__ . "/../lib/db_config.php");
require(__DIR__ . "/../lib/flash_messages.php");
require(__DIR__ . "/../lib/safe_echo.php");
require(__DIR__ . "/../partials/nav.php");  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register an Account</title>
    <style>
        body {
            background-color: #FFFAED; 
            margin: 0; 
            height: 100vh;
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            justify-content: center; 
        }

        .form-container {
            background-color: #FFFAFF; 
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3); 
            border-radius: 10px; 
            border: 2px solid #ccc; 
            padding: 40px; 
            width: 400px; 
            text-align: center; 
            margin-top: 20px; 
        }

        .loginlabel {
            display: block; 
            margin-bottom: 10px; 
            font-size: 18px; 
            color: #333; 
        }

        input[type="email"],
        input[type="text"],
        input[type="password"] {
            font-size: 16px;
            padding: 10px; 
            border: 1px solid #ccc; 
            border-radius: 5px; 
            width: 100%; 
            margin-bottom: 15px; 
            box-sizing: border-box; 
        }

        input[type="submit"] {
            font-size: 20px; 
            padding: 10px; 
            background-color: #4CAF50; 
            color: white;
            border: none;
            border-radius: 5px; 
            cursor: pointer;
            width: 100%; 
        }

        input[type="submit"]:hover {
            background-color: #45a049; 
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <form onsubmit="return validate(this)" method="POST">
                <label class="loginlabel" for="email">Email</label>
                <input type="email" name="email" required />
                
                <label class="loginlabel" for="username">Username</label>
                <input type="text" name="username" required maxlength="30"/>

                <label class="loginlabel" for="pw">Password</label>
                <input type="password" id="pw" name="password" required minlength="8" />

                <label class="loginlabel" for="confirm">Confirm</label>
                <input type="password" name="confirm" required minlength="8" />

                <input class="loginlabel" type="submit" value="Register" />
            </form>
        </div>
    </div>

    <script>
        function validate(form) {
           return true;
        }
    </script>

    <?php
    if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["confirm"])) {
        $email = se($_POST, "email", "", false);
        $username = se($_POST, "username", "", false);
        $password = se($_POST, "password", "", false);
        $confirm = se($_POST, "confirm", "", false);
        $hasError = false;

        if (empty($email)) {
            flashMessage("Email must not be empty", "error");
            $hasError = true;
        }

        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flashMessage("Invalid email", "error");
            $hasError = true;
        }
        if (empty($password)) {
            flashMessage("Password must not be empty", "error");
            $hasError = true;
        }
        if (empty($confirm)) {
            flashMessage("Confirm Password must not be empty", "error");
            $hasError = true;
        }
        if (strlen($password) < 8) {
            flashMessage("Password must be >8 characters", "error");
            $hasError = true;
        }
        if ($password !== $confirm) {
            flashMessage("Passwords must match", "error");
            $hasError = true;
        }

        if (!$hasError) {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO Users (email, password, username) VALUES(:email, :password, :username)");
            try {
                $stmt->execute([":email" => $email, ":password" => $hash, ":username" => $username]);
                flashMessage("Successfully registered!", "success");
            } catch (Exception $e) {
                flashMessage("There was a problem registering", "error");
            }
        }
    }
    ?>
    <?php require(__DIR__ . "/../partials/flash.php"); ?>
</body>
</html>
