<?php

use PHPUnit\Framework\TestCase;

class BenchmarkInfoTxtTest extends TestCase
{
	public function testGetData()
	{
		$tested = $this->createMock(Website::class);
		$tested->method('getLoadTime')->willReturn(123);
		$tested->method('getURL')->willReturn('http://dot.net');
		
		$competing1 = $this->createMock(Website::class);
		$competing1->method('getLoadTime')->willReturn(455);
		$competing1->method('getURL')->willReturn('http://competitor-one.co.uk');
		
		$competing2 = $this->createMock(Website::class);
		$competing2->method('getLoadTime')->willReturn(365);
		$competing2->method('getURL')->willReturn('http://competitor-two.com');
		
		$b = new Benchmark($tested, array($competing1, $competing2));
		$info = new BenchmarkInfoTxt($b);
		
		@$report = $info->getData();
		
		$this->assertTrue(strpos($report, $tested->getURL() . ' [tested website]') !== false);
		$this->assertTrue(strpos($report, $competing1->getURL() . ' [tested website]') === false);
		$this->assertTrue(strpos($report, $competing2->getURL() . ' [tested website]') === false);
		
		$report = explode("\r\n", $report);
		
		$this->assertTrue(strpos($report[2], $tested->getLoadTime() . "ms\t" . $tested->getURL()) !== false);
		$this->assertTrue(strpos($report[3], $competing2->getLoadTime() . "ms\t" . $competing2->getURL()) !== false);
		$this->assertTrue(strpos($report[4], $competing1->getLoadTime() . "ms\t" . $competing1->getURL()) !== false);
	}
}