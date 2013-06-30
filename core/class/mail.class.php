<?php
/**
* Allows e-mail sending.
* 
* @author Jean-Philippe Collette
* @package Core
* @subpackage Misc
*/
class Mail extends Object
{
	private $headers = array();
	private $to, $subject, $message;
	
	public function __construct($to, $subject, $message='')
	{
		parent::__construct();
		$this->to = $to;
		$this->subject = $subject;
		$this->message = $message;
		$this->addHeader("Content-type", "text/html; charset=utf-8");
		$this->addHeader("From", EMAIL);
	}
	
	public function setMessageTemplate($template)
	{
		$this->message = $this->renderFile($template);
	}
	
	public function __set($field, $value)
	{
		$this->addHeader($field, $value);
	}
		
	/**
	* Adds a mail header.
	* @param string $header Header name.
	* @param string $value Header value.
	*/
	public function addHeader($header, $value)
	{
		$this->headers[$header] = $value;
	}
	
	/**
	* Sends the email.
	*/
	public function send()
	{
		$headers = array();
		
		foreach($this->headers as $k => $v)
			$headers[] = $k.": ".$v;
		
		$message = $this->message;
		
		return mail($this->to, $this->subject, $message, implode("\r\n", $headers));
	}
}

?>