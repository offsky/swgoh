<?
if(strpos($_SERVER['SERVER_NAME'], "docker")===false) {
	require_once("../vars.php");
	require_once("../libs.php");
	require_once("../api_swgoh_help2.php");
	$db = new mymysqli("swgoh");
	error_reporting(0);
} else {
	require_once("../../vars.php");
	require_once("../libs.php");
	require_once("../api_swgoh_help2.php");
	$db = new mymysqli("toodledo");
	error_reporting(E_ALL);
}

$numberOftoons = 15;

$self = explode("/",$_SERVER['PHP_SELF']);
$self = array_pop($self);

$ally = empty($_REQUEST['ally']) ? "" : trim($_REQUEST['ally']);
$ally = intval(preg_replace("/[^0-9]/","",$ally));

if(!empty($ally)) {
	$username = fetchPlayerFromSWGOHHelp($ally);
}

?><!DOCTYPE html>
<html>
	<head>
		<title>SWGOH Tools - Farming Priority List Maker</title>
 		<meta name="viewport" content="width=device-width, initial-scale=1">
	  	<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
		<meta charset="utf-8" />
		<style>
  h3 {
    margin-top: 50px;
  }
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
	.tooncell .dim {
		color: #444;
		font-size: 0.9em;
	}
	.tooncell i.last {
		margin-right: 0px;
	}

	#restoremsg {
		display: none;
	}
	table.styled {
		border-collapse: collapse;
	}
	table.styled thead td {
		padding: 4px 4px 7px 4px;
		border-bottom:2px solid black;
	}

	table.styled tbody td {
		padding: 2px;
		white-space: nowrap;
		border-bottom:1px solid #999;
		border-left:1px dotted #999;
		border-right:1px dotted #999;
	}
	table.styled tbody td:last-child {
		border-right:0px solid #999;
	}
	table.styled tbody td:first-child {
		border-left:0px solid #999;
	}
	table.styled a {
		text-decoration: none;
	}
	table.styled a:hover {
		text-decoration: underline;
	}
	table .fa {
		margin-left: 2px;
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

		var id2Name = {"AAYLASECURA":"Aayla Secura","ADMIRALACKBAR":"Admiral Ackbar","AHSOKATANO":"Ahsoka Tano","FULCRUMAHSOKA":"Ahsoka Tano (Fulcrum)","AMILYNHOLDO":"Amilyn Holdo","ASAJVENTRESS":"Asajj Ventress","B2SUPERBATTLEDROID":"B2 Super Battle Droid","BARRISSOFFEE":"Barriss Offee","BASTILASHAN":"Bastila Shan","BAZEMALBUS":"Baze Malbus","BB8":"BB-8","BIGGSDARKLIGHTER":"Biggs Darklighter","BISTAN":"Bistan","BOBAFETT":"Boba Fett","BODHIROOK":"Bodhi Rook","BOSSK":"Bossk","CADBANE":"Cad Bane","HOTHHAN":"Captain Han Solo","PHASMA":"Captain Phasma","CASSIANANDOR":"Cassian Andor","CC2224":"CC-2224 \"Cody\"","CHIEFCHIRPA":"Chief Chirpa","CHIEFNEBIT":"Chief Nebit","CHIRRUTIMWE":"Chirrut Îmwe","CHOPPERS3":"Chopper","CLONESERGEANTPHASEI":"Clone Sergeant - Phase I","CLONEWARSCHEWBACCA":"Clone Wars Chewbacca","COLONELSTARCK":"Colonel Starck","COMMANDERLUKESKYWALKER":"Commander Luke Skywalker","CORUSCANTUNDERWORLDPOLICE":"Coruscant Underworld Police","COUNTDOOKU":"Count Dooku","CT210408":"CT-21-0408 \"Echo\"","CT5555":"CT-5555 \"Fives\"","CT7567":"CT-7567 \"Rex\"","MAUL":"Darth Maul","DARTHNIHILUS":"Darth Nihilus","DARTHSIDIOUS":"Darth Sidious","DARTHSION":"Darth Sion","DARTHTRAYA":"Darth Traya","VADER":"Darth Vader","DATHCHA":"Dathcha","DEATHTROOPER":"Death Trooper","DENGAR":"Dengar","DIRECTORKRENNIC":"Director Krennic","EETHKOTH":"Eeth Koth","EMBO":"Embo","EMPERORPALPATINE":"Emperor Palpatine","ENFYSNEST":"Enfys Nest","EWOKELDER":"Ewok Elder","EWOKSCOUT":"Ewok Scout","EZRABRIDGERS3":"Ezra Bridger","FINN":"Finn","FIRSTORDEREXECUTIONER":"First Order Executioner","FIRSTORDEROFFICERMALE":"First Order Officer","FIRSTORDERSPECIALFORCESPILOT":"First Order SF TIE Pilot","FIRSTORDERTROOPER":"First Order Stormtrooper","FIRSTORDERTIEPILOT":"First Order TIE Pilot","GAMORREANGUARD":"Gamorrean Guard","GARSAXON":"Gar Saxon","ZEBS3":"Garazeb \"Zeb\" Orrelios","GRIEVOUS":"General Grievous","GENERALKENOBI":"General Kenobi","VEERS":"General Veers","GEONOSIANSOLDIER":"Geonosian Soldier","GEONOSIANSPY":"Geonosian Spy","GRANDADMIRALTHRAWN":"Grand Admiral Thrawn","GRANDMASTERYODA":"Grand Master Yoda","GRANDMOFFTARKIN":"Grand Moff Tarkin","GREEDO":"Greedo","HANSOLO":"Han Solo","HERASYNDULLAS3":"Hera Syndulla","HERMITYODA":"Hermit Yoda","HK47":"HK-47","HOTHREBELSCOUT":"Hoth Rebel Scout","HOTHREBELSOLDIER":"Hoth Rebel Soldier","MAGNAGUARD":"IG-100 MagnaGuard","IG86SENTINELDROID":"IG-86 Sentinel Droid","IG88":"IG-88","IMAGUNDI":"Ima-Gun Di","IMPERIALPROBEDROID":"Imperial Probe Droid","IMPERIALSUPERCOMMANDO":"Imperial Super Commando","JAWA":"Jawa","JAWAENGINEER":"Jawa Engineer","JAWASCAVENGER":"Jawa Scavenger","JEDIKNIGHTCONSULAR":"Jedi Consular","ANAKINKNIGHT":"Jedi Knight Anakin","JEDIKNIGHTGUARDIAN":"Jedi Knight Guardian","JOLEEBINDO":"Jolee Bindo","JYNERSO":"Jyn Erso","K2SO":"K-2SO","KANANJARRUSS3":"Kanan Jarrus","KITFISTO":"Kit Fisto","KYLOREN":"Kylo Ren","KYLORENUNMASKED":"Kylo Ren (Unmasked)","L3_37":"L3-37","ADMINISTRATORLANDO":"Lando Calrissian","LOBOT":"Lobot","LOGRAY":"Logray","LUKESKYWALKER":"Luke Skywalker (Farmboy)","LUMINARAUNDULI":"Luminara Unduli","MACEWINDU":"Mace Windu","MAGMATROOPER":"Magmatrooper","MISSIONVAO":"Mission Vao","HUMANTHUG":"Mob Enforcer","MOTHERTALZIN":"Mother Talzin","NIGHTSISTERACOLYTE":"Nightsister Acolyte","NIGHTSISTERINITIATE":"Nightsister Initiate","NIGHTSISTERSPIRIT":"Nightsister Spirit","NIGHTSISTERZOMBIE":"Nightsister Zombie","NUTEGUNRAY":"Nute Gunray","OLDBENKENOBI":"Obi-Wan Kenobi (Old Ben)","DAKA":"Old Daka","PAO":"Pao","PAPLOO":"Paploo","PLOKOON":"Plo Koon","POE":"Poe Dameron","POGGLETHELESSER":"Poggle the Lesser","PRINCESSLEIA":"Princess Leia","QIRA":"Qi'ra","QUIGONJINN":"Qui-Gon Jinn","R2D2_LEGENDARY":"R2-D2","RANGETROOPER":"Range Trooper","HOTHLEIA":"Rebel Officer Leia Organa","RESISTANCEPILOT":"Resistance Pilot","RESISTANCETROOPER":"Resistance Trooper","REYJEDITRAINING":"Rey (Jedi Training)","REY":"Rey (Scavenger)","ROSETICO":"Rose Tico","ROYALGUARD":"Royal Guard","SABINEWRENS3":"Sabine Wren","SAVAGEOPRESS":"Savage Opress","SCARIFREBEL":"Scarif Rebel Pathfinder","SHORETROOPER":"Shoretrooper","SITHASSASSIN":"Sith Assassin","SITHMARAUDER":"Sith Marauder","SITHTROOPER":"Sith Trooper","SNOWTROOPER":"Snowtrooper","STORMTROOPER":"Stormtrooper","STORMTROOPERHAN":"Stormtrooper Han","SUNFAC":"Sun Fac","T3_M4":"T3-M4","TALIA":"Talia","TEEBO":"Teebo","TIEFIGHTERPILOT":"TIE Fighter Pilot","TUSKENRAIDER":"Tusken Raider","TUSKENSHAMAN":"Tusken Shaman","UGNAUGHT":"Ugnaught","URORRURRR":"URoRRuR'R'R","YOUNGCHEWBACCA":"Vandor Chewbacca","SMUGGLERCHEWBACCA":"Veteran Smuggler Chewbacca","SMUGGLERHAN":"Veteran Smuggler Han Solo","VISASMARR":"Visas Marr","WAMPA":"Wampa","WEDGEANTILLES":"Wedge Antilles","WICKET":"Wicket","YOUNGHAN":"Young Han Solo","YOUNGLANDO":"Young Lando Calrissian","ZAALBAR":"Zaalbar","ZAMWESELL":"Zam Wesell"};
		//ALSO UPDATE BELOW IN PHP
		// var names = []; //Take from https://swgoh.gg/api/characters/
		// var fixed = {};
		// names.forEach(function(toon) {
		// 	fixed[toon.base_id] = toon.name;
		// });
		// console.log(JSON.stringify(fixed));

		var ally = fetch("ally");
		if(ally) {
			$('#ally').val(ally);
		}
		$('#ally').on("change",function(e) {
			var ally = $('#ally').val();
			store("ally",ally);
		});

		//use data
		function init() {
			fillSelects();
		}

		init();

		function fillSelects() {
			var inputs = $('.toonselect');
			var names = [];

			for(var toon in id2Name) {
				var niceName = id2Name[toon];
				if(niceName==undefined || niceName=='') niceName = toon;
				names.push(niceName+"**"+toon);
			}
			names.sort();
			$.each(names,function(index,value) {
				var parts = value.split("**");
				var option = '<option value="'+parts[1]+'">'+parts[0]+'</option>';
				inputs.append(option);
			});

			for(var i=1;i<=<?=$numberOftoons?>;i++) {
				$('#toon'+i+' option[value="'+$('#toon'+i+'').data("pre")+'"]').prop("selected", "selected");
			}

		}

	
		// $('#toon1').on("change",runReport);
		// $('#toon2').on("change",runReport);
		// $('#toon3').on("change",runReport);
		// $('#toon4').on("change",runReport);
		// $('#toon5').on("change",runReport);
		
		function printToon(toon) {
			if(toon==undefined) return "";
			return toon[0]+"<i class='fa fa-star'></i> "+toon[1]+"<i class='fa fa-cog'></i> "+toon[2]+"<i class='fa fa-angle-double-up'></i> "+numberWithCommas(toon[3])+"<i class='fa fa-bolt last'></i>";
		}

		//helpers
		function numberWithCommas(x) {
  			return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
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

		<h1><a href="http://www.swgoh.life/index.html">More Tools</a> &gt; Farming Priority List Maker</h1>

		<? if(empty($ally)) { ?>

			<p>This tool will allow you to make a list of farming priorities for personal use, or to share with your guild. To find your ally code, open the game to the main screen and tap on your name in the upper left corner. Then look below your name for a 9 digit number.</p>

			<form action="<?=$self?>" method="get">
			<b>What is your SWGOH Ally Code:</b><br />
			<input type="text" id="ally" name="ally" placeholder="123-456-789" /> 
			<br /><br />
			<b>Pick the <?=$numberOftoons?> characters that you want to work on:</b><br />
			<? for($i=1;$i<=$numberOftoons;$i++) { ?>
				<select class="toonselect" name="t<?=$i?>" id="toon<?=$i?>" data-pre="<?=empty($_GET['t'.$i])?"":$_GET['t'.$i]?>"><option value="">PICK TOON</option></select><br />
			<? } ?>
			<br />
			<input type="submit" value="Show Me" class="btn" />
			</form>

		<? } else { ?>
					
			<p>Here is your farming priority list. Bookmark this page to return quickly and see your progress. You can share also this with your guild by copy and pasting the link in the text box below.</p>
			<?
			unset($_GET['ally']);
			$query = http_build_query($_GET);
			?>
			Account: <?=$username?> (<?=$ally?>) (<a href="priority.php?<?=$query?>">pick another</a>)<br />
			<b>Share:</b><input type="text" value="https://shard.swgoh.life/priority.php?<?=$query?>" />
			<br />
			<?
				if(0) { //this needs to be done locally and then have data copy and pasted into source for production
					$toons = array();
					$rs = $db->query("SELECT src,title,guild_store,cantina_store,arena_store,fleet_store,gw_store,cantina,hard,gear_score FROM swgoh_toons order by title asc");
					while($row = $db->getNext($rs,1)) {

						$perday = 0;
						if($row['guild_store']) $perday+=3; //https://docs.google.com/spreadsheets/d/1Q-UyeOukxolBDsVXqpKcpwyd1tI6awdZWqx5Re4bl3o/htmlview#
						if($row['cantina_store']) $perday+=7;
						if($row['arena_store']) $perday+=10;
						if($row['fleet_store']) $perday+=2;
						if($row['gw_store']) $perday+=15;

						if($row['cantina']>0) { //assumes 2 refreshes
							$perday+=ceil((120*3+45)/($row['cantina']*4)); //assumes 25% drop rate (thats the 4)
						}

						if($row['hard']) $perday+=$row['hard'];

						$toons[toonName($row['title'])] = $perday;
					}
					echo base64_encode(json_encode($toons))."<br />";
				} else {
					$perday = json_decode(base64_decode("eyJBYXlsYSBTZWN1cmEiOjksIkFkbWlyYWwgQWNrYmFyIjoxMiwiQWhzb2thIFRhbm8iOjExLCJBaHNva2EgVGFubyAoRnVsY3J1bSkiOjAsIkFtaWx5biBIb2xkbyI6MSwiQXNhamogVmVudHJlc3MiOjEwLCJCMiBTdXBlciBCYXR0bGUgRHJvaWQiOjksIkJhcnJpc3MgT2ZmZWUiOjExLCJCYXN0aWxhIFNoYW4iOjAsIkJhemUgTWFsYnVzIjoxLCJCQi04IjowLCJCaWdncyBEYXJrbGlnaHRlciI6MjksIkJpc3RhbiI6OSwiQm9iYSBGZXR0IjoxMSwiQm9kaGkgUm9vayI6MTUsIkJvc3NrIjoxLCJDYWQgQmFuZSI6MTUsIkNhcHRhaW4gSGFuIFNvbG8iOjcsIkNhcHRhaW4gUGhhc21hIjoxNSwiQ2Fzc2lhbiBBbmRvciI6MTAsIkNDLTIyMjQgXCJDb2R5XCIiOjUsIkNoaWVmIENoaXJwYSI6OSwiQ2hpZWYgTmViaXQiOjEwLCJDaGlycnV0IFx1MDBjZW13ZSI6MiwiQ2hvcHBlciI6NywiQ2xvbmUgU2VyZ2VhbnQgLSBQaGFzZSBJIjozLCJDbG9uZSBXYXJzIENoZXdiYWNjYSI6MTUsIkNvbG9uZWwgU3RhcmNrIjozLCJDb21tYW5kZXIgTHVrZSBTa3l3YWxrZXIiOjAsIkNvcnVzY2FudCBVbmRlcndvcmxkIFBvbGljZSI6MTAsIkNvdW50IERvb2t1IjoxMSwiQ1QtMjEtMDQwOCBcIkVjaG9cIiI6NSwiQ1QtNTU1NSBcIkZpdmVzXCIiOjgsIkNULTc1NjcgXCJSZXhcIiI6NSwiRGFydGggTWF1bCI6NSwiRGFydGggTmloaWx1cyI6MSwiRGFydGggU2lkaW91cyI6MTAsIkRhcnRoIFNpb24iOjEsIkRhcnRoIFRyYXlhIjowLCJEYXJ0aCBWYWRlciI6MiwiRGF0aGNoYSI6MTcsIkRlYXRoIFRyb29wZXIiOjcsIkRlbmdhciI6MywiRGlyZWN0b3IgS3Jlbm5pYyI6MSwiRWV0aCBLb3RoIjoxMCwiRW1ibyI6MCwiRW1wZXJvciBQYWxwYXRpbmUiOjAsIkVuZnlzIE5lc3QiOjEsIkV3b2sgRWxkZXIiOjUsIkV3b2sgU2NvdXQiOjIsIkV6cmEgQnJpZGdlciI6MTUsIkZpbm4iOjEyLCJGaXJzdCBPcmRlciBFeGVjdXRpb25lciI6MTMsIkZpcnN0IE9yZGVyIE9mZmljZXIiOjcsIkZpcnN0IE9yZGVyIFNGIFRJRSBQaWxvdCI6MywiRmlyc3QgT3JkZXIgU3Rvcm10cm9vcGVyIjoyLCJGaXJzdCBPcmRlciBUSUUgUGlsb3QiOjIsIkdhbW9ycmVhbiBHdWFyZCI6MywiR2FyIFNheG9uIjo3LCJHYXJhemViIFwiWmViXCIgT3JyZWxpb3MiOjE1LCJHZW5lcmFsIEdyaWV2b3VzIjoyLCJHZW5lcmFsIEtlbm9iaSI6MCwiR2VuZXJhbCBWZWVycyI6MywiR2Vvbm9zaWFuIFNvbGRpZXIiOjE1LCJHZW9ub3NpYW4gU3B5IjoxMywiR3JhbmQgQWRtaXJhbCBUaHJhd24iOjAsIkdyYW5kIE1hc3RlciBZb2RhIjowLCJHcmFuZCBNb2ZmIFRhcmtpbiI6MTIsIkdyZWVkbyI6MTAsIkhhbiBTb2xvIjowLCJIZXJhIFN5bmR1bGxhIjoxMywiSGVybWl0IFlvZGEiOjAsIkhLLTQ3IjoxMCwiSG90aCBSZWJlbCBTY291dCI6OSwiSG90aCBSZWJlbCBTb2xkaWVyIjoxLCJJRy0xMDAgTWFnbmFHdWFyZCI6MTIsIklHLTg2IFNlbnRpbmVsIERyb2lkIjoyOCwiSUctODgiOjEwLCJJbWEtR3VuIERpIjoxMiwiSW1wZXJpYWwgUHJvYmUgRHJvaWQiOjAsIkltcGVyaWFsIFN1cGVyIENvbW1hbmRvIjo3LCJKYXdhIjoxNSwiSmF3YSBFbmdpbmVlciI6MywiSmF3YSBTY2F2ZW5nZXIiOjcsIkplZGkgQ29uc3VsYXIiOjE2LCJKZWRpIEtuaWdodCBBbmFraW4iOjEwLCJKZWRpIEtuaWdodCBHdWFyZGlhbiI6OCwiSm9sZWUgQmluZG8iOjAsIkp5biBFcnNvIjozLCJLLTJTTyI6MTUsIkthbmFuIEphcnJ1cyI6MTAsIktpdCBGaXN0byI6MTEsIkt5bG8gUmVuIjoxMSwiS3lsbyBSZW4gKFVubWFza2VkKSI6MTEsIkwzLTM3Ijo5LCJMYW5kbyBDYWxyaXNzaWFuIjoxMywiTG9ib3QiOjIsIkxvZ3JheSI6MywiTHVrZSBTa3l3YWxrZXIgKEZhcm1ib3kpIjoxNiwiTHVtaW5hcmEgVW5kdWxpIjoxOCwiTWFjZSBXaW5kdSI6MjQsIk1hZ21hdHJvb3BlciI6MTUsIk1pc3Npb24gVmFvIjowLCJNb2IgRW5mb3JjZXIiOjcsIk1vdGhlciBUYWx6aW4iOjEsIk5pZ2h0c2lzdGVyIEFjb2x5dGUiOjEzLCJOaWdodHNpc3RlciBJbml0aWF0ZSI6MTUsIk5pZ2h0c2lzdGVyIFNwaXJpdCI6NywiTmlnaHRzaXN0ZXIgWm9tYmllIjoxLCJOdXRlIEd1bnJheSI6MTAsIk9iaS1XYW4gS2Vub2JpIChPbGQgQmVuKSI6MTMsIk9sZCBEYWthIjo4LCJQYW8iOjcsIlBhcGxvbyI6MTEsIlBsbyBLb29uIjoxMywiUG9lIERhbWVyb24iOjExLCJQb2dnbGUgdGhlIExlc3NlciI6MTcsIlByaW5jZXNzIExlaWEiOjEwLCJRaSdyYSI6MTEsIlF1aS1Hb24gSmlubiI6NywiUjItRDIiOjAsIlJhbmdlIFRyb29wZXIiOjEsIlJlYmVsIE9mZmljZXIgTGVpYSBPcmdhbmEiOjAsIlJlc2lzdGFuY2UgUGlsb3QiOjE3LCJSZXNpc3RhbmNlIFRyb29wZXIiOjUsIlJleSAoSmVkaSBUcmFpbmluZykiOjAsIlJleSAoU2NhdmVuZ2VyKSI6NSwiUm9zZSBUaWNvIjoxLCJSb3lhbCBHdWFyZCI6OSwiU2FiaW5lIFdyZW4iOjQsIlNhdmFnZSBPcHJlc3MiOjEwLCJTY2FyaWYgUmViZWwgUGF0aGZpbmRlciI6MTUsIlNob3JldHJvb3BlciI6MSwiU2l0aCBBc3Nhc3NpbiI6NywiU2l0aCBNYXJhdWRlciI6OSwiU2l0aCBUcm9vcGVyIjo3LCJTbm93dHJvb3BlciI6MTEsIlN0b3JtdHJvb3BlciI6MTAsIlN0b3JtdHJvb3BlciBIYW4iOjEwLCJTdW4gRmFjIjo1LCJUMy1NNCI6MCwiVGFsaWEiOjE1LCJUZWVibyI6MTcsIlRJRSBGaWdodGVyIFBpbG90IjoxMSwiVHVza2VuIFJhaWRlciI6MjMsIlR1c2tlbiBTaGFtYW4iOjUsIlVnbmF1Z2h0IjoxMCwiVVJvUlJ1UidSJ1IiOjIsIlZhbmRvciBDaGV3YmFjY2EiOjEsIlZldGVyYW4gU211Z2dsZXIgQ2hld2JhY2NhIjo3LCJWZXRlcmFuIFNtdWdnbGVyIEhhbiBTb2xvIjo3LCJWaXNhcyBNYXJyIjoxLCJXYW1wYSI6MCwiV2VkZ2UgQW50aWxsZXMiOjExLCJXaWNrZXQiOjEsIllvdW5nIEhhbiBTb2xvIjozLCJZb3VuZyBMYW5kbyBDYWxyaXNzaWFuIjoxLCJaYWFsYmFyIjowLCJaYW0gV2VzZWxsIjo1fQ"),true);
				}

				$toons = array("AAYLASECURA"=>"Aayla Secura","ADMIRALACKBAR"=>"Admiral Ackbar","AHSOKATANO"=>"Ahsoka Tano","FULCRUMAHSOKA"=>"Ahsoka Tano (Fulcrum)","AMILYNHOLDO"=>"Amilyn Holdo","ASAJVENTRESS"=>"Asajj Ventress","B2SUPERBATTLEDROID"=>"B2 Super Battle Droid","BARRISSOFFEE"=>"Barriss Offee","BASTILASHAN"=>"Bastila Shan","BAZEMALBUS"=>"Baze Malbus","BB8"=>"BB-8","BIGGSDARKLIGHTER"=>"Biggs Darklighter","BISTAN"=>"Bistan","BOBAFETT"=>"Boba Fett","BODHIROOK"=>"Bodhi Rook","BOSSK"=>"Bossk","CADBANE"=>"Cad Bane","HOTHHAN"=>"Captain Han Solo","PHASMA"=>"Captain Phasma","CASSIANANDOR"=>"Cassian Andor","CC2224"=>"CC-2224 \"Cody\"","CHIEFCHIRPA"=>"Chief Chirpa","CHIEFNEBIT"=>"Chief Nebit","CHIRRUTIMWE"=>"Chirrut Îmwe","CHOPPERS3"=>"Chopper","CLONESERGEANTPHASEI"=>"Clone Sergeant - Phase I","CLONEWARSCHEWBACCA"=>"Clone Wars Chewbacca","COLONELSTARCK"=>"Colonel Starck","COMMANDERLUKESKYWALKER"=>"Commander Luke Skywalker","CORUSCANTUNDERWORLDPOLICE"=>"Coruscant Underworld Police","COUNTDOOKU"=>"Count Dooku","CT210408"=>"CT-21-0408 \"Echo\"","CT5555"=>"CT-5555 \"Fives\"","CT7567"=>"CT-7567 \"Rex\"","MAUL"=>"Darth Maul","DARTHNIHILUS"=>"Darth Nihilus","DARTHSIDIOUS"=>"Darth Sidious","DARTHSION"=>"Darth Sion","DARTHTRAYA"=>"Darth Traya","VADER"=>"Darth Vader","DATHCHA"=>"Dathcha","DEATHTROOPER"=>"Death Trooper","DENGAR"=>"Dengar","DIRECTORKRENNIC"=>"Director Krennic","EETHKOTH"=>"Eeth Koth","EMBO"=>"Embo","EMPERORPALPATINE"=>"Emperor Palpatine","ENFYSNEST"=>"Enfys Nest","EWOKELDER"=>"Ewok Elder","EWOKSCOUT"=>"Ewok Scout","EZRABRIDGERS3"=>"Ezra Bridger","FINN"=>"Finn","FIRSTORDEREXECUTIONER"=>"First Order Executioner","FIRSTORDEROFFICERMALE"=>"First Order Officer","FIRSTORDERSPECIALFORCESPILOT"=>"First Order SF TIE Pilot","FIRSTORDERTROOPER"=>"First Order Stormtrooper","FIRSTORDERTIEPILOT"=>"First Order TIE Pilot","GAMORREANGUARD"=>"Gamorrean Guard","GARSAXON"=>"Gar Saxon","ZEBS3"=>"Garazeb \"Zeb\" Orrelios","GRIEVOUS"=>"General Grievous","GENERALKENOBI"=>"General Kenobi","VEERS"=>"General Veers","GEONOSIANSOLDIER"=>"Geonosian Soldier","GEONOSIANSPY"=>"Geonosian Spy","GRANDADMIRALTHRAWN"=>"Grand Admiral Thrawn","GRANDMASTERYODA"=>"Grand Master Yoda","GRANDMOFFTARKIN"=>"Grand Moff Tarkin","GREEDO"=>"Greedo","HANSOLO"=>"Han Solo","HERASYNDULLAS3"=>"Hera Syndulla","HERMITYODA"=>"Hermit Yoda","HK47"=>"HK-47","HOTHREBELSCOUT"=>"Hoth Rebel Scout","HOTHREBELSOLDIER"=>"Hoth Rebel Soldier","MAGNAGUARD"=>"IG-100 MagnaGuard","IG86SENTINELDROID"=>"IG-86 Sentinel Droid","IG88"=>"IG-88","IMAGUNDI"=>"Ima-Gun Di","IMPERIALPROBEDROID"=>"Imperial Probe Droid","IMPERIALSUPERCOMMANDO"=>"Imperial Super Commando","JAWA"=>"Jawa","JAWAENGINEER"=>"Jawa Engineer","JAWASCAVENGER"=>"Jawa Scavenger","JEDIKNIGHTCONSULAR"=>"Jedi Consular","ANAKINKNIGHT"=>"Jedi Knight Anakin","JEDIKNIGHTGUARDIAN"=>"Jedi Knight Guardian","JOLEEBINDO"=>"Jolee Bindo","JYNERSO"=>"Jyn Erso","K2SO"=>"K-2SO","KANANJARRUSS3"=>"Kanan Jarrus","KITFISTO"=>"Kit Fisto","KYLOREN"=>"Kylo Ren","KYLORENUNMASKED"=>"Kylo Ren (Unmasked)","L3_37"=>"L3-37","ADMINISTRATORLANDO"=>"Lando Calrissian","LOBOT"=>"Lobot","LOGRAY"=>"Logray","LUKESKYWALKER"=>"Luke Skywalker (Farmboy)","LUMINARAUNDULI"=>"Luminara Unduli","MACEWINDU"=>"Mace Windu","MAGMATROOPER"=>"Magmatrooper","MISSIONVAO"=>"Mission Vao","HUMANTHUG"=>"Mob Enforcer","MOTHERTALZIN"=>"Mother Talzin","NIGHTSISTERACOLYTE"=>"Nightsister Acolyte","NIGHTSISTERINITIATE"=>"Nightsister Initiate","NIGHTSISTERSPIRIT"=>"Nightsister Spirit","NIGHTSISTERZOMBIE"=>"Nightsister Zombie","NUTEGUNRAY"=>"Nute Gunray","OLDBENKENOBI"=>"Obi-Wan Kenobi (Old Ben)","DAKA"=>"Old Daka","PAO"=>"Pao","PAPLOO"=>"Paploo","PLOKOON"=>"Plo Koon","POE"=>"Poe Dameron","POGGLETHELESSER"=>"Poggle the Lesser","PRINCESSLEIA"=>"Princess Leia","QIRA"=>"Qi'ra","QUIGONJINN"=>"Qui-Gon Jinn","R2D2_LEGENDARY"=>"R2-D2","RANGETROOPER"=>"Range Trooper","HOTHLEIA"=>"Rebel Officer Leia Organa","RESISTANCEPILOT"=>"Resistance Pilot","RESISTANCETROOPER"=>"Resistance Trooper","REYJEDITRAINING"=>"Rey (Jedi Training)","REY"=>"Rey (Scavenger)","ROSETICO"=>"Rose Tico","ROYALGUARD"=>"Royal Guard","SABINEWRENS3"=>"Sabine Wren","SAVAGEOPRESS"=>"Savage Opress","SCARIFREBEL"=>"Scarif Rebel Pathfinder","SHORETROOPER"=>"Shoretrooper","SITHASSASSIN"=>"Sith Assassin","SITHMARAUDER"=>"Sith Marauder","SITHTROOPER"=>"Sith Trooper","SNOWTROOPER"=>"Snowtrooper","STORMTROOPER"=>"Stormtrooper","STORMTROOPERHAN"=>"Stormtrooper Han","SUNFAC"=>"Sun Fac","T3_M4"=>"T3-M4","TALIA"=>"Talia","TEEBO"=>"Teebo","TIEFIGHTERPILOT"=>"TIE Fighter Pilot","TUSKENRAIDER"=>"Tusken Raider","TUSKENSHAMAN"=>"Tusken Shaman","UGNAUGHT"=>"Ugnaught","URORRURRR"=>"URoRRuR'R'R","YOUNGCHEWBACCA"=>"Vandor Chewbacca","SMUGGLERCHEWBACCA"=>"Veteran Smuggler Chewbacca","SMUGGLERHAN"=>"Veteran Smuggler Han Solo","VISASMARR"=>"Visas Marr","WAMPA"=>"Wampa","WEDGEANTILLES"=>"Wedge Antilles","WICKET"=>"Wicket","YOUNGHAN"=>"Young Han Solo","YOUNGLANDO"=>"Young Lando Calrissian","ZAALBAR"=>"Zaalbar","ZAMWESELL"=>"Zam Wesell");

				for($i=1;$i<=$numberOftoons;$i++) {
					if(isset($_GET['t'.$i]) && isset($toons[$_GET['t'.$i]])) {
						$toon = $toons[$_GET['t'.$i]];
						$per = $perday[$toon];
						$rs = $db->query("SELECT * from toons WHERE (toon='".$db->str($toon)."') AND user='".$db->str($ally)."' order by toon desc");
						if($row = $db->getNext($rs,1)) {
							printOneToonFarm($row,7,11,$per);
						} else {
							printEmptyToon($toon);
							$red++;
						}
					}
				}
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