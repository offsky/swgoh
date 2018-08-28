<?
if(strpos($_SERVER['SERVER_NAME'], "docker")===false) {
	require_once("../vars.php");
	require_once("../libs.php");
	require_once("../api_swgoh_help3.php");
	$db = new mymysqli("swgoh");
	error_reporting(0);
} else {
	require_once("../../vars.php");
	require_once("../libs.php");
	require_once("../api_swgoh_help3.php");
	$db = new mymysqli("toodledo");
	error_reporting(E_ALL);
}


//	https://docs.google.com/document/d/1gR4l7BWevKgL9zzMlVuCsdOpyp-rPS6bvtrV4dGcY5c/mobilebasic#h.qz692fu96ngz


$self = explode("/",$_SERVER['PHP_SELF']);
$self = array_pop($self);

$ally = empty($_REQUEST['ally']) ? "" : trim($_REQUEST['ally']);
$ally = intval(preg_replace("/[^0-9]/","",$ally));
$username = "";

if(!empty($ally)) {
	$username = fetchPlayerFromSWGOHHelp($ally);
}

?><!DOCTYPE html>
<html>
	<head>
		<title>SWGOH Tools - Sith Triumvirate Raid Readiness</title>
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
				vertical-align: top;
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

  	<script src="jquery.js"></script>
	<script>
	$(document).ready(function() {

		var storage = null;
		if(window.localStorage) storage = window.localStorage;
		else if(window.globalStorage) storage = window.globalStorage[location.hostname];

		var ally = fetch("ally");
		if(ally) {
			$('#ally').val(ally);
		}
		$('#ally').on("change",function(e) {
			var ally = $('#ally').val();
			store("ally",ally);
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

		<h1><a href="http://www.swgoh.life/index.html">More Tools</a> &gt; Sith Triumvirate Raid Readiness</h1>

		<? if(empty($ally)) { ?>
			<p>This tool will inspect your account and give advice on what you need to work on for the Sith Triumvirate Raid. To find your ally code, open the game to the main screen and tap on your name in the upper left corner. Then look below your name for a 9 digit number.</p>
		
			<form action="<?=$self?>" method="get">
			<b>What is your SWGOH Ally Code:</b><br />
			<input type="text" id="ally" name="ally" placeholder="123-456-789" /> <input type="submit" value="ok" />
			</form>

		<? } else { ?>

			Account: <?=$username?> (<?=$ally?>) (<a href="raid_sith.php">pick another</a>)

			<br />
			<p>The goal of this tool is to show you the optimal minimum number of squads that you will need in order to contribute your 2% share of damage towards the Raid. There are many teams that can work for the Sith Triumvirate Raid beyond what is displayed here. This tool has simply picked the best known team for each phase and as better teams are discovered this will be updated.</p>

			<h2>Phase 1</h2>

			<p>Jedi Training Rey and Friends can do 4% or more of phase 1. Required Zetas: JTR Lead, BB8 Roll With the Punches. Optional Zetas: JTR Insight, R2D2 Combat Analysis. The main idea is to use specials on Darth Nihilus to prevent his protection regeneration. If you have to use a basic attack attack one of the Assassins. You need to time the shield carefully to protect you from Annihilate. Videos <a href="https://www.youtube.com/watch?v=43PPNO8MfR8&feature=youtu.be&t=1m">Here</a> and <a href="https://www.youtube.com/watch?v=z1a5hqTKYUI&feature=youtu.be">Here</a>.</p>
			<? 
				$red = 0;
				$rs = $db->query("SELECT * from toons WHERE (toon='Rey (Jedi Training)') AND user='".$db->str($ally)."' order by toon desc");
				if($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,7,11)) $red++;
				} else {
					printEmptyToon("Rey (Jedi Training)");
					$red++;
				}
				$rs = $db->query("SELECT * from toons WHERE (toon='BB-8') AND user='".$db->str($ally)."' order by toon desc");
				if($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,7,11)) $red++;
				} else {
					printEmptyToon("BB-8");
					$red++;
				}
				$rs = $db->query("SELECT * from toons WHERE (toon='Resistance Trooper') AND user='".$db->str($ally)."' order by toon desc");
				if($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,7,11)) $red++;
				} else {
					printEmptyToon("Resistance Trooper");
					$red++;
				}

				$rs = $db->query("SELECT * from toons WHERE (toon='R2-D2') AND user='".$db->str($ally)."' order by toon desc");
				$row1 = $db->getNext($rs,1);
				$rs = $db->query("SELECT * from toons WHERE (toon='Visas Marr') AND user='".$db->str($ally)."' order by toon desc");
				$row2 = $db->getNext($rs,1);
				if($row1 && $row2) {
					if(printTwoToons($row1,$row2,7,11)) $red++;
				} else if($row1) {
					if(printOneToon2($row1,7,11)) $red++;
				} else if($row2) {
					if(printOneToon2($row2,7,11)) $red++;
				} else {
					printEmptyToon("R2-D2");
					$red++;
				}

				
				$rs = $db->query("SELECT * from toons WHERE (toon='Rey (Scavenger)') AND user='".$db->str($ally)."' order by toon desc");
				$row1 = $db->getNext($rs,1);
				$rs = $db->query("SELECT * from toons WHERE (toon='Barriss Offee') AND user='".$db->str($ally)."' order by toon desc");
				$row2 = $db->getNext($rs,1);
				$rs = $db->query("SELECT * from toons WHERE (toon='Hermit Yoda') AND user='".$db->str($ally)."' order by toon desc");
				$row3 = $db->getNext($rs,1);
				if($row1 && $row2 && $row3) {
					if(printThreeToons($row1,$row2,$row3,7,11)) $red++;
				} else if($row1 && $row2) {
					if(printTwoToons($row1,$row2,7,11)) $red++;
				} else if($row1 && $row3) {
					if(printTwoToons($row1,$row3,7,11)) $red++;
				} else if($row2 && $row3) {
					if(printTwoToons($row2,$row3,7,11)) $red++;
				} else if($row1) {
					if(printOneToon2($row1,7,11)) $red++;
				} else if($row2) {
					if(printOneToon2($row2,7,11)) $red++;
				} else if($row3) {
					if(printOneToon2($row3,7,11)) $red++;
				} else {
					printEmptyToon("Rey (Scavenger)");
					$red++;
				}

				// if($red==0) echo "<br />You have this squad ready to go. Make sure you have the right zetas and good luck!";
				// else echo "<br />You are missing one more more of these toons.";				
			?>
			
			<br /><br /><h2>Phase 2</h2>
			<p>The general strategy is to keep Pain on your attackers to boost their damage but cleans them from your other toons. This phase is the biggest grind.</p>

			<p>Phoenix (minus Chooper) can do 1-2%. Zetas in this order of importance: Sabine, Kanan, Hera, Ezra, Zeb. Video <a href="https://www.youtube.com/watch?v=KLFZMrLUqZA&feature=youtu.be">here</a>.</p>
			<? 
				$red = 0;
				$green = 0;
				$rs = $db->query("SELECT * from toons WHERE phoenix=1 AND (toon!='Chopper') AND user='".$db->str($ally)."'");
				while($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,7,10)) $red++;
					else $green++;
				}
				// if($green==5) echo "<br />You have this squad ready to go. Make sure you have the right zetas and good luck!";
				// else echo "<br />You are missing one more more of these toons.";
			?>

			<p>Imperial Troopers can do 1-2%. Zeta Required: Veers unique</p>
			<? 
				$red = 0;
				$rs = $db->query("SELECT * from toons WHERE (toon='General Veers') AND user='".$db->str($ally)."' order by toon desc");
				if($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,7,11)) $red++;
				} else {
					printEmptyToon("General Veers");
					$red++;
				}
				$rs = $db->query("SELECT * from toons WHERE (toon='Grand Admiral Thrawn') AND user='".$db->str($ally)."' order by toon desc");
				if($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,7,11)) $red++;
				} else {
					printEmptyToon("Grand Admiral Thrawn");
					$red++;
				}
				$rs = $db->query("SELECT * from toons WHERE (toon='Shoretrooper') AND user='".$db->str($ally)."' order by toon desc");
				if($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,7,11)) $red++;
				} else {
					printEmptyToon("Shoretrooper");
					$red++;
				}
				$rs = $db->query("SELECT * from toons WHERE (toon='Colonel Starck') AND user='".$db->str($ally)."' order by toon desc");
				if($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,7,11)) $red++;
				} else {
					printEmptyToon("Colonel Starck");
					$red++;
				}
				$rs = $db->query("SELECT * from toons WHERE (toon='Snowtrooper') AND user='".$db->str($ally)."' order by toon desc");
				if($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,7,11)) $red++;
				} else {
					printEmptyToon("Snowtrooper");
					$red++;
				}
				
				// if($red==0) echo "<br />You have this squad ready to go. Make sure you have the right zetas and good luck!";
				// else echo "<br />You are missing one more more of these toons.";
			?>

			<br /><br /><h2>Phase 3</h2>
			<p>This team is called "Chex Mix" and can do over 4%. The idea is to make Han Solo slow and give him offense up, crit chance up and crit damage up and apply deathmark to Traya. Then have Han Solo use "Standalone". His counter attacks will crit for 100k each. Its a very fast team. Required Zeta: Han Solo's Shoots First. Optional Zetas: CLS Lead, It Binds all Things. Video <a href="https://www.youtube.com/watch?v=Q53Qgxgs09w&feature=youtu.be">here</a></p>
			<? 
				$red = 0;
				$rs = $db->query("SELECT * from toons WHERE (toon='Commander Luke Skywalker') AND user='".$db->str($ally)."' order by toon desc");
				if($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,7,11)) $red++;
				} else {
					printEmptyToon("Commander Luke Skywalker");
					$red++;
				}
				$rs = $db->query("SELECT * from toons WHERE (toon='Han Solo') AND user='".$db->str($ally)."' order by toon desc");
				if($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,7,11)) $red++;
				} else {
					printEmptyToon("Han Solo");
					$red++;
				}
				$rs = $db->query("SELECT * from toons WHERE (toon='Death Trooper') AND user='".$db->str($ally)."' order by toon desc");
				if($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,7,11)) $red++;
				} else {
					printEmptyToon("Death Trooper");
					$red++;
				}

				$rs = $db->query("SELECT * from toons WHERE (toon='Chirrut Îmwe') AND user='".$db->str($ally)."' order by toon desc");
				$row1 = $db->getNext($rs,1);
				$rs = $db->query("SELECT * from toons WHERE (toon='CT-7567 \"Rex\"') AND user='".$db->str($ally)."' order by toon desc");
				$row2 = $db->getNext($rs,1);
				if($row1 && $row2) {
					if(printTwoToons($row1,$row2,7,10)) $red++;
				} else if($row1) {
					if(printOneToon2($row1,7,10)) $red++;
				} else if($row2) {
					if(printOneToon2($row2,7,10)) $red++;
				} else {
					printEmptyToon("Chirrut Îmwe");
					$red++;
				}

				
				$rs = $db->query("SELECT * from toons WHERE (toon='Pao') AND user='".$db->str($ally)."' order by toon desc");
				$row1 = $db->getNext($rs,1);
				$rs = $db->query("SELECT * from toons WHERE (toon='Jedi Knight Anakin') AND user='".$db->str($ally)."' order by toon desc");
				$row2 = $db->getNext($rs,1);
				$rs = $db->query("SELECT * from toons WHERE (toon='Poggle the Lesser') AND user='".$db->str($ally)."' order by toon desc");
				$row3 = $db->getNext($rs,1);
				if($row1 && $row2 && $row3) {
					if(printThreeToons($row1,$row2,$row3,7,10)) $red++;
				} else if($row1 && $row2) {
					if(printTwoToons($row1,$row2,7,10)) $red++;
				} else if($row1 && $row3) {
					if(printTwoToons($row1,$row3,7,10)) $red++;
				} else if($row2 && $row3) {
					if(printTwoToons($row2,$row3,7,10)) $red++;
				} else if($row1) {
					if(printOneToon2($row1,7,10)) $red++;
				} else if($row2) {
					if(printOneToon2($row2,7,10)) $red++;
				} else if($row3) {
					if(printOneToon2($row3,7,10)) $red++;
				} else {
					printEmptyToon("Pao");
					$red++;
				}
				// if($red==0) echo "<br />You have this squad ready to go. Make sure you have the right zetas and good luck!";
				// else echo "<br />You are missing one more more of these toons.";
			?>

			<br /><br /><h2>Phase 4</h2>
			<p>You can use your JTR team from Phase 1 here if you still have it, otherwise Night Sisters can take out up to 10% of Darth Nihilus's health and some of Sion's as a bonus. Its important to avoid hitting Nihilus with a basic to prevent him from regaining protection. Required Zeta: Asajj leadership. Optional Zetas: Asajj Unique, Talzin Unique, Daka Unique. Video <a href="https://www.youtube.com/watch?v=l3tJvYhOuvg&feature=youtu.be">here</a>.</p>
			<? 
				$red = 0;
				$rs = $db->query("SELECT * from toons WHERE (toon='Asajj Ventress') AND user='".$db->str($ally)."' order by toon desc");
				if($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,7,11)) $red++;
				} else {
					printEmptyToon("Asajj Ventress");
					$red++;
				}
				$rs = $db->query("SELECT * from toons WHERE (toon='Old Daka') AND user='".$db->str($ally)."' order by toon desc");
				if($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,7,11)) $red++;
				} else {
					printEmptyToon("Old Daka");
					$red++;
				}
		
				$rs = $db->query("SELECT * from toons WHERE (toon='Talia') AND user='".$db->str($ally)."' order by toon desc");
				if($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,7,11)) $red++;
				} else {
					printEmptyToon("Talia");
					$red++;
				}

				$rs = $db->query("SELECT * from toons WHERE (toon='Nightsister Acolyte') AND user='".$db->str($ally)."' order by toon desc");
				$row1 = $db->getNext($rs,1);
				$rs = $db->query("SELECT * from toons WHERE (toon='Nightsister Zombie') AND user='".$db->str($ally)."' order by toon desc");
				$row2 = $db->getNext($rs,1);
				if($row1 && $row2) {
					if(printTwoToons($row1,$row2,7,10)) $red++;
				} else if($row1) {
					if(printOneToon2($row1,7,10)) $red++;
				} else if($row2) {
					if(printOneToon2($row2,7,10)) $red++;
				} else {
					printEmptyToon("Nightsister Acolyte");
					$red++;
				}

				$rs = $db->query("SELECT * from toons WHERE (toon='Mother Talzin') AND user='".$db->str($ally)."' order by toon desc");
				$row1 = $db->getNext($rs,1);
				$rs = $db->query("SELECT * from toons WHERE (toon='Nightsister Initiate') AND user='".$db->str($ally)."' order by toon desc");
				$row2 = $db->getNext($rs,1);
				if($row1 && $row2) {
					if(printTwoToons($row1,$row2,7,10)) $red++;
				} else if($row1) {
					if(printOneToon2($row1,7,10)) $red++;
				} else if($row2) {
					if(printOneToon2($row2,7,10)) $red++;
				} else {
					printEmptyToon("Mother Talzin");
					$red++;
				}

				// if($red==0) echo "<br />You have this squad ready to go. Make sure you have the right zetas and good luck!";
				// else echo "<br />You are missing one more more of these toons.";
			?>

			<p>First Order, lead by Kylo Ren Unmasked can remove 2% or more from Sion once Darth Nihilus has been defeated. The main strategy is to remove Isolate when you can and remove Cycle of Pain when it's above 20.</p>
			<? 
				$red = 0;
				$rs = $db->query("SELECT * from toons WHERE (toon='Kylo Ren (Unmasked)') AND user='".$db->str($ally)."' order by toon desc");
				if($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,7,11)) $red++;
				} else {
					printEmptyToon("Kylo Ren (Unmasked)");
					$red++;
				}
				$rs = $db->query("SELECT * from toons WHERE (toon='First Order Stormtrooper') AND user='".$db->str($ally)."' order by toon desc");
				if($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,7,11)) $red++;
				} else {
					printEmptyToon("First Order Storm Trooper");
					$red++;
				}
				$rs = $db->query("SELECT * from toons WHERE (toon='First Order Officer') AND user='".$db->str($ally)."' order by toon desc");
				if($row = $db->getNext($rs,1)) {
					if(printOneToon2($row,7,11)) $red++;
				} else {
					printEmptyToon("First Order Officer");
					$red++;
				}
				
				$rs = $db->query("SELECT * from toons WHERE (toon='First Order Executioner') AND user='".$db->str($ally)."' order by toon desc");
				$row1 = $db->getNext($rs,1);
				$rs = $db->query("SELECT * from toons WHERE (toon='Kylo Ren') AND user='".$db->str($ally)."' order by toon desc");
				$row2 = $db->getNext($rs,1);
				if($row1 && $row2) {
					if(printTwoToons($row1,$row2,7,10)) $red++;
				} else if($row1) {
					if(printOneToon2($row1,7,10)) $red++;
				} else if($row2) {
					if(printOneToon2($row2,7,10)) $red++;
				} else {
					printEmptyToon("First Order Executioner");
					$red++;
				}

				$rs = $db->query("SELECT * from toons WHERE (toon='First Order SF TIE Pilot') AND user='".$db->str($ally)."' order by toon desc");
				$row1 = $db->getNext($rs,1);
				$rs = $db->query("SELECT * from toons WHERE (toon='First Order TIE Pilot') AND user='".$db->str($ally)."' order by toon desc");
				$row2 = $db->getNext($rs,1);
				if($row1 && $row2) {
					if(printTwoToons($row1,$row2,7,10)) $red++;
				} else if($row1) {
					if(printOneToon2($row1,7,10)) $red++;
				} else if($row2) {
					if(printOneToon2($row2,7,10)) $red++;
				} else {
					printEmptyToon("First Order SF TIE Pilot");
					$red++;
				}

				// if($red==0) echo "<br />You have this squad ready to go. Make sure you have the right zetas and good luck!";
				// else echo "<br />You are missing one more more of these toons.";
			?>

			
		
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