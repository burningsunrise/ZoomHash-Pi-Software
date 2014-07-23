<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="images/favicon.ico">

	<title>ZoomHash WebUI</title>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,400italic,700,800' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Raleway:300,200,100' rel='stylesheet' type='text/css'>

	<!-- Bootstrap core CSS -->
	<link href="js/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" href="fonts/font-awesome-4/css/font-awesome.min.css">

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="../../assets/js/html5shiv.js"></script>
<script src="../../assets/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="js/jquery.nanoscroller/nanoscroller.css" />


<script type="text/javascript"
    src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<link href="css/style.css" rel="stylesheet" />	


<script>
$(document).ready( function(){
$('#refresh').load('refresh.php');
refresh();
});
 
function refresh()
{
	setTimeout( function() {
	  $('#refresh').fadeOut('slow').load('refresh.php').fadeIn('slow');
	  refresh();
	}, 35000);
}
</script>

<script type="text/javascript">
function countdown() {
    var i = document.getElementById('counter');
    i.innerHTML = parseInt(i.innerHTML)-1;
}
setInterval(function(){ countdown(); },1000);
</script>

</head>
<body class="animated">
	<div id="cl-wrapper">


		<div class="cl-sidebar">
			<div class="cl-toggle"><i class="fa fa-bars"></i></div>
			<div class="cl-navblock">
				<div class="menu-space">
					<div class="content">
						<div class="sidebar-logo">
							<div class="logo">
								<a href="#"></a>
							</div>
						</div>

						<ul class="cl-vnavigation">
							<li><a href="#"><i class="fa fa-home"></i><span>Miner Options</span></a>
								<ul class="sub-menu">
									<li   ><a href="restart.php">Restart Miner</a></li>
									<li   ><a href="start.php">Start Miner</a></li>
									<li   ><a href="stop.php">Stop Miner</a></li>
									<li	  ><a href="restartpi.php">Restart Pi</a></li>
								</ul>
							</li>
							<center style="color: #fff;"><p>SSH Options</p></center>
							<center>This will allow you to see all zeusminers in ssh</center>
							<center style="color: #fff;">sudo screen -r zeus</center>
							<center>This will allow you to see all gridseeds in ssh</center>
							<center style="color: #fff;">sudo screen -r grid</center>
							<center>To exit screens</center>
							<center style="color: #fff;">ctrl+a then ctrl+d</center>
							
								
						</ul>
						</div>
					</div>
					<div class="text-right collapse-button" style="padding:7px 9px;">
						<button id="sidebar-collapse" class="btn btn-default" style=""><i style="color:#fff;" class="fa fa-angle-left"></i></button>
					</div>
				</div>
			</div>
			<div class="container-fluid" id="pcont">
				<!-- TOP NAVBAR -->
				<div id="head-nav" class="navbar navbar-default">
					<div class="container-fluid">
						<div class="navbar-collapse">

						</div><!--/.nav-collapse animate-collapse -->
					</div>
				</div>					
				<div class="cl-mcont">
					<div id="refresh">
						<?php include("refresh.php");?>
					</div>

						<div class="col-sm-6 col-md-6" style="width: 100%;">
							<div class="block-flat">
								<div class="header">							
									<h3>Change Pool Information</h3>
								</div>
								<div class="content">
								<?
								
if(isset($_POST['poolinfoupdate']))
{
	$pool = $_POST['pool'];
	$user = $_POST['user'];
	$pass = $_POST['pass'];
	if(isset($_POST['zworkers'])){ $zworkers = ($_POST['zworkers'] - 1); }
	else { $zworkers = -1; }
	if(isset($_POST['gworkers'])){ $gworkers = ($_POST['gworkers'] - 1); }
	else { $gworkers = -1; }
	if(isset($_POST['zchips'])){ $zchips = $_POST['zchips']; }
	else { $zchips = 0; }
	if(isset($_POST['gfreq'])){ $gfreq = $_POST['gfreq']; }
	else { $gfreq = 0; }
	if(isset($_POST['zfreq'])){ $zfreq = $_POST['zfreq']; }
	else { $zfreq = 0; }
	


	file_put_contents('/var/www/conf/minerconfig.dat', $zworkers . "\n" . $gworkers . "\n" . $zfreq . "\n" . $pool . "\n" . $user . "\n" . $pass . "\n" . $zchips . "\n" . $gfreq);

	shell_exec('./restartpi.sh');

}
?>

									<form onSubmit="alert('Your Pi will be rebooted and this page will refresh once the Pi is rebooted!');" role="form" class="update-pool" id="update-pool" enctype="multipart/form-data" action="/index.php" method="post" style="border-style:none hidden;"> 
										<div class="form-group">
											<label style="color: black;">ZeusMiner Chips: <br />
											(Blizzard: 6) || 
											 (Cyclone: 96) || 
											 (Hurricane X2: 48) || 
											 (Hurricane X3: 64) || 
											 (Thunder X2: 96) || 
											 (Thunder X3: 128)
											</label>
										</div>										
										<div class="form-group">
											<label>ZeusMiner Unit Count</label> <input value="0" type="zworkers" name="zworkers" placeholder="Enter Zeusminer units" class="form-control">
										</div>
										<div class="form-group">
											<label>Gridseed Unit Count</label> <input value="0" type="gworkers" name="gworkers" placeholder="Enter Gridseed units" class="form-control">
										</div>											
										<div class="form-group">
											<label>ZeusMiner Chip Count</label> <input value="0" type="zchips" name="zchips" placeholder="Enter Zeusminer chips" class="form-control">
										</div>									
										<div class="form-group">
											<label>Update Pool URL</label> <input type="pool" name="pool" placeholder="stratum+tcp://POOLURL" class="form-control">
										</div>									
										<div class="form-group"> 
											<label>Update Username</label> <input type="user" name="user" placeholder="Username.Workername" class="form-control">
										</div> 
										<div class="form-group"> 
											<label>Update Password</label> <input type="pass" name="pass" placeholder="Password" class="form-control">
										</div> 
										<div class="form-group"> 
											<label>Update Gridseed Frequency</label> <input value="0" type="gfreq" name="gfreq" placeholder="800" class="form-control">
										</div> 	
										<div class="form-group"> 
											<label>Update Zeus Frequency</label> <input value="0" type="zfreq" name="zfreq" placeholder="368" class="form-control">
										</div> 														
										<button class="btn btn-primary" name="poolinfoupdate" type="submit">Submit</button>
									</form>

								</div>				
							</div>
						</div>


					</div>	  


				</div> 

			</div>

			<script src="js/jquery.js"></script>
			<script src="js/jquery.pushmenu/js/jPushMenu.js"></script>
			<script type="text/javascript" src="js/jquery.nanoscroller/jquery.nanoscroller.js"></script>
			<script type="text/javascript" src="js/jquery.ui/jquery-ui.js" ></script>
			<script type="text/javascript" src="js/behaviour/core.js"></script>
			<script src="js/bootstrap/dist/js/bootstrap.min.js"></script>

		</body>
		</html>
