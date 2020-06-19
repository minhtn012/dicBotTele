<?php
/**
 * Telegram Bot example.
 *
 * @author Gabriele Grillo <gabry.grillo@alice.it>
 */
require_once '../vendor/autoload.php';
// include_once 'Telegram.php';
use GuzzleHttp\Client;
use GuzzleHttp\Stream\Stream;

// Set the bot TOKEN
$bot_token = '1193195079:AAFnxQ5TJRoAtJiI1G13K9MzjIyCcqNkQMw';
// Instances the class
$telegram = new Telegram($bot_token);

/* If you need to manually take some parameters
*  $result = $telegram->getData();
*  $text = $result["message"] ["text"];
*  $chat_id = $result["message"] ["chat"]["id"];
*/

// Take text and chat_id from the message
$text = $telegram->Text();
$chat_id = $telegram->ChatID();

 $client = new Client([
    // Base URI is used with relative requests
    'base_uri' => 'https://od-api.oxforddictionaries.com/api/v2/entries/en-us/',
    // You can set any number of default request options.
    'timeout'  => 5.0,
    'headers' => [
        'app_id' => 'c4ae583c',
        'app_key' => '10740a00580c552c0e4c89e12ccd448b'
    ]
]);


$responseRaw = $client->request('GET', $text)->getBody();
$test = json_decode($responseRaw);
$results = $test->results;

foreach ($results as $key => $result) {
    $lexicalEntries = $result->lexicalEntries;
    foreach ($lexicalEntries as $key => $lexicalEntry) {
        $entries = $lexicalEntry->entries;
        foreach ($entries as $key => $entry) {
            $pronunciations = $entry->pronunciations[0]->phoneticSpelling;
            $senses = $entry->senses[0]->shortDefinitions[0];
            $example = $entry->senses[0]->examples[0]->text;

            $textPronunciations = '- IPA: ' . $pronunciations;
            $textShortDefinitions = ' - Definition: ' . $senses;
            $textEx = '- Ex: ' . $example;
            $text = $textPronunciations . "\n" . $textShortDefinitions . "\n" . $textEx;
            $content = ['chat_id' => $chat_id, 'text' => $text];
            $telegram->sendMessage($content);
        }
    }
}
exit;
$content1 = ['chat_id' => $chat_id, 'text' => utf8_encode($responseRaw)];
        $telegram->sendMessage($content1);
        
$response = json_decode($responseRaw);

if ($response->error) {
    $text = $response->error;
    $content = ['chat_id' => $chat_id, 'text' => $text];
        $telegram->sendMessage($content);
}

if ($response->results) {
    $lexicalEntries = $response->results->lexicalEntries;
    foreach ($lexicalEntries as $key => $value) {
        $lexicalCategory = $value->lexicalCategory;
        $entries = $value->entries;
        $spelling = $entries->pronunciations->phoneticSpelling;
        $senses = $entries->senses->shortDefinitions;
        $text1 = $text . ' - ' . $lexicalCategory . ' - ' . '$spelling';
        $text2 = $senses;

        $content1 = ['chat_id' => $chat_id, 'text' => $text1];
        $telegram->sendMessage($content1);

        $content2 = ['chat_id' => $chat_id, 'text' => $text2];
        $telegram->sendMessage($content2);
    }

}
// Test CallBack
$callback_query = $telegram->Callback_Query();
if ($callback_query !== null && $callback_query != '') {
    $reply = 'Callback value '.$telegram->Callback_Data();
    $content = ['chat_id' => $telegram->Callback_ChatID(), 'text' => $reply];
    $telegram->sendMessage($content);

    $content = ['callback_query_id' => $telegram->Callback_ID(), 'text' => $reply, 'show_alert' => true];
    $telegram->answerCallbackQuery($content);
}

//Test Inline
$data = $telegram->getData();
if ($data['inline_query'] !== null && $data['inline_query'] != '') {
    $query = $data['inline_query']['query'];
    // GIF Examples
    if (strpos('testText', $query) !== false) {
        $results = json_encode([['type' => 'gif', 'id'=> '1', 'gif_url' => 'http://i1260.photobucket.com/albums/ii571/LMFAOSPEAKS/LMFAO/113481459.gif', 'thumb_url'=>'http://i1260.photobucket.com/albums/ii571/LMFAOSPEAKS/LMFAO/113481459.gif']]);
        $content = ['inline_query_id' => $data['inline_query']['id'], 'results' => $results];
        $reply = $telegram->answerInlineQuery($content);
    }

    if (strpos('dance', $query) !== false) {
        $results = json_encode([['type' => 'gif', 'id'=> '1', 'gif_url' => 'https://media.tenor.co/images/cbbfdd7ff679e2ae442024b5cfed229c/tenor.gif', 'thumb_url'=>'https://media.tenor.co/images/cbbfdd7ff679e2ae442024b5cfed229c/tenor.gif']]);
        $content = ['inline_query_id' => $data['inline_query']['id'], 'results' => $results];
        $reply = $telegram->answerInlineQuery($content);
    }
}

// Check if the text is a command
if (!is_null($text) && !is_null($chat_id)) {
    if ($text == '/test') {
        if ($telegram->messageFromGroup()) {
            $reply = 'Chat Group';
        } else {
            $reply = 'Private Chat';
        }
        // Create option for the custom keyboard. Array of array string
        $option = [['A', 'B'], ['C', 'D']];
        // Get the keyboard
        $keyb = $telegram->buildKeyBoard($option);
        $content = ['chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => $reply];
        $telegram->sendMessage($content);
    } elseif ($text == '/git') {
        $reply = 'Check me on GitHub: https://github.com/Eleirbag89/TelegramBotPHP';
        // Build the reply array
        $content = ['chat_id' => $chat_id, 'text' => $reply];
        $telegram->sendMessage($content);
    } elseif ($text == '/img') {
        // Load a local file to upload. If is already on Telegram's Servers just pass the resource id
        $img = curl_file_create('test.png', 'image/png');
        $content = ['chat_id' => $chat_id, 'photo' => $img];
        $telegram->sendPhoto($content);
        //Download the file just sended
        $file_id = $message['photo'][0]['file_id'];
        $file = $telegram->getFile($file_id);
        $telegram->downloadFile($file['result']['file_path'], './test_download.png');
    } elseif ($text == '/where') {
        // Send the Catania's coordinate
        $content = ['chat_id' => $chat_id, 'latitude' => '37.5', 'longitude' => '15.1'];
        $telegram->sendLocation($content);
    } elseif ($text == '/inlinekeyboard') {
        // Shows the Inline Keyboard and Trigger a callback on a button press
        $option = [
                [
                $telegram->buildInlineKeyBoardButton('Callback 1', $url = '', $callback_data = '1'),
                $telegram->buildInlineKeyBoardButton('Callback 2', $url = '', $callback_data = '2'),
                ],
            ];

        $keyb = $telegram->buildInlineKeyBoard($option);
        $content = ['chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => 'This is an InlineKeyboard Test with Callbacks'];
        $telegram->sendMessage($content);
    }
}
