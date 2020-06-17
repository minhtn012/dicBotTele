<?php 

$bot_token = '1193195079:AAFnxQ5TJRoAtJiI1G13K9MzjIyCcqNkQMw';
$webhook_url = "https://afternoon-cove-13889.herokuapp.com/hook.php";
$url_API_Tele = "https://api.telegram.org/bot".$bot_token."/setWebhook?url=".$webhook_url;

$chatId=207829817;  //** ===>>>NOTE: this chatId MUST be the chat_id of a person, NOT another bot chatId !!!**
  $params=[
      'chat_id'=>$chatId,
      'text'=>'This is my message !!!',
  ];
  $ch = curl_init($url_API_Tele . '/sendMessage');
  curl_setopt($ch, CURLOPT_HEADER, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $result = curl_exec($ch);
  curl_close($ch);
