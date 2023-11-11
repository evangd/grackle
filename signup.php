<?php
session_save_path('sessions');
session_start();

require_once 'pdo.php';

if (isset($_POST['signup']) && ($_POST['password'] === $_POST['password2'])) {

    $query = $pdo-prepare('INSERT INTO users 
        (username, password, first_name, last_name, color, last_online)
        VALUES (:un, :pw, :fn, :ln, black, :lo)');
    $query->execute(array(
        ':un' => $_POST['username'],
        ':pw' => $_POST['password'],
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':lo' => time()
    ));

    $_SESSION['id'] = $pdo->lastInsertId();
    $_SESSION['first_name'] = $row['first_name'];
    $_SESSION['last_name'] = $row['last_name'];

    // redundant code? yes

    $query2 = $pdo->prepare('SELECT id FROM messages ORDER BY id DESC LIMIT 1');
    $query2->execute();
    $lastMsg = $query2->fetch(PDO::FETCH_ASSOC);

    $_SESSION['start'] = $lastMsg['id'];

    header('Location: index.php');
    return;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Grackle</title>
    <link href="style.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body>
    <form id="signup" method="POST">
        <h1 class='login-title'>Grackle</h1>
        <fieldset>
            <legend>Your Name</legend>
            <label for="first_name">First</label>
            <input id="first_name" name="first_name" type="text" required><br>
            <label for="last_name">Last</label>
            <input id="last_name" name="last_name" type="text" required>
        </fieldset>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <label for="password2">Confirm Password:</label>
        <input type="password" id="password2" name="password2" required>
        <button type="submit" name="signup">Create Account</button>
    </form>
</body>
</html>