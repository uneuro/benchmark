<?php

/**
 * Class representing a tested website.
 */
class Website
{
	/** @var string Website's URL. */
	protected $url = '';
	
	/** @var int Website's load time in milliseconds. */
	protected $loadTime = -1;
	
	/**
	 * Sets the website URL passed as the parameter.
	 * 
	 * InvalidArgumentException will be thrown if the parameter isn't a valid website URL.
	 * 
	 * @param string $url Website's URL.
	 * @throws InvalidArgumentException
	 */
	public function __construct($url)
	{
		if (!filter_var($url, FILTER_VALIDATE_URL)) {
			throw new InvalidArgumentException("Invalid url: '{$url}'!");
		}
		$this->url = $url;
	}
	
	/**
	 * @return string Website's URL.
	 */
	public function getURL()
	{
		return $this->url;
	}
	
	/**
	 * Tests website load time.
	 * 
	 * To include more specific tests this method may be overwritten in a derived class.
	 */
	public function test()
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_exec($ch);

		$info = curl_getinfo($ch);

		curl_close($ch);

		$this->loadTime = $info['total_time'] * 1000;
	}
	
	/**
	 * Returns website's load time in milliseconds.
	 * 
	 * Method returns the load time according to the last test.
	 * If no tests have been performed a BadMethodCallException is thrown.
	 * 
	 * @throws BadMethodCallException
	 * @return int Website's load time in milliseconds.
	 */
	public function getLoadTime()
	{
		if ($this->loadTime < 0) {
			throw new BadMethodCallException('No tests have been performed yet!');
		}
		return $this->loadTime;
	}
}
