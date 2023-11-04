<?php
session_start();

require_once "pdo.php";

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
}

if (isset($_POST['message'])) {

    if (!isset($_SESSION['chat'])) {
        $_SESSION['chat'] = [];
    }

    $_SESSION['chat'][] = array(
        $_SESSION['first_name'] . ' ' . $_SESSION['last_name'],
        $_POST['message'],
        date('g:i a')
    );

    // header('Location: index.php');
    // return;

    // $stmt = $pdo->prepare('INSERT INTO messages (message, user_id) 
    //     VALUES (:message, :user_id)');
    // $stmt->execute(array(
    //     ':message' => $_POST['message'],
    //     ':user_id' => $_SESSION['id']
    // ));

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grackle</title>
    <link href="style.css" rel="stylesheet">
    <script src="jquery-3.7.1.js" type="text/javascript"></script>
</head>
<body>
    <div id="grid-container">
        <div id="sidebar">
            <div id="account">
                <p>Hello there, <?php echo $_SESSION['first_name']; ?>!</p>

            </div>
        </div>
        <div id="messages"></div>
        <form id="chatbar" method="POST" action="index.php">
            <textarea name="message" rows="1" autofocus></textarea>
            <button type="submit" name="send" disabled>Send</button>
        </form>
    </div>
    <script>
        const chatbar = document.querySelector('textarea');
        const send = document.querySelector('button[name="send"]');

        chatbar.addEventListener('keydown', function(e) {

            if (e.target.value !== "") {
                send.disabled = false;
                if (e.key === 'Enter') {
                    send.click();
                }
            } else {
                send.disabled = true;
            }

        })

        send.addEventListener('click', function(e) {
            e.preventDefault();
            console.log(`Click listener fired with value: ${chatbar.value}`);
            $.ajax({
                url: 'chat-out.php',
                type: 'POST',
                data: JSON.stringify({
                    message: chatbar.value,
                    time: Date.now().toString()
                }),
                contentType: 'application/json',
                success: function() {
                    console.log('Post sent!');
                },
                error: function() {
                    console.log('ERROR');
                }
            });

            chatbar.value = '';
        });

        function getNewChats() {
            $.ajax({
                url: 'chat-in.php',
                cache: false,
                success: function(messages) {
                    $('#messages').empty();
                    for (let i = 0; i < messages.length; ++i) {
                        msg = messages[i];

                        $('#messages').append(`<p><strong> ${msg[0]}:</strong>
                         ${msg[1]}<br>${msg[2]}`);
                    }

                    setTimeout(getNewChats(), 4000);
                }
            });
        }

        // getNewChats();
    </script>
</body>
</html>