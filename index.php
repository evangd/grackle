<?php
session_start();

require_once "pdo.php";

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
}

if (isset($_POST['send'])) {

    $stmt = $pdo->prepare('INSERT INTO messages (message, user_id) 
        VALUES (:message, :user_id)');
    $stmt->execute(array(
        ':message' => $_POST['message'],
        ':user_id' => $_SESSION['id']
    ));

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Egret</title>
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div id="grid-container">
        <div id="sidebar">
            <div id="account">
                <p>Hello there, <?php echo $_SESSION['first_name']; ?>!</p>

            </div>
        </div>
        <div id="messages">
            <?php
            // obviously will have to join some stuff later
            $stmt = $pdo->prepare('SELECT message FROM messages');
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                echo '<p>'. $row['message'] . '</p>';
            }
            ?>
        </div>
        <form id="chatbar" method="POST">
            <textarea name="message"></textarea>
            <button type="submit" name="send" disabled>Send</button>
        </form>
    </div>
    <script>
        const chatbar = document.querySelector('textarea');
        const send = document.querySelector('button[name="send"]');

        chatbar.addEventListener('keyup', function(e) {

            if (e.target.value !== "") {
                send.disabled = false;
            } else {
                send.disabled = true;
            }

        });
    </script>
</body>
</html>