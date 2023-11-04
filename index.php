<?php
session_start();

require_once "pdo.php";

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
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
                <p>Hello there, <strong><?php echo $_SESSION['first_name']; ?></strong>!</p>

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
                    e.preventDefault();
                    send.click();
                }
            } else {
                send.disabled = true;
            }

        })

        send.addEventListener('click', function(e) {
            e.preventDefault();
            $.ajax({
                url: 'post-chat.php',
                type: 'POST',
                data: JSON.stringify({
                    message: chatbar.value,
                    time: new Date().toLocaleTimeString('en-US')
                }),
                contentType: 'application/json; charset=utf-8',
                success: function() {
                    console.log('Post sent!');
                    $('#messages').scrollTop($('#messages')[0].scrollHeight);
                }
            });

            chatbar.value = '';
            $('#messages').scrollTop($('#messages')[0].scrollHeight);
        });

        function getNewChats() {
            $.ajax({
                url: 'get-chat.php',
                cache: false,
                success: function(messages) {
                    $('#messages').empty();
                    for (let i = 0; i < messages.length; ++i) {
                        msg = messages[i];

                        $('#messages').append(`<p><strong> 
                        ${msg['first_name'] + ' ' + msg['last_name']}:</strong>
                         ${msg['message']}<br>${msg['time']}</p>`);
                    }

                    setTimeout(getNewChats(), 4000);
                }
            });
        }

        getNewChats();
    </script>
</body>
</html>