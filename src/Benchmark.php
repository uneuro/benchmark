<?php

/**
 * Class for testing websites.
 * 
 * Stores Website objects with distinction between the main tested website
 * and its competitors, performs tests on them and exposes the results.
 */
class Benchmark
{
	/** @var Website The main website being tested. */
	protected $testedWebsite;
	
	/** @var Website[] An array of the competing website objects. */
	protected $competingWebsites = array();
	
	/** @var int Last test's timestamp. */
	protected $lastTestsTime;
	
	/**
	 * @param Website $tested The main website to be tested.
	 * @param Website[] $competitors An array of the competing website objects.
	 * @throws InvalidArgumentException
	 */
	public function __construct(Website $tested, array $competitors)
	{
		$this->setTestedWebsite($tested);
		
		foreach ($competitors as $key => $competitor) {
			if (!($competitor instanceof Website)) {
				throw new InvalidArgumentException('Invalid competitor type at key: ' . $key);
			}
			
			$this->addCompetingWebsite($competitor);
		}
	}
	
	/**
	 * @param Website $website The main website to be tested.
	 */
	public function setTestedWebsite(Website $website)
	{
		$this->testedWebsite = $website;
	}
	
	/**
	 * @return Website The website currently set as the main tested website.
	 */
	public function getTestedWebsite()
	{
		return $this->testedWebsite;
	}
	
	/**
	 * Add a website to compete with the main tested website.
	 * 
	 * @param Website $website A competing website.
	 */
	public function addCompetingWebsite(Website $website)
	{
		if (in_array($website, $this->competingWebsites)) return;
		$this->competingWebsites[$website->getURL()] = $website;
	}

	/**
	 * @return Website[] An array of all competing website objects.
	 */
	public function getCompetingWebsites()
	{
		return $this->competingWebsites;
	}
	
	/**
	 * @return int Timestamp The time of the last performed website tests.
	 */
	public function getLastTestsTime()
	{
		return $this->lastTestsTime;
	}
	
	/**
	 * Performs tests on all websites added to the Benchmark object.
	 * 
	 * Updates the timestamp of the last performed tests.
	 * Invokes Website::test() method for the main tested website object
	 * and all competing website objects.
	 */
	public function performTests()
	{
		$this->lastTestsTime = time();

		$this->testedWebsite->test();
		
		foreach ($this->competingWebsites as $competitor) {
			$competitor->test();
		}
	}
	
	/**
	 * Checks if a given condition was met during the benchmark.
	 * 
	 * Checks the main Website object against each of its competing Website objects
	 * according to the condition specified inside the anonymous function passed
	 * as the parameter.
	 * 
	 * @param callable $conditionMet Boolean function to test a given condition on a pair of Website objects.
	 * @throws InvalidArgumentException
	 * @return boolean True if the specified condition is met at least once (for at least one pair: {tested website, competitor}).
	 */
	public function isConditionMet($conditionMet)
	{
		if (!is_callable($conditionMet)) {
			throw new InvalidArgumentException('Argument provided for Benchmark::checkCondition must be callable!');
		}

		$result = false;
		foreach ($this->competingWebsites as $competitor) {
			$result = $result || $conditionMet($this->testedWebsite, $competitor);
		}
		return $result;
	}
}
