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

$gg = empty($_REQUEST['gg']) ? "" : trim($_REQUEST['gg']);
$manual = empty($_POST['manual']) ? "" : trim($_POST['manual']);
$manual2 = empty($_POST['manual2']) ? "" : trim($_POST['manual2']);

if(!empty($gg)) {
	$rs = $db->query("SELECT * FROM player WHERE username='ss' and username='".$db->str($gg)."'");
	if($row = $db->getNext($rs,1)) {
		if($row['last']<time()-(86400*0) && !empty($manual)  && !empty($manual2)) {
			$db->query("UPDATE player SET last = ".time()." WHERE username='".$db->str($gg)."'");
			$data = getUserFromGG($gg,$manual);
			foreach($data as $toonKey=>$toon) {	
				$flags = getToonFlags($toon['title']);
				$db->query("REPLACE INTO toons(user,toon,level,gear,stars,percent,light,phoenix,rogue,rebel,empire,bounty,trooper) VALUES('".$db->str($gg)."','".$db->str($toon['title'])."',".intval($toon['level']).",".intval($toon['gear']).",".intval($toon['star']).",".intval($toon['percent']).",".$flags['light'].",".$flags['phoenix'].",".$flags['rogue'].",".$flags['rebel'].",".$flags['empire'].",".$flags['bounty'].",".$flags['trooper'].")");
			}
			$data = getUserShipsFromGG($gg,$manual2);
			foreach($data as $toonKey=>$toon) {	
				$flags = getToonFlags($toon['title']);
				$db->query("REPLACE INTO toons(user,toon,level,gear,stars,percent,light,ship) VALUES('".$db->str($gg)."','".$db->str($toon['title'])."',".intval($toon['level']).",0,".intval($toon['star']).",".intval($toon['percent']).",".$flags['light'].",1)");
			}
		}
	} else if(!empty($manual)  && !empty($manual2)) {
		$db->query("INSERT INTO player(username,last) VALUES('".$db->str($gg)."',".time().")");
		$data = getUserFromGG($gg,$manual);

		foreach($data as $toonKey=>$toon) {	
			$flags = getToonFlags($toon['title']);
			$db->query("REPLACE INTO toons(user,toon,level,gear,stars,percent,light,phoenix,rogue,rebel,empire,bounty,trooper) VALUES('".$db->str($gg)."','".$db->str($toon['title'])."',".intval($toon['level']).",".intval($toon['gear']).",".intval($toon['star']).",".intval($toon['percent']).",".$flags['light'].",".$flags['phoenix'].",".$flags['rogue'].",".$flags['rebel'].",".$flags['empire'].",".$flags['bounty'].",".$flags['trooper'].")");
		}
		$data = getUserShipsFromGG($gg,$manual2);
		foreach($data as $toonKey=>$toon) {	
			$flags = getToonFlags($toon['title']);
			$db->query("REPLACE INTO toons(user,toon,level,gear,stars,percent,light,ship) VALUES('".$db->str($gg)."','".$db->str($toon['title'])."',".intval($toon['level']).",0,".intval($toon['star']).",".intval($toon['percent']).",".$flags['light'].",1)");
		}
	}

}

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
			textarea {
				width: 80%;
				height: 100px;
			}

a.seg {
	font-weight: normal;
	font-size: 14px;
	letter-spacing: .4px;
	padding: 7px 12px;
	background-color: #fff;
	color: rgba(189, 139, 40, 1);
	display: inline-block;
	border: 2px solid rgba(189, 139, 40, 1);
	border-radius: 3px;
	position: relative;	
	text-decoration: none;
	cursor: pointer;
	white-space: nowrap;
	margin: 0;
}

a.seg:hover  {
	text-decoration:none;
	background-color: rgba(189, 139, 40, 1);
	color: #fff;
	transition: all ease-in-out .15s;
}

.seg:focus,
.seg:active {
	outline: none;
	box-shadow: 0 0 10px rgba(189, 139, 40, .7);
}

.link_grp {
	display: flex;
	margin: 30px 0px;
}

