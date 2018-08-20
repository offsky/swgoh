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

$self = explode("/",$_SERVER['PHP_SELF']);
$self = array_pop($self);

$ally = empty($_REQUEST['ally']) ? "" : trim($_REQUEST['ally']);
$ally = intval(preg_replace("/[^0-9]/","",$ally));

if(!empty($ally)) {
	$data = fetchGuildFromSWGOHHelp($ally);
}

?><!DOCTYPE html>
<html>
	<head>
		<title>SWGOH Tools - Territory War Squad Builder</title>
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

		var toonData = JSON.parse('<?=json_encode($data)?>');

		var id2Name = {"AAYLASECURA":"Aayla Secura","ADMIRALACKBAR":"Admiral Ackbar","AHSOKATANO":"Ahsoka Tano","FULCRUMAHSOKA":"Ahsoka Tano (Fulcrum)","AMILYNHOLDO":"Amilyn Holdo","ASAJVENTRESS":"Asajj Ventress","B2SUPERBATTLEDROID":"B2 Super Battle Droid","BARRISSOFFEE":"Barriss Offee","BASTILASHAN":"Bastila Shan","BAZEMALBUS":"Baze Malbus","BB8":"BB-8","BIGGSDARKLIGHTER":"Biggs Darklighter","BISTAN":"Bistan","BOBAFETT":"Boba Fett","BODHIROOK":"Bodhi Rook","BOSSK":"Bossk","CADBANE":"Cad Bane","HOTHHAN":"Captain Han Solo","PHASMA":"Captain Phasma","CASSIANANDOR":"Cassian Andor","CC2224":"CC-2224 \"Cody\"","CHIEFCHIRPA":"Chief Chirpa","CHIEFNEBIT":"Chief Nebit","CHIRRUTIMWE":"Chirrut Îmwe","CHOPPERS3":"Chopper","CLONESERGEANTPHASEI":"Clone Sergeant - Phase I","CLONEWARSCHEWBACCA":"Clone Wars Chewbacca","COLONELSTARCK":"Colonel Starck","COMMANDERLUKESKYWALKER":"Commander Luke Skywalker","CORUSCANTUNDERWORLDPOLICE":"Coruscant Underworld Police","COUNTDOOKU":"Count Dooku","CT210408":"CT-21-0408 \"Echo\"","CT5555":"CT-5555 \"Fives\"","CT7567":"CT-7567 \"Rex\"","MAUL":"Darth Maul","DARTHNIHILUS":"Darth Nihilus","DARTHSIDIOUS":"Darth Sidious","DARTHSION":"Darth Sion","DARTHTRAYA":"Darth Traya","VADER":"Darth Vader","DATHCHA":"Dathcha","DEATHTROOPER":"Death Trooper","DENGAR":"Dengar","DIRECTORKRENNIC":"Director Krennic","EETHKOTH":"Eeth Koth","EMBO":"Embo","EMPERORPALPATINE":"Emperor Palpatine","ENFYSNEST":"Enfys Nest","EWOKELDER":"Ewok Elder","EWOKSCOUT":"Ewok Scout","EZRABRIDGERS3":"Ezra Bridger","FINN":"Finn","FIRSTORDEREXECUTIONER":"First Order Executioner","FIRSTORDEROFFICERMALE":"First Order Officer","FIRSTORDERSPECIALFORCESPILOT":"First Order SF TIE Pilot","FIRSTORDERTROOPER":"First Order Stormtrooper","FIRSTORDERTIEPILOT":"First Order TIE Pilot","GAMORREANGUARD":"Gamorrean Guard","GARSAXON":"Gar Saxon","ZEBS3":"Garazeb \"Zeb\" Orrelios","GRIEVOUS":"General Grievous","GENERALKENOBI":"General Kenobi","VEERS":"General Veers","GEONOSIANSOLDIER":"Geonosian Soldier","GEONOSIANSPY":"Geonosian Spy","GRANDADMIRALTHRAWN":"Grand Admiral Thrawn","GRANDMASTERYODA":"Grand Master Yoda","GRANDMOFFTARKIN":"Grand Moff Tarkin","GREEDO":"Greedo","HANSOLO":"Han Solo","HERASYNDULLAS3":"Hera Syndulla","HERMITYODA":"Hermit Yoda","HK47":"HK-47","HOTHREBELSCOUT":"Hoth Rebel Scout","HOTHREBELSOLDIER":"Hoth Rebel Soldier","MAGNAGUARD":"IG-100 MagnaGuard","IG86SENTINELDROID":"IG-86 Sentinel Droid","IG88":"IG-88","IMAGUNDI":"Ima-Gun Di","IMPERIALPROBEDROID":"Imperial Probe Droid","IMPERIALSUPERCOMMANDO":"Imperial Super Commando","JAWA":"Jawa","JAWAENGINEER":"Jawa Engineer","JAWASCAVENGER":"Jawa Scavenger","JEDIKNIGHTCONSULAR":"Jedi Consular","ANAKINKNIGHT":"Jedi Knight Anakin","JEDIKNIGHTGUARDIAN":"Jedi Knight Guardian","JOLEEBINDO":"Jolee Bindo","JYNERSO":"Jyn Erso","K2SO":"K-2SO","KANANJARRUSS3":"Kanan Jarrus","KITFISTO":"Kit Fisto","KYLOREN":"Kylo Ren","KYLORENUNMASKED":"Kylo Ren (Unmasked)","L3_37":"L3-37","ADMINISTRATORLANDO":"Lando Calrissian","LOBOT":"Lobot","LOGRAY":"Logray","LUKESKYWALKER":"Luke Skywalker (Farmboy)","LUMINARAUNDULI":"Luminara Unduli","MACEWINDU":"Mace Windu","MAGMATROOPER":"Magmatrooper","MISSIONVAO":"Mission Vao","HUMANTHUG":"Mob Enforcer","MOTHERTALZIN":"Mother Talzin","NIGHTSISTERACOLYTE":"Nightsister Acolyte","NIGHTSISTERINITIATE":"Nightsister Initiate","NIGHTSISTERSPIRIT":"Nightsister Spirit","NIGHTSISTERZOMBIE":"Nightsister Zombie","NUTEGUNRAY":"Nute Gunray","OLDBENKENOBI":"Obi-Wan Kenobi (Old Ben)","DAKA":"Old Daka","PAO":"Pao","PAPLOO":"Paploo","PLOKOON":"Plo Koon","POE":"Poe Dameron","POGGLETHELESSER":"Poggle the Lesser","PRINCESSLEIA":"Princess Leia","QIRA":"Qi'ra","QUIGONJINN":"Qui-Gon Jinn","R2D2_LEGENDARY":"R2-D2","RANGETROOPER":"Range Trooper","HOTHLEIA":"Rebel Officer Leia Organa","RESISTANCEPILOT":"Resistance Pilot","RESISTANCETROOPER":"Resistance Trooper","REYJEDITRAINING":"Rey (Jedi Training)","REY":"Rey (Scavenger)","ROSETICO":"Rose Tico","ROYALGUARD":"Royal Guard","SABINEWRENS3":"Sabine Wren","SAVAGEOPRESS":"Savage Opress","SCARIFREBEL":"Scarif Rebel Pathfinder","SHORETROOPER":"Shoretrooper","SITHASSASSIN":"Sith Assassin","SITHMARAUDER":"Sith Marauder","SITHTROOPER":"Sith Trooper","SNOWTROOPER":"Snowtrooper","STORMTROOPER":"Stormtrooper","STORMTROOPERHAN":"Stormtrooper Han","SUNFAC":"Sun Fac","T3_M4":"T3-M4","TALIA":"Talia","TEEBO":"Teebo","TIEFIGHTERPILOT":"TIE Fighter Pilot","TUSKENRAIDER":"Tusken Raider","TUSKENSHAMAN":"Tusken Shaman","UGNAUGHT":"Ugnaught","URORRURRR":"URoRRuR'R'R","YOUNGCHEWBACCA":"Vandor Chewbacca","SMUGGLERCHEWBACCA":"Veteran Smuggler Chewbacca","SMUGGLERHAN":"Veteran Smuggler Han Solo","VISASMARR":"Visas Marr","WAMPA":"Wampa","WEDGEANTILLES":"Wedge Antilles","WICKET":"Wicket","YOUNGHAN":"Young Han Solo","YOUNGLANDO":"Young Lando Calrissian","ZAALBAR":"Zaalbar","ZAMWESELL":"Zam Wesell"};
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
			runReport();
		}

		init();

		function fillSelects() {
			var t1 = $('#toon1');
			var t2 = $('#toon2');
			var t3 = $('#toon3');
			var t4 = $('#toon4');
			var t5 = $('#toon5');

			var names = [];

			for(var toon in toonData) {
				if(toonData[toon][0].type==1) {
					var niceName = id2Name[toon];
					if(niceName==undefined || niceName=='') niceName = toon;
					names.push(niceName+"**"+toon);
				}
			}
			names.sort();
			$.each(names,function(index,value) {
				var parts = value.split("**");
				var option = '<option value="'+parts[1]+'">'+parts[0]+'</option>';

				t1.append(option);
				t2.append(option);
				t3.append(option);
				t4.append(option);
				t5.append(option);
			});

			$('#toon1 option[value="HERASYNDULLAS3"]').prop("selected", "selected");
			$('#toon2 option[value="CHOPPERS3"]').prop("selected", "selected");
			$('#toon3 option[value="KANANJARRUSS3"]').prop("selected", "selected");
			$('#toon4 option[value="ZEBS3"]').prop("selected", "selected");
			$('#toon5 option[value="SABINEWRENS3"]').prop("selected", "selected");
		}

		function shortcut(e) {
			var ss = $(e.target).data('ss');

			$('#toon1 option').prop("selected", false);
			$('#toon2 option').prop("selected", false);
			$('#toon3 option').prop("selected", false);
			$('#toon4 option').prop("selected", false);
			$('#toon5 option').prop("selected", false);
			$("option:selected").removeProp("selected");

			if(ss=="phoenix") {
				$('#toon1 option[value="HERASYNDULLAS3"]').prop("selected", "selected");
				$('#toon2 option[value="CHOPPERS3"]').prop("selected", "selected");
				$('#toon3 option[value="KANANJARRUSS3"]').prop("selected", "selected");
				$('#toon4 option[value="ZEBS3"]').prop("selected", "selected");
				$('#toon5 option[value="SABINEWRENS3"]').prop("selected", "selected");
			} else if(ss=="nightsister") {
				$('#toon1 option[value="MOTHERTALZIN"]').prop("selected", "selected");
				$('#toon2 option[value="ASAJVENTRESS"]').prop("selected", "selected");
				$('#toon3 option[value="DAKA"]').prop("selected", "selected");
				$('#toon4 option[value="NIGHTSISTERACOLYTE"]').prop("selected", "selected");
				$('#toon5 option[value="NIGHTSISTERZOMBIE"]').prop("selected", "selected");
			} else if(ss=="cls") {
				$('#toon1 option[value="COMMANDERLUKESKYWALKER"]').prop("selected", "selected");
				$('#toon2 option[value="R2D2_LEGENDARY"]').prop("selected", "selected");
				$('#toon3 option[value="HANSOLO"]').prop("selected", "selected");
				$('#toon4 option[value="BAZEMALBUS"]').prop("selected", "selected");
				$('#toon5 option[value="CHIRRUTIMWE"]').prop("selected", "selected");
			} else if(ss=="maul") {
				$('#toon1 option[value="MAUL"]').prop("selected", "selected");
				$('#toon2 option[value="SAVAGEOPRESS"]').prop("selected", "selected");
				$('#toon3 option[value="SITHTROOPER"]').prop("selected", "selected");
				$('#toon4 option[value="SITHASSASSIN"]').prop("selected", "selected");
				$('#toon5 option[value="EMPERORPALPATINE"]').prop("selected", "selected");
			} else if(ss=="rey") {
				$('#toon1 option[value="REYJEDITRAINING"]').prop("selected", "selected");
				$('#toon2 option[value="BB8"]').prop("selected", "selected");
				$('#toon3 option[value="R2D2_LEGENDARY"]').prop("selected", "selected");
				$('#toon4 option[value="GENERALKENOBI"]').prop("selected", "selected");
				$('#toon5 option[value="OLDBENKENOBI"]').prop("selected", "selected");
			} else if(ss=="gk") {
				$('#toon1 option[value="GENERALKENOBI"]').prop("selected", "selected");
				$('#toon2 option[value="BARRISSOFFEE"]').prop("selected", "selected");
				$('#toon3 option[value=""]').prop("selected", "selected");
				$('#toon4 option[value=""]').prop("selected", "selected");
				$('#toon5 option[value=""]').prop("selected", "selected");
			} else if(ss=="finn") {
				$('#toon1 option[value="FINN"]').prop("selected", "selected");
				$('#toon2 option[value="RESISTANCETROOPER"]').prop("selected", "selected");
				$('#toon3 option[value="RESISTANCEPILOT"]').prop("selected", "selected");
				$('#toon4 option[value="POE"]').prop("selected", "selected");
				$('#toon5 option[value="BB8"]').prop("selected", "selected");
			}
			runReport();
		}


		$('#toon1').on("change",runReport);
		$('#toon2').on("change",runReport);
		$('#toon3').on("change",runReport);
		$('#toon4').on("change",runReport);
		$('#toon5').on("change",runReport);

		$('.js_ss').on("click",shortcut);

		function runReport() {
			var t1 = $('#toon1').val();
			var t2 = $('#toon2').val();
			var t3 = $('#toon3').val();
			var t4 = $('#toon4').val();
			var t5 = $('#toon5').val();

			var squad = [];

			for(var toon in toonData) {
				if(toonData[toon][0].type==1) {
					if(toon==t1) squad[0] = toonData[toon];
					if(toon==t2) squad[1] = toonData[toon];
					if(toon==t3) squad[2] = toonData[toon];
					if(toon==t4) squad[3] = toonData[toon];
					if(toon==t5) squad[4] = toonData[toon];
				}
			}

			rankSquads(squad);
		}

		function rankSquads(squad) {
			var guild = [];

			squad.forEach(function(toon,index) {
				toon.forEach(function(player) {
					if(guild[player.player]==undefined) guild[player.player] = {url:player.url,power:0,toon:[]};
					
					if(player.gp>=6000) {
						guild[player.player].toon[index+1] = [player.starLevel,player.gearLevel,player.level,player.gp];
						guild[player.player].power += player.gp;
					}
				});
			});

			printTable(guild);
		}

		function printTable(guild) {

			var rows = [];
			for(var player in guild) {
				var squad = guild[player];
				//if(squad.toon[1]!==undefined && squad.toon[2]!==undefined && squad.toon[3]!==undefined && squad.toon[4]!==undefined && squad.toon[5]!==undefined) {
					var row = "<td>"+player+"</td><td>"+printToon(squad.toon[1])+"</td><td>"+printToon(squad.toon[2])+"</td><td>"+printToon(squad.toon[3])+"</td><td>"+printToon(squad.toon[4])+"</td><td>"+printToon(squad.toon[5])+"</td><td>"+numberWithCommas(squad.power)+"</td></tr>";
					rows.push({power:squad.power,html:row});
				//}
			}

			rows.sort(function(a,b) {
				return b.power-a.power;
			});

			var table = $('#table');
			table.html("");
			var rownum = 0;
			rows.forEach(function(row) {
				rownum++;
				table.append("<tr><td>"+rownum+"</td>"+row.html);
			});

		}

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

		<h1><a href="http://www.swgoh.life/index.html">More Tools</a> &gt; Territory War Squad Builder</h1>

		<? if(empty($ally)) { ?>

			<p>This tool will inspect your guild's roster and allow you to build different squads for Territory War.To find your ally code, open the game to the main screen and tap on your name in the upper left corner. Then look below your name for a 9 digit number.</p>

			<form action="<?=$self?>" method="get">
			<b>What is your SWGOH Ally Code:</b><br />
			<input type="text" id="ally" name="ally" placeholder="123-456-789" /> <input type="submit" value="ok" />
			</form>

		<? } else { ?>
	
			<a name="top"></a>
			<div id="step3">
				<table class="styled">
					<thead>
					<tr>
						<td colspan="2">Player</td>
						<td><select id="toon1"><option value="">ANY TOON</option></select></td>
						<td><select id="toon2"><option value="">ANY TOON</option></select></td>
						<td><select id="toon3"><option value="">ANY TOON</option></select></td>
						<td><select id="toon4"><option value="">ANY TOON</option></select></td>
						<td><select id="toon5"><option value="">ANY TOON</option></select></td>
						<td>Squad Power</td>
					</tr>
					</thead>
					<tbody id="table">

					</tbody>
				</table>
				<p>Toons with a power of less than 6000 cannot be used in Territory Wars, so they are omitted.</p>
				Shortcuts: <a href="#top" class="js_ss" data-ss="phoenix">Phoenix</a>,&nbsp;
							  <a href="#top" class="js_ss" data-ss="nightsister">NightSisters</a>,&nbsp;
							  <a href="#top" class="js_ss" data-ss="cls">Commander Luke + R2 + Chaze</a>,&nbsp;
							  <a href="#top" class="js_ss" data-ss="maul">Maul + Sith</a>,&nbsp;
							  <a href="#top" class="js_ss" data-ss="rey">Jedi Rey</a>,&nbsp;
							  <a href="#top" class="js_ss" data-ss="finn">Finn Resistance</a>,&nbsp;
							  <a href="#top" class="js_ss" data-ss="gk">GK + Barris</a>
			</div>

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