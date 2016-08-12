<?php

/**
 * Provides a report about benchmark results.
 * 
 * Extracts information from a Benchmark object and exposes them
 * formated in a way that should be specified in its derived classes.
 * 
 * @abstract
 */
abstract class BenchmarkInfo
{
	/** @var Benchmark */
	protected $benchmark;
	
	/**
	 * @param Benchmark $benchmark
	 */
	public function __construct(Benchmark $benchmark) {
		$this->setBenchmark($benchmark);
	}
	
	/**
	 * @param Benchmark $benchmark
	 */
	public function setBenchmark(Benchmark $benchmark) {
		$this->benchmark = $benchmark;
	}
	
	/**
	 * Returns a report about benchmark results.
	 * 
	 * @abstract
	 */
	public abstract function getData();
}
