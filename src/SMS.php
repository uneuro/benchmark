<?php

/**
 * SMS service API.
 */
class SMS
{
	/**
	 * @param array $serviceCredentials credentials for the SMS service account.
	 */	
	public function __construct($serviceCredentials) {}
	
	/**
	 * @param string[] $phoneNumbers An array of the message recipients' phone numbers.
	 * @param string $message A message to be sent by the SMS service.
	 */
	public function send($phoneNumbers, $message) {}
}
