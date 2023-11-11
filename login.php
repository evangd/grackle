<?php
    session_save_path('sessions');
    session_start();

    require_once 'pdo.php';

    if (isset($_POST['username']) && isset($_POST['password'])) {

        $stmt = $pdo->prepare('SELECT id, first_name, last_name, color from users
            WHERE username = :un AND password = :pw AND last_online < :time');
        $stmt->execute(array(
            ':un' => $_POST['username'],
            ':pw' => $_POST['password'],
            ':time' => time() - 5
        ));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row !== false) {

            $_SESSION['id'] = $row['id'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];
            $_SESSION['color'] = $row['color'];

            $query = $pdo->prepare('UPDATE users SET last_online = :time WHERE id = :uid');
            $query->execute(array(
                ':time' => time(),
                ':uid' => $_SESSION['id']
            ));

            // Get the most recent message and pull all session messages after that
            // NOTE: I'm just making a dummy message so that I don't have to change this lol

            $stmt2 = $pdo->prepare('SELECT id FROM messages ORDER BY id DESC LIMIT 1');
            $stmt2->execute();
            $lastMsg = $stmt2->fetch(PDO::FETCH_ASSOC);

            $_SESSION['start'] = $lastMsg['id'];

            header('Location: index.php');
            return;
        } else {
            // Need to include error messages
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;700&display=swap" rel="stylesheet">
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
    <p>or</p>
    <a href="signup.php"><button id="create">Create an Account</button></a>
</body>
</html>