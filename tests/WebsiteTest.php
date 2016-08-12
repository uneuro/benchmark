<?php

use PHPUnit\Framework\TestCase;

class WebsiteTest extends TestCase
{
	public function testGetLoadTimeException()
	{
		$w = new Website('http://foo.com');
		
		$this->expectException(BadMethodCallException::class);
		$w->getLoadTime();
	}
}