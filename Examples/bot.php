<?php
copy('https://raw.githubusercontent.com/Ripeey/Teliph/main/Teliph/Teliph.php', 'Teliph.php');

require_once('Teliph.php');

use Teliph\Filters as Filters;
$bot = new Teliph\Bot('12345:AFsaf-bot_token');

# Method 1
if(Filters::regex('/hi/i'))
{
    $bot->sendMessage([
        'chat_id'=> $bot->update()['message']['chat']['id'],
        'text'=> "Hi there! This is response to message."
    ]);
}
# Method 2
$bot->on('message', Filters::command('start'), function($bot, $message){
    $result = $bot->sendMessage([
        'chat_id'=> $message['chat']['id'],
        'text'=> "Hi there! This is response to command."
    ]);
});
?>
