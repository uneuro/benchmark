<?php

if (PHP_SAPI != 'cli') {
	echo 'This script runs only in CLI mode.';
	exit();
}

if (count($argv) < 3) {
	$script = end(explode(DIRECTORY_SEPARATOR, $argv[0]));
	echo "Usage:\r\n";
	echo "php {$script} benchmarked_url competitor_url1 [competitor_url2 ...]\r\n";
	exit();
}

require_once('src/autoload.php');

try {
	$tested = new Website($argv[1]);
	$competitors = array();
	
	for ($i = 2; $i < count($argv); ++$i) {
		$competitors[] = new Website($argv[$i]);
	}
} catch (InvalidArgumentException $e) {
	exit('Error: ' . $e->getMessage() . "\r\n");
}

$b = new Benchmark($tested, $competitors);
$info = new BenchmarkInfoTxt($b);

echo 'Testing...' . "\r\n";

$b->performTests();

// report results
$report = $info->getData();

echo $report;

$log = new SplFileObject('log.txt', 'a');
$log->fwrite($report);
$log = null;

// additional conditons
$slower = function(Website $w, Website $c) {
	return $w->getLoadTime() > $c->getLoadTime();
};

$twiceAsSlow = function(Website $w, Website $c) {
	return $w->getLoadTime() >= 2 * $c->getLoadTime();
};

if ($b->isConditionMet($slower)) {
	$mail = new Mailer();
	$mail->send('someone@example.com', 'Benchmark notification', 'Tested website loaded slower than one of the competitors.');
	
	if($b->isConditionMet($twiceAsSlow)) {
		$sms = new SMS(array('user', 'passwd'));
		$sms->send(array('+48 123456789'), 'Tested website loaded twice as slow as one of the competitors.');
	}
}
