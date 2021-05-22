# Teliph

A php based framework for quickly setting up small telegram API based bots. It is Lightweight and just needs one single file to be included within the code.


## Usage

```php
copy('https://raw.githubusercontent.com/Ripeey/Teliph/main/Teliph/Teliph.php', 'Teliph.php');

require('Teliph.php');

use Teliph\Filters as Filters;
$bot = new Teliph\Bot('12345:AFsaf-bot_token');

# Method 1 <Filter>
if(Filters::regex('/hi/i'))
{
    $result $bot->sendMessage([
        'chat_id'=> $bot->update()['message']['chat']['id'],
        'text'=> "Hi there! This is response to message."
    ]);
}
# Method 2 <UpdateType, Filter, Callback>
$bot->on('message', Filters::command('start'), function($bot, $message){
    $result = $bot->sendMessage([
        'chat_id'=> $message['chat']['id'],
        'text'=> "Hi there! This is response to command."
    ]);
});
```
## Methods
All **Methods** are exactly same as available in telegram documentation you must read [here](https://core.telegram.org/bots/api#available-methods).

## Update Types
Some common update types are :
* **message** - New incoming message of any kind — text, photo, sticker, etc.
* **channel_post** - New incoming channel post of any kind — text, photo, sticker, etc.
* **inline_query** - New incoming inline query.
* **callback_query** - New incoming callback query.
* **chat_member** - A chat member's status was updated in a chat.
Theres a lot more, all update types are listed in Update object within [telegram documentation](https://core.telegram.org/bots/api#update).
## Filters
* **command** - Checks if text is a /command. Args (command, symbol default /) 
* **regex** - Matches a gievn pattern with the text. Args (pattern)
* **media** - Checks if message contains any media like animation,audio,document,photo,sticker,video,video_note,voice
* **group** - Checks if incomming message is from a group chat.
* **private** - Checks if incomming message is from a private chat.
* **supergroup** - Checks if incomming message is from a supergroup chat.
* **channel** - Checks if incomming message is from a channel.
* **chat** - Check if chat's id|username is same as given id|username. Args (id | username)
* **user** - Check if user's id|username is same as given id|username. Args (id | username)

## Extras FAQs
This framework is recommended to be used for quick and small bot deployments inspired by userneins early version of  phgram with some extra functionalities of events and filters. 

## License
[MIT](https://github.com/Ripeey/Teliph/blob/main/LICENSE)
