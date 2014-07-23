<center><label>Information will refresh in <span id="counter">35</span> second(s).</label></center>
<div class="row">

						<div class="col-md-3 col-sm-6">
							<div class="fd-tile detail tile-green">
								<div class="content"><h1 class="text-left">										
									<? error_reporting(0);
									#
# Sample Socket I/O to BFGMiner API
#
function getsock($addr, $port)
{
 $socket = null;
 $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
 if ($socket === false || $socket === null)
 {
	$error = socket_strerror(socket_last_error());
	$msg = "socket create(TCP) failed";

	return null;
 }

 $res = socket_connect($socket, $addr, $port);
 if ($res === false)
 {
	$error = socket_strerror(socket_last_error());
	$msg = "socket connect($addr,$port) failed";

	socket_close($socket);
	return null;
 }
 return $socket;
}
#
# Slow ...
function readsockline($socket)
{
 $line = '';
 while (true)
 {
	$byte = socket_read($socket, 1);
	if ($byte === false || $byte === '')
		break;
	if ($byte === "\0")
		break;
	$line .= $byte;
 }
 return $line;
}
#
function request($cmd)
{
 $socket = getsock('127.0.0.1', 4025);
 if ($socket != null)
 {
	socket_write($socket, $cmd, strlen($cmd));
	$line = readsockline($socket);
	socket_close($socket);

	if (strlen($line) == 0)
	{
		echo "WARN: '$cmd' returned nothing\n";
		return $line;
	}

	//print "$cmd returned '$line'\n";

	if (substr($line,0,1) == '{')
		return json_decode($line, true);

	$data = array();

	$objs = explode('|', $line);
	foreach ($objs as $obj)
	{
		if (strlen($obj) > 0)
		{
			$items = explode(',', $obj);
			$item = $items[0];
			$id = explode('=', $items[0], 2);
			if (count($id) == 1 or !ctype_digit($id[1]))
				$name = $id[0];
			else
				$name = $id[0].$id[1];

			if (strlen($name) == 0)
				$name = 'null';

			if (isset($data[$name]))
			{
				$num = 1;
				while (isset($data[$name.$num]))
					$num++;
				$name .= $num;
			}

			$counter = 0;
			foreach ($items as $item)
			{
				$id = explode('=', $item, 2);
				if (count($id) == 2)
					$data[$name][$id[0]] = $id[1];
				else
					$data[$name][$counter] = $id[0];

				$counter++;
			}
		}
	}

	return $data;
 }

 return null;
}
#

$z = request('summary');
$sv_rigs = count($z);

$sv_rigs = $sv_rigs - 1;

 for ($i=0; $i<$sv_rigs; $i++)
{
	$z[$i]['summary'] = request('z');
}



$q = request('pools');
$qv_rigs = count($q);

$qv_rigs = $qv_rigs - 1;
for ($i=0; $i<$qv_rigs; $i++)
{
	$q[$i]['pools'] = request('z');
}

#


$r = request('devs');
$dv_rigs = count($r);

$dv_rigs =  $dv_rigs - 1;

 for ($i=0; $i<$dv_rigs; $i++)
{
	$r[$i]['devs'] = request('devs');
}

