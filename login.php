<?php
    session_start();

    require_once 'pdo.php';

    if (isset($_POST['username']) && isset($_POST['password'])) {

        $stmt = $pdo->prepare('SELECT id, first_name, last_name, color from users
            WHERE username = :un AND password = :pw');
        $stmt->execute(array(':un' => $_POST['username'], ':pw' => $_POST['password']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row !== false) {

            $_SESSION['id'] = $row['id'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];
            $_SESSION['color'] = $row['color'];

            header('Location: index.php');
            return;
        } else {
            $_SESSION['No account found. Nice try, pal.'];
            header('Location: login.php');
            return;
        }

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Grackle</title>
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <form id="login" method="POST">
        <h1 class='login-title'>Grackle</h1>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
        <button type="submit" name="login">Log In</button>
    </form>
</body>
</html>