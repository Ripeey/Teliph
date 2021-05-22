<?php
/**
* A Telegram Bot API framework in PHP 
* Lite version based on usernein/phgram early version 
* 
* @package    Teliph
* @author     @Ripeey (Github)
*/

namespace Teliph;

class Bot
{
	# api endpoint
	private const api_endpoint = "https://api.telegram.org/bot";
	
	# bot token
	private $bot_token = '';
	
	# update data
	static protected $update_data = '';
	
	# update type
	static protected $update_type = '';

	/**
       * 
       * Initialize bot and setup vars
       *
       * @param bot_token string
       */
	public function __construct(string $bot_token)
	{
	$this->bot_token = $bot_token;
	$this->api_receive();
	}
	
	/**
       * 
       * Initialize for calling bot api methods
       *
       * @param bot_token string
       */
	public function __call(string $method, array $args = [[]])
	{
		$url = self::api_endpoint.$this->bot_token.'/'.$method;
		$response = $this->api_request($url, $args[0]);	
		return $response;
	}
	
	/**
       * 
       * Update json string
       *
       * @return update json string
       */
	public function __toString()
	{
		return json_encode($this->update());
	}
	
	/**
       * 
       * Requests to bot api
       *
       * @param string $url final endpoint url
       * @param array $payload post args
       * @return response array []
       */
	private function api_request(string $url, array $payload = [])
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json'])
		$response = curl_exec($ch);
		curl_close($ch);
		return json_decode($response, true);
	}
	
	/**
       * 
       * Receives update from webhook
       *
       * @return update array []
       */
	private function api_receive()
	{
		return $this->update(json_decode(file_get_contents('php://input'), true));
	}
	
	/**
       * 
       * Setup or Fetch update data
       *
       * @param update_data array
       * @return update array []
       */
	public function update(array $update_data = [])
	{
		if($update_data)
		{
			self::$update_data = $update_data;
			self::$update_type = array_keys(self::$update_data)[1];
		}
		
		return self::$update_data;
	}
	
	/**
       * 
       * Setup event callback and filters
       *
       * @param string update_type 
       * @param boolean filters 
       * @param callable callback
       */
	public function on(string $update_type, bool $filter, callable $callback)
	{
		if(isset($this->update()[$update_type]) && $filter) $callback($this, $this->update()[$update_type]);
	}
}

/**
  * Filters class for handling callbacks or control flows
  *
  * @package    Teliph
  * @author     @Ripeey (Github)
  */

class Filters extends Bot
{
	/**
       * 
       * Filters to check the message text is a command
       *
       * @param string payload command text
       * @param string symbol prexfix of command
       * @return boolean
       */
	public static function command(string $payload, string $symbol = '/')
	{
		if(isset(parent::$update_data[parent::$update_type]['text']))
			return stripos(parent::$update_data[parent::$update_type]['text'], $symbol.$payload) !== false;
		else
			return false;
	}
	/**
       * 
       * Filters to match the message, callback and inlinequery with given regex pattern
       *
       * @param string pattern to match
       * @return boolean
       */
	public static function regex(string $pattern)
	{
		$keyload = array_intersect(['text','caption','query','data'], array_keys(parent::$update_data[parent::$update_type]));
		if($keyload)
			return preg_match($pattern, parent::$update_data[parent::$update_type][current($keyload)]);
		else
			return false;
	}
	/**
       * 
       * Filters to match the message contains a media
       *
       * @return boolean
       */
	public static function media()
	{
		if(array_intersect(['animation', 'audio', 'document', 'photo', 'sticker', 'video', 'video_note', 'voice'], array_keys(parent::$update_data[parent::$update_type])))
			return true;
		else
			return false;
	}
	/**
       * 
       * Filters to check the type of chat, can be either “private”, “group”, “supergroup” or “channel”
       *
       * @return boolean
       */
	public static function group()
	{
		if(isset(parent::$update_data[parent::$update_type]['chat']))
			return parent::$update_data[parent::$update_type]['chat']['type'] == __FUNCTION__;
		else
			return false;
	}
	public static function private()
	{
		if(isset(parent::$update_data[parent::$update_type]['chat']))
			return parent::$update_data[parent::$update_type]['chat']['type'] == __FUNCTION__;
		else
			return false;
	}
	public static function supergroup()
	{
		if(isset(parent::$update_data[parent::$update_type]['chat']))
			return parent::$update_data[parent::$update_type]['chat']['type'] == __FUNCTION__;
		else
			return false;
	}
	public static function channel()
	{
		if(isset(parent::$update_data[parent::$update_type]['chat']))
			return parent::$update_data[parent::$update_type]['chat']['type'] == __FUNCTION__;
		else
			return false;
	}
	/**
       * 
       * Filters to check the ID or Username of the chat
       *
       * @param sting|int var either ID or Username
       * @return boolean
       */
	public static function chat($var)
	{
		if(isset(parent::$update_data[parent::$update_type]['chat']['id']) && is_numeric($var))
			return parent::$update_data[parent::$update_type]['chat']['id'] == $var;
		
		else if (isset(parent::$update_data[parent::$update_type]['chat']['username']))
			return parent::$update_data[parent::$update_type]['chat']['username'] == $var;
		
		else
			return false;
	}
	/**
       * 
       * Filters to check the ID or Username of the user
       *
       * @param sting|int var either ID or Username
       * @return boolean
       */
	public static function user($var)
	{
		if(isset(parent::$update_data[parent::$update_type]['from']['id']) && is_numeric($var))
			return parent::$update_data[parent::$update_type]['from']['id'] == $var;
		
		else if (isset(parent::$update_data[parent::$update_type]['from']['username']))
			return parent::$update_data[parent::$update_type]['from']['username'] == $var;
		
		else
			return false;
	}
	/**
       * 
       * Filters to allow all kind of updates
       *
       * @return boolean
       */
	public static function all()
	{
		return isset(parent::$update_data);
	}
}

?>