<?php
include 'Telegram.php';
$bot_token = '1193195079:AAFnxQ5TJRoAtJiI1G13K9MzjIyCcqNkQMw';
$telegram = new Telegram($bot_token);
$text = $telegram->Text();
$chat_id = $telegram->ChatID();
$content = array('chat_id' => $chat_id, 'text' => 'Hello');
$telegram->sendMessage($content);