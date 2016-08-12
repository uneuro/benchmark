<?php

/**
 * Provides a report about benchmark results in a text format.
 */
class BenchmarkInfoTxt extends BenchmarkInfo
{
	/**
	 * @return string A report with benchmark results in a text format.
	 */
	public function getData()
	{
		$tested = $this->benchmark->getTestedWebsite();

		$data = date('Y-m-d H:i', $this->benchmark->getLastTestsTime()) . ":\r\n" . 'Results (from fastest to slowest):' . "\r\n";
		
		try {
			foreach ($this->getFastest2Slowest() as $website) {
				$data .= $this->getLoadTimeStr($website->getLoadTime()) . "\t" . $website->getURL();
				if ($website === $tested) {
					$data .= ' [tested website]';
				}
				$data .= "\r\n";
			}
		} catch (BadMethodCallException $e) {
			$data = 'Could not prepare report. Not all tests have been performed yet!';
		}

		return $data;
	}
	
	/**
	 * @return TestedWebsite[] An array of websites form the Benchmark object sorted from fastest load time to slowest.
	 */
	protected function getFastest2Slowest()
	{
		$comparison = array();
		$comparison[] = $this->benchmark->getTestedWebsite();
	
		foreach ($this->benchmark->getCompetingWebsites() as $competitor) {
			$comparison[] = $competitor;
		}
	
		usort($comparison, function(Website $w1, Website $w2) {
			if ($w1->getLoadTime() == $w2->getLoadTime()) return 0;
			return ($w1->getLoadTime() < $w2->getLoadTime()) ? -1 : 1;
		});
	
		return $comparison;
	}

	/**
	 * 
	 * @param int $loadTime Load time in milliseconds
	 * @return string Load time aligned to the left with "ms" unit added at the end.
	 */
	protected function getLoadTimeStr($loadTime)
	{
		return str_pad($loadTime, 4, " ", STR_PAD_LEFT) . 'ms';
	}
}
