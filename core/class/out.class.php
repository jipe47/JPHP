<?php
/**
* Interface to send output data.
* 
* @author Jean-Philippe Collette
* @package Core
* @subpackage IO
*/
class out
{
	/**
	* Adds an entry in the logbook.
	* @param string $message The message which will be recorded.
	* @param string $type The type of message (see Log::INFO|SUCCESS|WARNING|CONFIRM|ERROR)
	*/
	public static function log($message, $type = Message::INFO)
	{
		Log::add($message, $type);
	}
	
	/**
	* Displays a message in the current page, if no redirection is specified
	* @param string $message The message which will be displayed.
	* @param string $type The type of message (see Message::INFO|SUCCESS|WARNING|VALIDATION|ERROR)
	*/
	public static function message($message, $type = Message::SUCCESS)
	{
		Messages::add($message, $type);
	}
	
	/**
	* Displays an error message ; alias of message.
	* @param string $message The message which will be displayed.
	*/
	public static function e($message)
	{
		self::message($message, Message::ERROR);
	}
	
	/**
	 * @see out::e
	 * @param string $message The message which will be displayed.
	 */
	public static function error($message)
	{
		self::e($message);
	}
	
	/**
	* Displays an informative message ; alias of message.
	* @param string $message The message which will be displayed.
	*/
	public static function i($message)
	{
		self::message($message, Message::INFO);
	}
	
	/**
	 * @see out::i
	 * @param string $message The message which will be displayed.
	 */
	public static function info($message)
	{
		self::i($message);
	}
	
	/**
	* Displays a warning message ; alias of message.
	* @param string $message The message which will be displayed.
	*/
	public static function w($message)
	{
		self::message($message, Message::WARNING);
	}

	/*
	 * @see out::w
	 * @param string $message The message which will be displayed.
	 */
	public static function warn($message)
	{
		self::w($message);
	}
	
   /*
	* @see out::w
	* @param string $message The message which will be displayed.
	*/
	public static function warning($message)
	{
		self::w($message);
	}
	
	/**
	* Displays a success message ; alias of message.
	* @param string $message The message which will be displayed.
	*/
	public static function s($message)
	{
		self::message($message, Message::SUCCESS);
	}
	
	/*
	 * @see out::s
	* @param string $message The message which will be displayed.
	*/
	public static function success($message)
	{
		self::s($message);
	}
}

?>