.link_grp .seg:first-child {
	border-top-left-radius: 4px;
	border-bottom-left-radius: 4px;
}
.link_grp .seg + .seg {
	border-left: 1px;
}
.link_grp .seg {
	border-radius: 0;
	padding-right: 15px;
	padding-left: 15px;
	white-space: normal;
	text-align: center;
}
.link_grp .seg:last-child {
	border-top-right-radius: 4px;
	border-bottom-right-radius: 4px;
}

.link_grp .seg:hover {
	background-color: rgba(189, 139, 40, .7);
	color: #fff;
}
.link_grp .seg.active {
	background-color: rgba(189, 139, 40, 1);
	color: #fff;
}

.link_grp .seg:focus {
	outline: none;
	box-shadow: 0 0 10px rgba(189, 139, 40, .7);
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
		if(gg) {
			$('#gg').val(gg);
			showStep2(gg);
		}
		$('#gg').on("change",function(e) {
			var gg = $('#gg').val();
			store("gg",gg);
			showStep2(gg);
		});

		function showStep2(gg) {
			$('#step2url').attr("href","https://swgoh.gg/u/"+gg+"/collection/")
			$('#step2url2').attr("href","https://swgoh.gg/u/"+gg+"/ships/")
			$('#step2').show();
		}

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
			<p>This tool will inspect your account and give advice on what you need to work on for Territory Battles. It needs to fetch your roster information from <a href="https://www.swgoh.gg">swgoh.gg</a> to do this. If you don't have an account there, please make one. </p>
		
			<form action="<?=$self?>" method="post">
			<b>What is your SWGOH.GG Name:</b><br />
			https://swgoh.gg/u/<input type="text" id="gg" name="gg" />/ <input type="button" value="ok" /><br /><br />

			<div id="step2" style="display:none">
				<b>SWGOH.GG does not allow automatic fetching of your account information, so we'll have to do it manually <i class="fa fa-smile-o"></i>.</b>
				<br />
				<br />
				Step 1:<br />
				<a href="" id="step2url" target="_blank">Click here to open your toon collection page on swgoh.gg</a>
				<br /><br />
				Step 2:<br />
				In your web browser, find the "View Page Source" menu option and select it. You'll see a bunch of HTML code in a new window.  Copy and Paste that entire page of text into the following text box.
				<br /><br />				
				Step 3:<br />
				Copy and paste the HTML from your SWGOH.GG toon collection page here:<br />
				<textarea name="manual"></textarea>
				<br />

				Step 4:<br />
				<a href="" id="step2url2" target="_blank">Click here to open your ship collection page on swgoh.gg</a>
				<br /><br />
				Step 5:<br />
				In your web browser, find the "View Page Source" menu option and select it. You'll see a bunch of HTML code in a new window.  Copy and Paste that entire page of text into the following text box.
				<br /><br />				
				Step 6:<br />
				Copy and paste the HTML from your SWGOH.GG ship collection page here:<br />
				<textarea name="manual2"></textarea>
				<br />


				<input type="submit" value="Inspect My Account" class="btn" />
			</div>

			</form>

		<? } else if(empty($gg) && false) { ?>
			<p>This tool will inspect your account and give advice on what you need to work on for Territory Battles. It needs to fetch your roster information from <a href="https://www.swgoh.gg">swgoh.gg</a> to do this. If you don't have an account there, please make one. To prevent overwhelming swgoh.gg it only fetches account information once a day.</p>
			<form action="<?=$self?>" method="post">
				<b>Your SWGOH.GG Name:</b><br />
				https://swgoh.gg/u/<input type="text" id="gg" name="gg" />/<br /><br />

				<input type="submit" value="Inspect My Account" class="btn" />
			</form>
		<? } else if(empty($_GET['dark'])) { ?>

			SWGOH Account: <?=$gg?> (<a href="territory_battle.php">pick another</a>)

			<br />

			<div class="link_grp">
				<a href="territory_battle.php?gg=<?=$gg?>" class="seg active">Light Side</a>
				<a href="territory_battle.php?gg=<?=$gg?>&dark=1" class="seg">Dark Side</a>
			</div>

			<h2>Hoth Rebel Brothers</h2>
			<p>You will need a 5<i class="fa fa-star"></i> Hoth Rebel Soldier and a 6<i class="fa fa-star"></i> Hoth Rebel Scout for a variety of missions. </p>
			<? 
				$red = 0;
				$rs = $db->query("SELECT * from toons WHERE (toon='Hoth Rebel Soldier') AND user='".$db->str($gg)."' order by toon desc");
				while($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,5,8)) $red++;
				}
				$rs = $db->query("SELECT * from toons WHERE (toon='Hoth Rebel Scout') AND user='".$db->str($gg)."' order by toon desc");
				while($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,6,8)) $red++;
				}			
			?>
			
			<br /><br /><h2>Phoenix</h2>
			<p>You will need a 6<i class="fa fa-star"></i> Phoenix squad to complete a Phase 5 Combat mission.</p>
			<? 
				$red = 0;
				$rs = $db->query("SELECT * from toons WHERE phoenix=1 AND user='".$db->str($gg)."'");
				while($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,6,9)) $red++;
				}			
			?>

			<br /><br /><h2>Rogue One</h2>
			<p>You will need a 7<i class="fa fa-star"></i> Rogue One squad to complete a Phase 6 Combat mission. Jyn is the only one with a leader ability, so be sure to include her in your squad.</p>
			<? 
				$red = 0;
				$rs = $db->query("SELECT * from toons WHERE rogue=1 AND user='".$db->str($gg)."'");
				while($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,7,9)) $red++;
				}			
			?>

			<br /><br /><h2>Special Characters</h2>
			<p>You will need a 7<i class="fa fa-star"></i> Commander Luke Skywalker, Captain Han Solo and Rebel Officer Leia Organa for the 'Special Missions' in phases 3, 5 and 6</p>
			<? 
				$red = 0;
				$rs = $db->query("SELECT * from toons WHERE (toon='Captain Han Solo' OR toon='Commander Luke Skywalker' OR toon='Rebel Officer Leia Organa') AND user='".$db->str($gg)."'");
				while($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,7,8)) $red++;
				}			
			?>

			<br /><br /><h2>Rebels</h2>
			<p>You will need four entire squads of Light Side characters including one Rebel Squad at 7<i class="fa fa-star"></i>, but the more rebels the better because they get a buff (Protection up if you use a special and no enemies where defeated). Dont feel like you have to use all rebels. If you have a great Resistance team, use it. Here are the rebels not already mentioned above.</p>
			<? 
				$red = 0;
				$rs = $db->query("SELECT * from toons WHERE rebel=1 AND phoenix=0 AND rogue=0 AND toon!='Hoth Rebel Soldier' AND toon!='Hoth Rebel Scout' AND toon!='Captain Han Solo' AND toon!='Commander Luke Skywalker' AND toon!='Rebel Officer Leia Organa' AND user='".$db->str($gg)."'");
				while($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,7,8)) $red++;
				}			
			?>

			<br /><br /><h2>Ships</h2>
			<p>You will need six 7<i class="fa fa-star"></i> light side ships and one 6<i class="fa fa-star"></i> capital ship.</p>
			<? 
				$rs = $db->query("SELECT * from toons WHERE ship=1 AND (toon='Home One' OR toon='Endurance') AND user='".$db->str($gg)."'");
				while($row = $db->getNext($rs,1)) {
					printOneShip2($row,6);
				}			
			?>
			<br />
			<? 
				$rs = $db->query("SELECT * from toons WHERE ship=1 AND light=1 AND toon!='Home One' AND toon!='Endurance' AND user='".$db->str($gg)."'");
				while($row = $db->getNext($rs,1)) {
					printOneShip2($row,7);
				}			
			?>

			<br /><br /><br /><h2>Notes</h2>
			<p>Gear level 8,9 and Character Level 70 were chosen as the minimum requirement to be useful, depending on the character and will appear red above if your character is under this. Naturally, the higher the better.</p>
				
		<? } else { ?>

			SWGOH Account: <?=$gg?> (<a href="territory_battle.php">pick another</a>)

			<br />
			<div class="link_grp">
				<a href="territory_battle.php?gg=<?=$gg?>" class="seg">Light Side</a>
				<a href="territory_battle.php?gg=<?=$gg?>&dark=1" class="seg active">Dark Side</a>
			</div>
			
			<h2>Special Characters</h2>
			<p>You will need a 7<i class="fa fa-star"></i> Colonel Starck, General Veers and Imperial Probe Droid for the Special Mission in phase 6 and other missions. You will also need a 5<i class="fa fa-star"></i> Snowtrooper and a 4<i class="fa fa-star"></i> Darth Vader for various missions.</p>
			<? 
				$red = 0;
				$rs = $db->query("SELECT * from toons WHERE (toon='Colonel Starck' OR toon='General Veers' OR toon='Imperial Probe Droid' OR toon='Snowtrooper' OR toon='Darth Vader') AND user='".$db->str($gg)."'");
				while($row = $db->getNext($rs,1)) {
					if($row['toon']=="Snowtrooper") {
						if(printOneToon2($row,5,8)) $red++;
					} else if($row['toon']=="Darth Vader") {
						if(printOneToon2($row,4,8)) $red++;
					} else {
						if(printOneToon2($row,7,8)) $red++;
					}
				}			
			?>


			<br /><br /><h2>Bounty Hunters</h2>
			<p>You will need a 6<i class="fa fa-star"></i> Bounty Hunter squad to complete a Phase 5 Special Mission and other missions.</p>
			<? 
				$red = 0;
				$rs = $db->query("SELECT * from toons WHERE bounty=1 AND user='".$db->str($gg)."'");
				while($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,6,9)) $red++;
				}			
			?>

			<br /><br /><h2>Imperial Trooper</h2>
			<p>You will need a 5<i class="fa fa-star"></i> Imperial Trooper squad to complete a Phase 3 Special Mission with General Veers and Colonel Starck.</p>
			<? 
				$red = 0;
				$rs = $db->query("SELECT * from toons WHERE trooper=1 AND toon!='Colonel Starck' AND toon!='General Veers' AND user='".$db->str($gg)."'");
				while($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,5,9)) $red++;
				}			
			?>

			<br /><br /><h2>Empire</h2>
			<p>You will need four entire squads of Dark Side characters at 7<i class="fa fa-star"></i> including 2 full Empire squads for Phase 6. The more Empire the better because they get a buff (+Crit Chance, +Health, +Healthsteal), but don't feel like you have to use all Empire. Here are the Empire toons not already listed above.</p>
			<? 
				$red = 0;
				$rs = $db->query("SELECT * from toons WHERE empire=1 AND trooper=0 AND toon!='Imperial Probe Droid' AND toon!='Darth Vader' AND user='".$db->str($gg)."'");
				while($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,7,9)) $red++;
				}			
			?>

			<br /><br /><h2>Ships</h2>
			<p>You will need six 7<i class="fa fa-star"></i> dark side ships and one 7<i class="fa fa-star"></i> capital ship.</p>
			<? 
				$rs = $db->query("SELECT * from toons WHERE ship=1 AND (toon='Executrix' OR toon='Chimaera') AND user='".$db->str($gg)."'");
				while($row = $db->getNext($rs,1)) {
					printOneShip2($row,7);
				}			
			?>
			<br />
			<? 
				$rs = $db->query("SELECT * from toons WHERE ship=1 AND light=0 AND toon!='Executrix' AND toon!='Chimaera' AND user='".$db->str($gg)."'");
				while($row = $db->getNext($rs,1)) {
					printOneShip2($row,7);
				}			
			?>

			<br /><br /><br /><h2>Notes</h2>
			<p>Gear level 8,9 and Character Level 70 were chosen as the minimum requirement to be useful, depending on the character and will appear red above if your character is under this. Naturally, the higher the better.</p>
				
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