<?php
session_save_path('sessions');
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <script src="jquery-3.7.1.js" type="text/javascript"></script>
</head>
<body>
    <div id="grid-container">
        <div id="sidebar">
            <div id="account">
                <p>Hello there, <strong><?php echo htmlentities($_SESSION['first_name']); ?></strong>!</p>
                <a href="logout.php" id="logout">Logout</a>
            </div>
            <h3>Who's on?</h3>
            <ul id="users"></ul>
        </div>
        <div id="messages"></div>
        <form id="chatbar" method="POST" action="index.php">
            <textarea name="message" rows="1" maxlength="255" autofocus></textarea>
            <button type="submit" name="send" disabled>Send</button>
        </form>
    </div>
    <script>
        const chatbar = document.querySelector('textarea');
        const send = document.querySelector('button[name="send"]');

        // check to see if send should be enabled
        chatbar.addEventListener('keyup', function(e) {
            if ( e.target.value === '') {
                send.disabled = true;
            } else {
                send.disabled = false;
            }
        });

        chatbar.addEventListener('keydown', function(e) {
            // Still need to make the line break actually show up lol. Might be htmlentities()
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (!send.disabled) send.click();
            }
        });
            

        send.addEventListener('click', function(e) {
            e.preventDefault();
            $.ajax({
                url: 'post-chat.php',
                type: 'POST',
                data: JSON.stringify({
                    message: chatbar.value,
                    time: new Date().toLocaleTimeString('en-US')
                }),
                contentType: 'application/json; charset=utf-8'
            });

            chatbar.value = '';
            send.disabled = true;
        });

        let lastMsg = '<p></p>';

        function getNewChats() {
            $.ajax({
                url: 'get-chat.php',
                cache: false,
                success: function(response) {
                    
                    const numUsers = response[0];
                    const users = response.slice(1, numUsers + 1);
                    const messages = response.slice(numUsers + 1);
                    
                    $('#messages').empty();
                    for (let i = 0; i < messages.length; ++i) {
                        let msg = messages[i];

                        $('#messages').append(`<p><strong> 
                        ${msg['first_name'] + ' ' + msg['last_name']}:</strong>
                         ${msg['message']}<br><span class="timestamp">${msg['time']}</span></p>`);
                    }

                    console.log($('#messages').childElementCount);

                    if ($('#messages')[0].childElementCount > 0) {
                        console.log('kids!');
                        const newLast = document.querySelector('#messages p:last-child');

                        if (lastMsg.innerHTML !== newLast.innerHTML) {
                            $('#messages').scrollTop($('#messages')[0].scrollHeight);
                            lastMsg = newLast;
                        }
                    }

                    // update user list

            
                    $('#users').empty();

                    if (numUsers > 0 ) {
                        for (let i = 0; i < users.length; ++i) {
                            let user = users[i];

                            $('#users').append(`<li>${user['first_name']} ${user['last_name']}</li>`);
                        }
                    } else {
                        $('#users').append('<li>Just you!</li>');
                    }
        
                    setTimeout(getNewChats, 10);
                }
            });
        }

        function heartbeat() {
            $.ajax({
                url: 'heartbeat.php',
                cache: 'false',
                success: function() {
                    setTimeout(heartbeat, 1500);
                }
            });
        }

        heartbeat();
        getNewChats();
    </script>
</body>
</html>