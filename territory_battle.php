<?
if(strpos($_SERVER['SERVER_NAME'], "docker")===false) {
	require_once("../vars.php");
	require_once("../libs.php");
	$db = new mymysqli("swgoh");
	error_reporting(0);
} else {
	require_once("../../vars.php");
	require_once("../libs.php");
	$db = new mymysqli("toodledo");
	error_reporting(E_ALL);
}
$self = explode("/",$_SERVER['PHP_SELF']);
$self = array_pop($self);

$gg = empty($_POST['gg']) ? "" : trim($_POST['gg']);

if(!empty($gg)) {
	$rs = $db->query("SELECT * FROM player WHERE username='".$db->str($gg)."'");
	if($row = $db->getNext($rs,1)) {
		if($row['last']<time()-(86400*3)) {
			$db->query("UPDATE player SET last = ".time()." WHERE username='".$db->str($gg)."'");
			$data = getUserFromGG($gg);
			foreach($data as $toonKey=>$toon) {	
				$flags = getToonFlags($toon['title']);
				if($flags['light']) $db->query("REPLACE INTO toons(user,toon,level,gear,stars,percent,light,phoenix,rogue,rebel) VALUES('".$db->str($gg)."','".$db->str($toon['title'])."',".intval($toon['level']).",".intval($toon['gear']).",".intval($toon['star']).",".intval($toon['percent']).",".$flags['light'].",".$flags['phoenix'].",".$flags['rogue'].",".$flags['rebel'].")");
			}
		}
	} else {
		$db->query("INSERT INTO player(username,last) VALUES('".$db->str($gg)."',".time().")");
		$data = getUserFromGG($gg);
		foreach($data as $toonKey=>$toon) {	
			$flags = getToonFlags($toon['title']);
			if($flags['light']) $db->query("REPLACE INTO toons(user,toon,level,gear,stars,percent,light,phoenix,rogue,rebel) VALUES('".$db->str($gg)."','".$db->str($toon['title'])."',".intval($toon['level']).",".intval($toon['gear']).",".intval($toon['star']).",".intval($toon['percent']).",".$flags['light'].",".$flags['phoenix'].",".$flags['rogue'].",".$flags['rebel'].")");
		}
	}
}

