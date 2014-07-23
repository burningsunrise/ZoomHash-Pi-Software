<?php
$cmd = false;

if (!($fp = @fsockopen("127.0.0.1", 4027, $errno, $errstr, 3)))
{
	return array(
		"error" => true,
		"msg" => $errstr
		);
}

stream_set_blocking($fp, false);

if (!$cmd) $in = json_encode(array(
	"get" => "stats"
	)) . "\n";
	else $in = json_encode($cmd) . "\n";
fwrite($fp, $in);
usleep(150000);
$out = false;
$die = 0;

while (!feof($fp) && !($str = fgets($fp, 2048))) usleep(10000);
$out.= $str;

while (!feof($fp) && $die < 10)
{
	if (!($str = fgets($fp, 1024)))
	{
		$die++;
		usleep(10000);
		continue;
	}

	$die = 0;
	$out.= $str;
}

fclose($fp);
$stats = $out;
$stats = json_decode($stats);
$d = 0;
$miner = array();
$minerfreq = 0;
$mineraccept = 0;
$minerreject = 0;
$minererrors = 0;
$minerhashrate = 0;

if (isset($stats->pools))
{
	foreach($stats->pools as $pool)
	{
		if ($pool->active == 1)
		{

			$miner['pool']['url'] = $pool->url;
			$miner['pool']['user'] = $pool->user;
			$miner['pool']['pass'] = $pool->pass;
		}
	}
}


?>
