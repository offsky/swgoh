<?
if(strpos($_SERVER['SERVER_NAME'], "docker")===false) {
	require_once("../vars.php");
	require_once("../libs.php");
	$db = new mymysqli("swgoh");
	error_reporting(0);
} else {
	require_once("../../vars.php");
	require_once("../libs.php");
	$db = new mymysqli("swgoh");
	error_reporting(E_ALL);
}
$self = explode("/",$_SERVER['PHP_SELF']);
$self = array_pop($self);

function makeInteger($value) {
	$value=preg_replace("/[^0-9]/","",$value); //remove non numeric
	return intval($value);
}

$gp = empty($_POST['gp']) ? "" : makeInteger(trim($_POST['gp']));
$stars = empty($_POST['stars']) ? "" : makeInteger(trim($_POST['stars']));
$time = empty($_POST['time']) ? "" : makeInteger(trim($_POST['time']));

$recorded = false;
if(!empty($gp) && !empty($stars)) {
	$rs = $db->query("INSERT INTO swgoh_tb_scores(gp,stars,stamp,dark) VALUES(".intval($gp).",".intval($stars).",".intval($time).",1)");
	$recorded = true;
	header("Location: http://shard.swgoh.life/territory_battle_scores_dark.php?recorded=1");
}

if(!empty($_GET['recorded'])) $recorded = true;

