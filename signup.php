<?php
session_save_path('sessions');
session_start();

require_once 'pdo.php';

if (isset($_POST['signup']) && ($_POST['password'] === $_POST['password2'])) {

    $query = $pdo->prepare('INSERT INTO users 
        (username, password, first_name, last_name, color, last_online)
        VALUES (:un, :pw, :fn, :ln, NULL, :lo)');
    $query->execute(array(
        ':un' => $_POST['username'],
        ':pw' => $_POST['password'],
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':lo' => time()
    ));

    $_SESSION['id'] = $pdo->lastInsertId();
    $_SESSION['first_name'] = $_POST['first_name'];
    $_SESSION['last_name'] = $_POST['last_name'];

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
    <form id="signup" method="POST" name="signup">
        <img src="images/grackle1.svg" alt="Grackle">
        <fieldset>
            <legend>Your Name</legend>
            <input id="first_name" name="first_name" type="text" placeholder="first" aria-label="first" required><br>
            <input id="last_name" name="last_name" type="text" placeholder="last" aria-label="last" required>
        </fieldset>
        <input type="text" id="username" name="username" placeholder="username" aria-label="username" required>
        <input type="password" id="password" name="password" placeholder="password" aria-label="password" required>
        <input type="password" id="password2" name="password2" placeholder="confirm password" aria-label="confirm password" required>
        <button type="submit" name="signup">create account</button>
    </form>
    <script>
        const create = document.querySelector('button[type="submit"]');
        const signup = document.forms['signup'];

        create.addEventListener('click', function(e) {
            if (signup.elements['password'].value !==
                signup.elements['password2'].value) {
                    e.preventDefault();
                    const msg = document.createElement('p');
                    const passLabel = document.querySelector('label[for="password"]');
                    msg.classList.add('error');
                    msg.textContent = 'Passwords must match';
                    signup.insertBefore(msg, passLabel);
                }
        });
    </script>
</body>
</html>