<?php

/**
 * A class for sending email messages.
 */
class Mailer
{
	/** @var string Message content type. */
	protected $contentType = 'text/plain';
	
	/** @var string Message character set. */
	protected $characterSet = 'utf-8';
	
	/** @var string[] An array of additional headers to add to the email message. */
	protected $additionalHeaders = array();
	
	/**
	 * @param string $contentType Message content type.
	 */
	public function setContentType($contentType)
	{
		$this->contentType = $contentType;
	}
	
	/**
	 * @param string $characterSet Message character set.
	 */
	public function setCharacterSet($characterSet)
	{
		$this->characterSet = $characterSet;
	}
	
	/**
	 * Adds a header to the email message.
	 * 
	 * @param string $header
	 */
	public function addAdditionalHeader($header)
	{
		$header = preg_split('/\r|\n/', $header, null, PREG_SPLIT_NO_EMPTY);
		$this->additionalHeaders[] = $header[0];
	}
	
	/**
	 * Sends email message.
	 * 
	 * @param string $to Recipient of the message.
	 * @param string $subject
	 * @param string $message
	 * @return bool True if the mail was successfully accepted, False otherwise
	 * @throws Exception
	 */
	public function send($to, $subject, $message)
	{
		$to = filter_var($to, FILTER_SANITIZE_EMAIL);
		$subject = str_replace(array("\r", "\n"), array(' ', ' '), trim($subject));
		return mail($to, $subject, $message, $this->prepareHeaders());
	}
	
	/**
	 * @return string Additional headers to include in the message.
	 */
	protected function prepareHeaders()
	{
		$headers = array();
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = "Content-type: {$this->contentType}; charset={$this->characterSet}";
		return implode("\r\n", array_merge($headers, $this->additionalHeaders));
	}
}