?><!DOCTYPE html>
<html>
	<head>
		<title>SWGOH Tools - Territory Battle Readiness</title>
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
			.tooncell {
				display: inline-block;
				padding:10px;
				border: 1px solid black;
				margin: 10px;
				text-align: center;
			}
			.tooncell b {
				display: block;
				margin-bottom: 5px;
			}
			.tooncell i {
				margin-right: 5px;
			}
			.tooncell .red {
				color: #c00;
			}
			.tooncell .green {
				color: #090;
			}
			.tooncell i.last {
				margin-right: 0px;
			}

			@media(min-width: 520px) { #top { padding-bottom:0px} #ad { height:90px;} .swgoh_ad { left:inherit; top:0; right:0; width: 328px; height: 90px; } }
			@media(min-width: 720px) { #top { padding-bottom:0px} #ad { height:90px;} .swgoh_ad { left:inherit; top:0; right:0; width: 528px; height: 90px; } }
			@media(min-width: 920px) { #top { padding-bottom:0px} #ad { height:90px;} .swgoh_ad { left:inherit; top:0; right:0; width: 728px; height: 90px; } }
			@media(min-width: 1200px) { #top { padding-bottom:0px} #ad { height:90px;} .swgoh_ad { left:inherit; top:0; right:0; width: 1000px; height: 90px; } }
  	</style>
  	<script src="https://use.fontawesome.com/c278e2b3ff.js"></script>
  	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

  	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	<script>
	$(document).ready(function() {

		var storage = null;
		if(window.localStorage) storage = window.localStorage;
		else if(window.globalStorage) storage = window.globalStorage[location.hostname];

	

		var gg = fetch("gg");
		if(gg) $('#gg').val(gg);
		$('#gg').on("change",function(e) {
			store("gg",$('#gg').val());
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
		<h1><a href="http://www.swgoh.life/index.html">More Tools</a> &gt; Territory Battle Readiness</h1>

		<? if(empty($gg)) { ?>
			<p>This tool will inspect your account and give advice on what you need to work on for Territory Battles. It needs to fetch your roster information from <a href="https://www.swgoh.gg">swgoh.gg</a> to do this. If you don't have an account there, please make one. To prevent overwhelming swgoh.gg it only fetches account information once a day.</p>
			<form action="<?=$self?>" method="post">
				<b>Your SWGOH.GG Name:</b><br />
				https://swgoh.gg/u/<input type="text" id="gg" name="gg" />/<br /><br />

				<input type="submit" value="Inspect My Account" class="btn" />
			</form>
		<? } else { ?>

			SWGOH Account: <?=$gg?> (<a href="territory_battle.php">pick another</a>)


			<br /><br /><h2>Hoth Rebel Brothers</h2>
			<p>You will need a 5<i class="fa fa-star"></i> Hoth Rebel Soldier and a 6<i class="fa fa-star"></i> Hoth Rebel Scout for a variety of missions. </p>
			<? 
				$red = 0;
				$rs = $db->query("SELECT * from toons WHERE (toon='Hoth Rebel Soldier') AND user='".$db->str($gg)."' order by toon desc");
				while($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,5)) $red++;
				}
				$rs = $db->query("SELECT * from toons WHERE (toon='Hoth Rebel Scout') AND user='".$db->str($gg)."' order by toon desc");
				while($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,6)) $red++;
				}			
			?>
			
			<br /><br /><h2>Phoenix</h2>
			<p>You will need a 6<i class="fa fa-star"></i> Phoenix squad to complete a Phase 5 Combat mission.</p>
			<? 
				$red = 0;
				$rs = $db->query("SELECT * from toons WHERE phoenix=1 AND user='".$db->str($gg)."'");
				while($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,6)) $red++;
				}			
			?>

			<br /><br /><h2>Rogue One</h2>
			<p>You will need a 7<i class="fa fa-star"></i> Rogue One squad to complete a Phase 6 Combat mission. Jyn is the only one with a leader ability, so be sure to include her in your squad.</p>
			<? 
				$red = 0;
				$rs = $db->query("SELECT * from toons WHERE rogue=1 AND user='".$db->str($gg)."'");
				while($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,7)) $red++;
				}			
			?>

			<br /><br /><h2>Special Characters</h2>
			<p>You will need a 7<i class="fa fa-star"></i> Commander Luke Skywalker, Captain Han Solo and Rebel Officer Leia Organa for the 'Special Missions' in phases 3, 5 and 6</p>
			<? 
				$red = 0;
				$rs = $db->query("SELECT * from toons WHERE (toon='Captain Han Solo' OR toon='Commander Luke Skywalker' OR toon='Rebel Officer Leia Organa') AND user='".$db->str($gg)."'");
				while($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,7)) $red++;
				}			
			?>

			<br /><br /><h2>Rebels</h2>
			<p>You will need three entire squads of Light Side characters including one Rebel Squad at 7<i class="fa fa-star"></i>, but the more rebels the better because they get a buff (Protection up if you use a special and no enemies where defeated). Dont feel like you have to use all rebels. If you have a great Resistance team, use it. Here are the rebels not already mentioned above.</p>
			<? 
				$red = 0;
				$rs = $db->query("SELECT * from toons WHERE rebel=1 AND phoenix=0 AND rogue=0 AND toon!='Hoth Rebel Soldier' AND toon!='Hoth Rebel Scout' AND toon!='Captain Han Solo' AND toon!='Commander Luke Skywalker' AND toon!='Rebel Officer Leia Organa' AND user='".$db->str($gg)."'");
				while($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,7)) $red++;
				}			
			?>

			<br /><br /><h2>Ships</h2>
			<p>You will need a fleet of 7<i class="fa fa-star"></i> light side ships. We cant display your readiness for that yet, so you'll have to check manually.</p>
		

			<br /><br /><br /><h2>Notes</h2>
			<p>Gear level 7 and Character Level 70 were chosen as the minimum requirement to be useful and will appear red above if your character is under this. Naturally, the higher the better.</p>
				
		<? } ?>


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