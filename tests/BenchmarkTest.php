<?php

use PHPUnit\Framework\TestCase;

class BenchmarkTest extends TestCase
{
	public function testIsConditionMet()
	{
		$tested = $this->createMock(Website::class);
		$tested->method('getLoadTime')->willReturn(100);
		
		$competing1 = $this->createMock(Website::class);
		$competing1->method('getLoadTime')->willReturn(150);
		
		$competing2 = $this->createMock(Website::class);
		$competing2->method('getLoadTime')->willReturn(100);
		
		$slower = function(Website $w, Website $c) {
			return $w->getLoadTime() > $c->getLoadTime();
		};
		
		$b = new Benchmark($tested, array($competing1, $competing2));
		
		$this->assertFalse($b->isConditionMet($slower));
		
		$competing3 = $this->createMock(Website::class);
		$competing3->method('getLoadTime')->willReturn(99);
		
		$b->addCompetingWebsite($competing3);
		
		$this->assertTrue($b->isConditionMet($slower));
		
		$tested2 = $this->createMock(Website::class);
		$tested2->method('getLoadTime')->willReturn(98);
		
		$b->setTestedWebsite($tested2);
		
		$this->assertFalse($b->isConditionMet($slower));
	}
	
	public function testAddingCompetitors()
	{
		$competitors = array(
			new Website('http://foo.com'),
			new Website('http://bar.com'),
			new Website('http://barbie.com')
		);
		
		$b = new Benchmark(new Website('http://dot.net'), $competitors);
		$this->assertEquals(count($b->getCompetingWebsites()), 3);
		
		$competitors[] = new Website('http://bar.com');
		
		$b2 = new Benchmark(new Website('http://dot.net'), $competitors);
		$this->assertEquals(count($b2->getCompetingWebsites()), 3);
		
		$competitors[] = 'foo';
		
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Invalid competitor type at key: 4');
		$b3 = new Benchmark(new Website('http://dot.net'), $competitors);
	}
}