?><!DOCTYPE html>
<html>
	<head>
		<title>SWGOH Tools - Territory Battle Readiness</title>
 		<meta name="viewport" content="width=device-width, initial-scale=1">
	  	<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
		<meta charset="utf-8" />
		<style>
			p { max-width: 800px;}
			.swgoh_ad {
			position: absolute;
			left:0;
			top:90px;
			width:0;
			height:0px;
			}
			#ad {
			width:100%;
			height:60px;
			}
			table { margin-left: 0; border: 1px solid #000;}
			td,th { border: 1px solid #666; padding: 10px;}
			td:first-child,
			th:first-child {
				border-left: 1px solid #666;
			}

			@media(min-width: 520px) { #top { padding-bottom:0px} #ad { height:90px;} .swgoh_ad { left:inherit; top:0; right:0; width: 328px; height: 90px; } }
			@media(min-width: 720px) { #top { padding-bottom:0px} #ad { height:90px;} .swgoh_ad { left:inherit; top:0; right:0; width: 528px; height: 90px; } }
			@media(min-width: 920px) { #top { padding-bottom:0px} #ad { height:90px;} .swgoh_ad { left:inherit; top:0; right:0; width: 728px; height: 90px; } }
			@media(min-width: 1200px) { #top { padding-bottom:0px} #ad { height:90px;} .swgoh_ad { left:inherit; top:0; right:0; width: 1000px; height: 90px; } }
  	</style>
  	<script src="https://use.fontawesome.com/c278e2b3ff.js"></script>
  	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

  	<script src="jquery.js"></script>
	<script>
	$(document).ready(function() {

		var storage = null;
		if(window.localStorage) storage = window.localStorage;
		else if(window.globalStorage) storage = window.globalStorage[location.hostname];

		$('#gp').on("blur", function() {
			var val = $('#gp').val();
			val = val.replace(/[.,]/g,"");
			val = parseInt(val);
			if(val<1000) val=0;
			if(val>200000000) val=0;

			var value = val.toLocaleString('en-US');
			$('#gp').val(value)
		});

		$('#stars').on("blur", function() {
			var val = $('#stars').val();
			if(val>45) $('#stars').val(0);

		});
		function store(key,val) {
			storage.setItem(key,val);
		}
		function fetch(key) {
			return storage.getItem(key);
		}
	});
	</script>
	</head>
	<body>
		<div id="top">
      	<a href="http://www.swgoh.life/index.html" id="logoClick"></a>
			<div id="ad">
			<!-- Swgoh.life -->
			<ins class="adsbygoogle swgoh_ad"
			 style="display:block"
			 data-ad-client="ca-pub-0176506581219642"
			 data-ad-slot="9855290481"
			 data-ad-format="horizontal"></ins>
			<script>
			//https://support.google.com/adsense/answer/6307124
			(adsbygoogle = window.adsbygoogle || []).push({});
			</script>
			</div>
	</div>
	<div id="middleSolo">

		<h1><a href="http://www.swgoh.life/index.html">More Tools</a> &gt; Territory Battle Scores</h1>

		<p>The goal of this page is to gather scores from Territory Battle so that we can all see how many stars we can expect to get from TB.</p>

		<div class="half">
			<? if(!$recorded) { ?>
			<form action="<?=$self?>" method="post">
				<input type="hidden" name="time" value="<?=time()?>" />
				<b>What is your Guild's total GP:</b><br />
				<input type="text" id="gp" name="gp" value="" placeholder="50,000,000" /><br /><br />

				<b>How many stars did you get in the last Territory Battle:</b><br />
				<input type="text" id="stars" name="stars" value="" placeholder="25" /><br /><br />

				<input type="submit" value="Record Score" class="btn" />
			</form>
			<? } else { ?>
				<br />
				<b>Your score has been recorded</b>
				<br />
				Please do not enter your score multiple times.
				<br />
			<? } ?>
		</div>
		<div class="half">
			<b>Results</b><br />

			<table>
				<tr><th>Guild GP</th><th>Average Stars <i class="fa fa-star"></i></th><th>Range</th><th>Data Points</th></tr>
				<?
					$rs = $db->query("SELECT avg(stars),count(stars),min(stars),max(stars) FROM swgoh_tb_scores WHERE dark=1 AND gp>10000 AND gp<=5000000");
					while($row = $db->getNext($rs)) {
						if($row[1]>2) echo "<tr><td>0 - 5,000,000</td><td>".round($row[0])."</td><td>".$row[2]."-".$row[3]."</td>";
						else echo "<tr><td>0 - 5,000,000</td><td colspan='2'>Not Enough data</td>";
						echo "<td>".$row[1]."</td></tr>";
					}
					$rs = $db->query("SELECT avg(stars),count(stars),min(stars),max(stars) FROM swgoh_tb_scores WHERE dark=1 AND gp>5000000 AND gp<=10000000");
					while($row = $db->getNext($rs)) {
						if($row[1]>2) echo "<tr><td>5,000,000 - 10,000,000</td><td>".round($row[0])."</td><td>".$row[2]."-".$row[3]."</td>";
						else echo "<tr><td>5,000,000 - 10,000,000</td><td colspan='2'>Not Enough data</td>";
						echo "<td>".$row[1]."</td></tr>";
					}
					$rs = $db->query("SELECT avg(stars),count(stars),min(stars),max(stars) FROM swgoh_tb_scores WHERE dark=1 AND gp>10000000 AND gp<=20000000");
					while($row = $db->getNext($rs)) {
						if($row[1]>2) echo "<tr><td>10,000,000 - 20,000,000</td><td>".round($row[0])."</td><td>".$row[2]."-".$row[3]."</td>";
						else echo "<tr><td>10,000,000 - 20,000,000</td><td colspan='2'>Not Enough data</td>";
						echo "<td>".$row[1]."</td></tr>";
					}
					$rs = $db->query("SELECT avg(stars),count(stars),min(stars),max(stars) FROM swgoh_tb_scores WHERE dark=1 AND gp>20000000 AND gp<=30000000");
					while($row = $db->getNext($rs)) {
						if($row[1]>2) echo "<tr><td>20,000,000 - 30,000,000</td><td>".round($row[0])."</td><td>".$row[2]."-".$row[3]."</td>";
						else echo "<tr><td>20,000,000 - 30,000,000</td><td colspan='2'>Not Enough data</td>";
						echo "<td>".$row[1]."</td></tr>";
					}
					$rs = $db->query("SELECT avg(stars),count(stars),min(stars),max(stars) FROM swgoh_tb_scores WHERE dark=1 AND gp>30000000 AND gp<=40000000");
					while($row = $db->getNext($rs)) {
						if($row[1]>2) echo "<tr><td>30,000,000 - 40,000,000</td><td>".round($row[0])."</td><td>".$row[2]."-".$row[3]."</td>";
						else echo "<tr><td>30,000,000 - 40,000,000</td><td colspan='2'>Not Enough data</td>";
						echo "<td>".$row[1]."</td></tr>";
					}
					$rs = $db->query("SELECT avg(stars),count(stars),min(stars),max(stars) FROM swgoh_tb_scores WHERE dark=1 AND gp>40000000 AND gp<=50000000");
					while($row = $db->getNext($rs)) {
						if($row[1]>2) echo "<tr><td>40,000,000 - 50,000,000</td><td>".round($row[0])."</td><td>".$row[2]."-".$row[3]."</td>";
						else echo "<tr><td>40,000,000 - 50,000,000</td><td colspan='2'>Not Enough data</td>";
						echo "<td>".$row[1]."</td></tr>";
					}
					$rs = $db->query("SELECT avg(stars),count(stars),min(stars),max(stars) FROM swgoh_tb_scores WHERE dark=1 AND gp>50000000 AND gp<=60000000");
					while($row = $db->getNext($rs)) {
						if($row[1]>2) echo "<tr><td>50,000,000 - 60,000,000</td><td>".round($row[0])."</td><td>".$row[2]."-".$row[3]."</td>";
						else echo "<tr><td>50,000,000 - 60,000,000</td><td colspan='2'>Not Enough data</td>";
						echo "<td>".$row[1]."</td></tr>";
					}
					$rs = $db->query("SELECT avg(stars),count(stars),min(stars),max(stars) FROM swgoh_tb_scores WHERE dark=1 AND gp>60000000 AND gp<=65000000");
					while($row = $db->getNext($rs)) {
						if($row[1]>2) echo "<tr><td>60,000,000 - 65,000,000</td><td>".round($row[0])."</td><td>".$row[2]."-".$row[3]."</td>";
						else echo "<tr><td>60,000,000 - 65,000,000</td><td colspan='2'>Not Enough data</td>";
						echo "<td>".$row[1]."</td></tr>";
					}
					$rs = $db->query("SELECT avg(stars),count(stars),min(stars),max(stars) FROM swgoh_tb_scores WHERE dark=1 AND gp>65000000 AND gp<=70000000");
					while($row = $db->getNext($rs)) {
						if($row[1]>2) echo "<tr><td>65,000,000 - 70,000,000</td><td>".round($row[0])."</td><td>".$row[2]."-".$row[3]."</td>";
						else echo "<tr><td>65,000,000 - 70,000,000</td><td colspan='2'>Not Enough data</td>";
						echo "<td>".$row[1]."</td></tr>";
					}
					$rs = $db->query("SELECT avg(stars),count(stars),min(stars),max(stars) FROM swgoh_tb_scores WHERE dark=1 AND gp>70000000 AND gp<=75000000");
					while($row = $db->getNext($rs)) {
						if($row[1]>2) echo "<tr><td>70,000,000 - 75,000,000</td><td>".round($row[0])."</td><td>".$row[2]."-".$row[3]."</td>";
						else echo "<tr><td>70,000,000 - 75,000,000</td><td colspan='2'>Not Enough data</td>";
						echo "<td>".$row[1]."</td></tr>";
					}
					$rs = $db->query("SELECT avg(stars),count(stars),min(stars),max(stars) FROM swgoh_tb_scores WHERE dark=1 AND gp>75000000 AND gp<=80000000");
					while($row = $db->getNext($rs)) {
						if($row[1]>2) echo "<tr><td>75,000,000 - 80,000,000</td><td>".round($row[0])."</td><td>".$row[2]."-".$row[3]."</td>";
						else echo "<tr><td>75,000,000 - 80,000,000</td><td colspan='2'>Not Enough data</td>";
						echo "<td>".$row[1]."</td></tr>";
					}
					$rs = $db->query("SELECT avg(stars),count(stars),min(stars),max(stars) FROM swgoh_tb_scores WHERE dark=1 AND gp>80000000 AND gp<=85000000");
					while($row = $db->getNext($rs)) {
						if($row[1]>2) echo "<tr><td>80,000,000 - 85,000,000</td><td>".round($row[0])."</td><td>".$row[2]."-".$row[3]."</td>";
						else echo "<tr><td>80,000,000 - 85,000,000</td><td colspan='2'>Not Enough data</td>";
						echo "<td>".$row[1]."</td></tr>";
					}
					$rs = $db->query("SELECT avg(stars),count(stars),min(stars),max(stars) FROM swgoh_tb_scores WHERE dark=1 AND gp>85000000 AND gp<=90000000");
					while($row = $db->getNext($rs)) {
						if($row[1]>2) echo "<tr><td>85,000,000 - 90,000,000</td><td>".round($row[0])."</td><td>".$row[2]."-".$row[3]."</td>";
						else echo "<tr><td>85,000,000 - 90,000,000</td><td colspan='2'>Not Enough data</td>";
						echo "<td>".$row[1]."</td></tr>";
					}
					$rs = $db->query("SELECT avg(stars),count(stars),min(stars),max(stars) FROM swgoh_tb_scores WHERE dark=1 AND gp>90000000 AND gp<=95000000");
					while($row = $db->getNext($rs)) {
						if($row[1]>2) echo "<tr><td>90,000,000 - 95,000,000</td><td>".round($row[0])."</td><td>".$row[2]."-".$row[3]."</td>";
						else echo "<tr><td>90,000,000 - 95,000,000</td><td colspan='2'>Not Enough data</td>";
						echo "<td>".$row[1]."</td></tr>";
					}
					$rs = $db->query("SELECT avg(stars),count(stars),min(stars),max(stars) FROM swgoh_tb_scores WHERE dark=1 AND gp>95000000 AND gp<=100000000");
					while($row = $db->getNext($rs)) {
						if($row[1]>2) echo "<tr><td>95,000,000 - 100,000,000</td><td>".round($row[0])."</td><td>".$row[2]."-".$row[3]."</td>";
						else echo "<tr><td>95,000,000 - 100,000,000</td><td colspan='2'>Not Enough data</td>";
						echo "<td>".$row[1]."</td></tr>";
					}
					$rs = $db->query("SELECT avg(stars),count(stars),min(stars),max(stars) FROM swgoh_tb_scores WHERE dark=1 AND gp>100000000 AND gp<=110000000");
					while($row = $db->getNext($rs)) {
						if($row[1]>2) echo "<tr><td>100,000,000 - 110,000,000</td><td>".round($row[0])."</td><td>".$row[2]."-".$row[3]."</td>";
						else echo "<tr><td>100,000,000 - 110,000,000</td><td colspan='2'>Not Enough data</td>";
						echo "<td>".$row[1]."</td></tr>";
					}
					$rs = $db->query("SELECT avg(stars),count(stars),min(stars),max(stars) FROM swgoh_tb_scores WHERE dark=1 AND gp>110000000 AND gp<=120000000");
					while($row = $db->getNext($rs)) {
						if($row[1]>2) echo "<tr><td>110,000,000 - 120,000,000</td><td>".round($row[0])."</td><td>".$row[2]."-".$row[3]."</td>";
						else echo "<tr><td>110,000,000 - 120,000,000</td><td colspan='2'>Not Enough data</td>";
						echo "<td>".$row[1]."</td></tr>";
					}
					$rs = $db->query("SELECT avg(stars),count(stars),min(stars),max(stars) FROM swgoh_tb_scores WHERE dark=1 AND gp>120000000 AND gp<=130000000");
					while($row = $db->getNext($rs)) {
						if($row[1]>2) echo "<tr><td>120,000,000 - 130,000,000</td><td>".round($row[0])."</td><td>".$row[2]."-".$row[3]."</td>";
						else echo "<tr><td>120,000,000 - 130,000,000</td><td colspan='2'>Not Enough data</td>";
						echo "<td>".$row[1]."</td></tr>";
					}
					$rs = $db->query("SELECT avg(stars),count(stars),min(stars),max(stars) FROM swgoh_tb_scores WHERE dark=1 AND gp>130000000 AND gp<=140000000");
					while($row = $db->getNext($rs)) {
						if($row[1]>2) echo "<tr><td>130,000,000 - 140,000,000</td><td>".round($row[0])."</td><td>".$row[2]."-".$row[3]."</td>";
						else echo "<tr><td>130,000,000 - 140,000,000</td><td colspan='2'>Not Enough data</td>";
						echo "<td>".$row[1]."</td></tr>";
					}
					$rs = $db->query("SELECT avg(stars),count(stars),min(stars),max(stars) FROM swgoh_tb_scores WHERE dark=1 AND gp>140000000");
					while($row = $db->getNext($rs)) {
						if($row[1]>2) echo "<tr><td>140,000,000+</td><td>".round($row[0])."</td><td>".$row[2]."-".$row[3]."</td>";
						else echo "<tr><td>140,000,000+</td><td colspan='2'>Not Enough data</td>";
						echo "<td>".$row[1]."</td></tr>";
					}
				?>
			</table>
		</div>


	<br /><br /><hr /><ins class="adsbygoogle"
			 style="display:block; text-align:center;"
			 data-ad-format="fluid"
			 data-ad-layout="in-article"
			 data-ad-client="ca-pub-0176506581219642"
			 data-ad-slot="6732943283"></ins>
	<script>
			 (adsbygoogle = window.adsbygoogle || []).push({});
	</script>
		
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-92624-14', 'auto');
		ga('send', 'pageview');
	</script>

	</div>
	<div id="foot">
		<p>SWGOH.LIFE is not affiliated with EA, EA Capital Games, Disney or Lucasfilm LTD.</p>
	</div>
</body>
</html>