#
									

									
									
									include("callminer.php");
									$mhsavg = $z["SUMMARY"]["MHS av"];
									if (isset($stats->devices))
									{
										foreach($stats->devices as $name => $device)
										{
											$c = 0;
											$devhashrate = 0;
											foreach($device->chips as $chip)
											{
												$c++;
												$devhashrate+= $chip->hashrate;
											}
											$minerhashrate+= substr($devhashrate,0,-3);
										}
										$test = intval($minerhashrate);
										if($test >= 1000 && $mhsavg >= 1000)
										{
											if(isset($mhsavg) && isset($test)){
												echo $mhsavg + $test . " MH/s";
											}
											elseif(isset($test)) {
												echo substr($test/1000, 0, -2) . " MH/s";
											}
											elseif(isset($mhsavg)) {
												echo $mhsavg . " MH/s";
											}
										}
										else
										{
											if(isset($mhsavg) && isset($test)){
												echo ($mhsavg += floatval($test/1000)) . " MH/s";
											}
											elseif(isset($test)) {
												echo $test . " KH/s";
											}
											elseif(isset($mhsavg)) {
												echo $mhsavg . " MH/s";
											}
										}
									}
									else
									{
											if(isset($mhsavg)) {
												echo $mhsavg . " MH/s";
											}
										
									}
									
									
									
									
									
																		
									?></h1><p>Hashrate</p></div>
									<div class="icon"><i class="fa fa-upload"></i></div>
									<i class="details"> <span></span></i>
								</div>
							</div>

							<div class="col-md-3 col-sm-6">
								<div class="fd-tile detail tile-blue">
									<div class="content"><h1 class="text-left"><?if (isset($stats->devices))
									{
										foreach($stats->devices as $name => $device)
										{
											$c = 0;
											$devaccept = 0;
											foreach($device->chips as $chip)
											{
												$c++;
												$devaccept+= $chip->accepted;
											}
											$mineraccept += $devaccept;
										}
										$accepted = $z["SUMMARY"]["Accepted"];
										
										if(isset($accepted) && isset($mineraccept))
										{
											$total = (int)$accepted + (int)$mineraccept;
											echo $total;
										}
										elseif(isset($mineraccept))
										{
											echo $mineraccept;
										}
										elseif(isset($accepted))
										{
											echo $accepted;
										}
									}
									else
									{
										$accepted = $z["SUMMARY"]["Accepted"];
										
										if(isset($accepted))
										{
											echo $accepted;
										}	
									}

									?></h1><p>Accepted Shares</p></div>
									<div class="icon"><i class="fa fa-reply"></i></div>
									<i class="details" href="#"> <span></span></i>
								</div>
							</div>							
							
							<div class="col-md-3 col-sm-6">
								<div class="fd-tile detail tile-red">
									<div class="content"><h1 class="text-left">	<?
									if (isset($stats->devices))
									{
										foreach($stats->devices as $name => $device)
										{
											$c = 0;
											$devrejected = 0;
											foreach($device->chips as $chip)
											{
												$c++;
												$devrejected+= $chip->rejected;
											}
											$minerreject += $devrejected;
										}
										
										$rejected = $z["SUMMARY"]["Rejected"];
										if(isset($rejected) && isset($minerreject))
										{
											$total = (int)$rejected + (int)$minerreject;
											echo $total;
										}
										elseif(isset($minerreject))
										{
											echo $minerreject;
										}
										elseif(isset($rejected))
										{
											echo $rejected;
										}
									}
									else
									{
										$rejected = $z["SUMMARY"]["Rejected"];
		
										if(isset($rejected))
										{
											echo $rejected;
										}
									}
									
									?></h1><p>Rejected Shares 
									<?
									if(isset($mineraccept) && isset($accepted) && isset($mineraccept) > 0 && isset($accepted) > 0)
									{
										echo "(".$percent = round(100*(((int)$rejected + (int)$minerreject) / ((int)$mineraccept + (int)$accepted)), 2)."%)";
									}
									elseif(isset($mineraccept) && isset($mineraccept) > 0)
									{
										echo "(".$percent = round(100*((int)$minerreject / (int)$mineraccept ), 2)."%)";
									}
									elseif(isset($accepted) && $accepted > 0)
									{
										echo "(".$percent = round(100*((int)$rejected  / (int)$accepted), 2)."%)";
									}
									?></p></div>
									<div class="icon"><i class="fa fa-heart"></i></div>
									<i class="details"><span></span></i>
								</div>
							</div>

							<div class="col-md-3 col-sm-6">
								<div class="fd-tile detail tile-prusia">
									<div class="content"><h1 class="text-left"><?if (isset($stats->devices))
									{
										foreach($stats->devices as $name => $device)
										{
											$c = 0;
											$deverrors = 0;
											foreach($device->chips as $chip)
											{
												$c++;
												$deverrors+= $chip->hw_errors;
											}
											$minererrors += $deverrors;
										}
										
										$hwerror = $z["SUMMARY"]["Hardware Errors"];
										if(isset($hwerror) && isset($minererrors))
										{
											$total = (int)$hwerror + (int)$minererrors;
											echo $total;
										}
										elseif(isset($minererrors))
										{
											echo $minererrors;
										}
										elseif(isset($hwerror))
										{
											echo $hwerror;
										}
									}	
									else
									{
										$hwerror = $z["SUMMARY"]["Hardware Errors"];
										if(isset($hwerror))
										{
											echo $hwerror;
										}									
									}
									
									?></h1><p>HW Errors 
									<?
									if(isset($mineraccept) && isset($accepted) && $mineraccept > 0 && $accepted > 0)
									{
										echo "(".$percent = round(100*(((int)$hwerror + (int)$minererrors) / ((int)$mineraccept + (int)$accepted)), 2)."%)";
									}
									elseif(isset($mineraccept) && $mineraccept > 0)
									{
										echo "(".$percent = round(100*((int)$minererrors / (int)$mineraccept ), 2)."%)";
									}
									elseif(isset($accepted) && $accepted > 0)
									{
										echo "(".$percent = round(100*((int)$hwerror  / (int)$accepted), 2)."%)";
									}
									?></p></div>
									<div class="icon"><i class="fa fa-flag"></i></div>
									<i class="details" > <span></span></i>
								</div>
							</div>

						</div>
						<div class="panel-group accordion accordion-semi" id="accordion3">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion3" href="#ac3-1" class="">
											<i class="fa fa-angle-right"></i> Current Information
										</a>
									</h4>
								</div>
								<div id="ac3-1" class="panel-collapse collapse in" style="height: auto;">
									<div class="panel-body">
										<strong>Pool Url:</strong> 
										<?
										$poolz = $q['POOL0']['URL'];
										if (isset($stats->devices)) {
										$poolg = $pool->url; 
										} 
										if(isset($poolz) && isset($poolg))
										{
											echo $poolg;
										}
										elseif(isset($poolz))
										{
											echo $poolz;
										}
										elseif(isset($poolg))
										{
											echo $poolg;
										}
										
										?>
										<br />
										<strong>Username.Worker: </strong>
										<?
										if (isset($stats->devices)) { 
										$userg = $pool->user; 
										} 
										$userz = $q['POOL0']['User'];
										
										if(isset($userz) && isset($userg))
										{
											echo $userg;
										}
										elseif(isset($userz))
										{
											echo $userz;
										}
										elseif(isset($userg))
										{
											echo $userg;
										}
										
										
										?>
										<br />
										<strong>Password:</strong> <?if (isset($stats->devices)) { echo $pool->pass; } else {
											$myFile = "/var/www/conf/minerconfig.dat";
											$lines = file($myFile);//file in to an array
											echo $lines[5]; //line 6
											}?>
										<br />
										<strong>Frequency:</strong> <?
										if (isset($stats->devices))
										{
											foreach($stats->devices as $name => $device)
											{
												$c = 0;
												$devfreq = 0;
												foreach($device->chips as $chip)
												{
													$c++;
													$devfreq+= $chip->frequency;
												}
											}
											echo round(($devfreq / $c) , 0);
										}
										else {
										echo "368";
										}
										?>
									</div>
								</div>
							</div>
						</div>
						<div class="block-flat">
							<div class="header">							
								<h3>Devices</h3>
							</div>
							<div class="content">
								<div class="table-responsive">
									<table class="table table-bordered" id="datatable">
										<thead>
											<tr>
												<th>Device Name</th>
												<th>Frequency</th>
												<th>Accepted Shares</th>
												<th>Rejected Shares</th>
												<th>Hardware Errors</th>
												<th>Hashrate</th>
											</tr>
										</thead>
										<tbody>
											<?php
											if (isset($stats->devices))
											{
												foreach($stats->devices as $name => $device)
												{
													$d++;
													$c = 0;
													$devfreq = 0;
													$devaccept = 0;
													$devrejected = 0;
													$deverrors = 0;
													$devhashrate = 0;
													foreach($device->chips as $chip)
													{
														$c++;
														$devfreq+= $chip->frequency;
														$devaccept+= $chip->accepted;
														$devrejected+= $chip->rejected;
														$deverrors+= $chip->hw_errors;
														$devhashrate+= $chip->hashrate;
													}

													?><tr class="odd gradeX">
													<td ><span class="badge badge-warning" data-placement="top" data-toggle="tooltip" data-original-title="Unique name of device connected to Raspberry Pi"><?echo $name;?></span></td>
													<td><span class="badge badge-info" data-placement="top" data-toggle="tooltip" data-original-title="Speed at which the devices process information"><? echo round(($devfreq / $c) , 0);?></span></td>
													<td><span class="badge badge-success" data-placement="top" data-toggle="tooltip" data-original-title="Number of shares that have been allocated to your account"><? echo $devaccept;?></span></td>
													<td class="center"><span class="badge badge-danger" data-placement="top" data-toggle="tooltip" data-original-title="Work that has arrived too late at the pool and has been rejected"><?echo $devrejected;?></span></td>
													<td class="center"><span class="badge" data-placement="top" data-toggle="tooltip" data-original-title="Device errors that can sometimes be caused by heightened frequency"><?echo $deverrors;?></span></td>
													<td><span class="badge badge-primary" data-placement="top" data-toggle="tooltip" data-original-title="Speed at which the miner solves blocks"><?echo substr($devhashrate,0,-3) . " KH/s";?></span></td>
													</tr><?
												}
											}
											
											//BFGMINER START
											

	for ($i=0; $i<$dv_rigs; $i++)
	{

?>

												<tr class="odd gradeX">
													<td ><span class="badge badge-warning" data-placement="top" data-toggle="tooltip" data-original-title="Unique name of device connected to Raspberry Pi"><?echo "ZUS".($i);?></span></td>
													<td><span class="badge badge-info" data-placement="top" data-toggle="tooltip" data-original-title="Speed at which the devices process information">368</span></td>
													<td><span class="badge badge-success" data-placement="top" data-toggle="tooltip" data-original-title="Number of shares that have been allocated to your account"><?echo $r[$i]['devs']['PGA'.$i]['Accepted'];?></span></td>
													<td class="center"><span class="badge badge-danger" data-placement="top" data-toggle="tooltip" data-original-title="Work that has arrived too late at the pool and has been rejected"><?php echo $r[$i]["devs"]["PGA".$i]["Rejected"];?></span></td>
													<td class="center"><span class="badge" data-placement="top" data-toggle="tooltip" data-original-title="Device errors that can sometimes be caused by heightened frequency"><?echo $r[$i]["devs"]["PGA".$i]["Hardware Errors"];?></span></td>
													<td><span class="badge badge-primary" data-placement="top" data-toggle="tooltip" data-original-title="Speed at which the miner solves blocks"><?php echo $r[$i]["devs"]['PGA'.$i]['MHS av'] . " MH/s"?></span></td>
		
		<?}					
											?>
										</tbody>
									</table>							
								</div>
							</div>
						</div>	