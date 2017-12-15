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
		var parsed = null;
		var storage = null;
		if(window.localStorage) storage = window.localStorage;
		else if(window.globalStorage) storage = window.globalStorage[location.hostname];


		var id2Name = {"AAYLASECURA":"Aayla Secura","ADMIRALACKBAR":"Admiral Ackbar","AHSOKATANO":"Ahsoka Tano","FULCRUMAHSOKA":"Ahsoka Tano (Fulcrum)","ASAJVENTRESS":"Asajj Ventress","B2SUPERBATTLEDROID":"B2 Super Battle Droid","BARRISSOFFEE":"Barriss Offee","BAZEMALBUS":"Baze Malbus","BB8":"BB-8","BIGGSDARKLIGHTER":"Biggs Darklighter","BISTAN":"Bistan","BOBAFETT":"Boba Fett","BODHIROOK":"Bodhi Rook","CADBANE":"Cad Bane","HOTHHAN":"Captain Han Solo","PHASMA":"Captain Phasma","CASSIANANDOR":"Cassian Andor","CC2224":"CC-2224 \"Cody\"","CHIEFCHIRPA":"Chief Chirpa","CHIEFNEBIT":"Chief Nebit","CHIRRUTIMWE":"Chirrut Îmwe","CHOPPERS3":"Chopper","CLONESERGEANTPHASEI":"Clone Sergeant - Phase I","CLONEWARSCHEWBACCA":"Clone Wars Chewbacca","COLONELSTARCK":"Colonel Starck","COMMANDERLUKESKYWALKER":"Commander Luke Skywalker","CORUSCANTUNDERWORLDPOLICE":"Coruscant Underworld Police","COUNTDOOKU":"Count Dooku","CT210408":"CT-21-0408 \"Echo\"","CT5555":"CT-5555 \"Fives\"","CT7567":"CT-7567 \"Rex\"","MAUL":"Darth Maul","DARTHNIHILUS":"Darth Nihilus","DARTHSIDIOUS":"Darth Sidious","VADER":"Darth Vader","DATHCHA":"Dathcha","DEATHTROOPER":"Death Trooper","DENGAR":"Dengar","DIRECTORKRENNIC":"Director Krennic","EETHKOTH":"Eeth Koth","EMPERORPALPATINE":"Emperor Palpatine","EWOKELDER":"Ewok Elder","EWOKSCOUT":"Ewok Scout","EZRABRIDGERS3":"Ezra Bridger","FINN":"Finn","FIRSTORDEROFFICERMALE":"First Order Officer","FIRSTORDERSPECIALFORCESPILOT":"First Order SF TIE Pilot","FIRSTORDERTROOPER":"First Order Stormtrooper","FIRSTORDERTIEPILOT":"First Order TIE Pilot","GAMORREANGUARD":"Gamorrean Guard","GARSAXON":"Gar Saxon","ZEBS3":"Garazeb \"Zeb\" Orrelios","GRIEVOUS":"General Grievous","GENERALKENOBI":"General Kenobi","VEERS":"General Veers","GEONOSIANSOLDIER":"Geonosian Soldier","GEONOSIANSPY":"Geonosian Spy","GRANDADMIRALTHRAWN":"Grand Admiral Thrawn","GRANDMASTERYODA":"Grand Master Yoda","GRANDMOFFTARKIN":"Grand Moff Tarkin","GREEDO":"Greedo","HANSOLO":"Han Solo","HERASYNDULLAS3":"Hera Syndulla","HERMITYODA":"Hermit Yoda","HK47":"HK-47","HOTHREBELSCOUT":"Hoth Rebel Scout","HOTHREBELSOLDIER":"Hoth Rebel Soldier","MAGNAGUARD":"IG-100 MagnaGuard","IG86SENTINELDROID":"IG-86 Sentinel Droid","IG88":"IG-88","IMAGUNDI":"Ima-Gun Di","IMPERIALPROBEDROID":"Imperial Probe Droid","IMPERIALSUPERCOMMANDO":"Imperial Super Commando","JAWA":"Jawa","JAWAENGINEER":"Jawa Engineer","JAWASCAVENGER":"Jawa Scavenger","JEDIKNIGHTCONSULAR":"Jedi Consular","ANAKINKNIGHT":"Jedi Knight Anakin","JEDIKNIGHTGUARDIAN":"Jedi Knight Guardian","JYNERSO":"Jyn Erso","K2SO":"K-2SO","KANANJARRUSS3":"Kanan Jarrus","KITFISTO":"Kit Fisto","KYLOREN":"Kylo Ren","KYLORENUNMASKED":"Kylo Ren (Unmasked)","ADMINISTRATORLANDO":"Lando Calrissian","LOBOT":"Lobot","LOGRAY":"Logray","LUKESKYWALKER":"Luke Skywalker (Farmboy)","LUMINARAUNDULI":"Luminara Unduli","MACEWINDU":"Mace Windu","MAGMATROOPER":"Magmatrooper","HUMANTHUG":"Mob Enforcer","MOTHERTALZIN":"Mother Talzin","NIGHTSISTERACOLYTE":"Nightsister Acolyte","NIGHTSISTERINITIATE":"Nightsister Initiate","NIGHTSISTERSPIRIT":"Nightsister Spirit","NIGHTSISTERZOMBIE":"Nightsister Zombie","NUTEGUNRAY":"Nute Gunray","OLDBENKENOBI":"Obi-Wan Kenobi (Old Ben)","DAKA":"Old Daka","PAO":"Pao","PAPLOO":"Paploo","PLOKOON":"Plo Koon","POE":"Poe Dameron","POGGLETHELESSER":"Poggle the Lesser","PRINCESSLEIA":"Princess Leia","QUIGONJINN":"Qui-Gon Jinn","R2D2_LEGENDARY":"R2-D2","HOTHLEIA":"Rebel Officer Leia Organa","RESISTANCEPILOT":"Resistance Pilot","RESISTANCETROOPER":"Resistance Trooper","REYJEDITRAINING":"Rey (Jedi Training)","REY":"Rey (Scavenger)","ROYALGUARD":"Royal Guard","SABINEWRENS3":"Sabine Wren","SAVAGEOPRESS":"Savage Opress","SCARIFREBEL":"Scarif Rebel Pathfinder","SHORETROOPER":"Shoretrooper","SITHASSASSIN":"Sith Assassin","SITHTROOPER":"Sith Trooper","SNOWTROOPER":"Snowtrooper","STORMTROOPER":"Stormtrooper","STORMTROOPERHAN":"Stormtrooper Han","SUNFAC":"Sun Fac","TALIA":"Talia","TEEBO":"Teebo","TIEFIGHTERPILOT":"TIE Fighter Pilot","TUSKENRAIDER":"Tusken Raider","TUSKENSHAMAN":"Tusken Shaman","UGNAUGHT":"Ugnaught","URORRURRR":"URoRRuR'R'R","SMUGGLERCHEWBACCA":"Veteran Smuggler Chewbacca","SMUGGLERHAN":"Veteran Smuggler Han Solo","WAMPA":"Wampa","WEDGEANTILLES":"Wedge Antilles","WICKET":"Wicket","ZAMWESELL":"Zam Wesell"};
		// var names = []; //Take from https://swgoh.gg/api/characters/
		// var fixed = {};
		// names.forEach(function(toon) {
		// 	fixed[toon.base_id] = toon.name;
		// });
		// console.log(JSON.stringify(fixed));

		//get data
		var gg = fetch("gg");
		if(gg) {
			$('#gg').val(gg);
			showStep2(gg);
		}
		function updateGG(e) {
			var gg = $('#gg').val();
			store("gg",gg);
			showStep2(gg);
		}
		function showStep2(gg) {
			var parts = gg.split("/");
			var num = parseInt(parts[0]);

			$('#step2url').attr("href","https://swgoh.gg/api/guilds/"+num+"/units/")
			$('#step2').show();
		}

		$('#gg').on("change",updateGG);
		$('#ok').on("click",updateGG);

		//use data
		function showStep3() {
			$('#badData').hide();

			var data = $('#manual').val();

			if(data.length<100) {
				badData();
				return;
			}
			try {
				parsed = JSON.parse(data);
			} catch(e) {
				badData();
				return;
			}

			fillSelects();
			$('#step2').hide();
			$('#step3').show();
			runReport();
		}

		function badData() {
			$('#badData').show();
		}

		function fillSelects() {
			var t1 = $('#toon1');
			var t2 = $('#toon2');
			var t3 = $('#toon3');
			var t4 = $('#toon4');
			var t5 = $('#toon5');

			var names = [];

			for(var toon in parsed) {
				if(parsed[toon][0].combat_type==1) {
					var niceName = id2Name[toon];
					if(niceName==undefined || niceName=='') niceName = parsed[toon][0].url.split("/")[5];
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
		}

		$('#go').on("click",showStep3);

		$('#toon1').on("change",runReport);
		$('#toon2').on("change",runReport);
		$('#toon3').on("change",runReport);
		$('#toon4').on("change",runReport);
		$('#toon5').on("change",runReport);

		function runReport() {
			var t1 = $('#toon1').val();
			var t2 = $('#toon2').val();
			var t3 = $('#toon3').val();
			var t4 = $('#toon4').val();
			var t5 = $('#toon5').val();

			var squad = [];

			for(var toon in parsed) {
				if(parsed[toon][0].combat_type==1) {
					if(toon==t1) squad[0] = parsed[toon];
					if(toon==t2) squad[1] = parsed[toon];
					if(toon==t3) squad[2] = parsed[toon];
					if(toon==t4) squad[3] = parsed[toon];
					if(toon==t5) squad[4] = parsed[toon];
				}
			}

			rankSquads(squad);
		}

		function rankSquads(squad) {
			var guild = [];

			var num=0;
			squad.forEach(function(toon) {
				num++;
				toon.forEach(function(player) {
					if(guild[player.player]==undefined) guild[player.player] = {url:player.url,power:0,toon:[]};
					
					if(player.power>=6000) {
						guild[player.player].toon[num] = [player.rarity,player.gear_level,player.level,player.power];
						guild[player.player].power += player.power;
					}
				});
			});

			printTable(guild);
		}

		function printTable(guild) {

			var rows = [];
			for(var player in guild) {
				var squad = guild[player];
				if(squad.toon[1]!==undefined && squad.toon[2]!==undefined && squad.toon[3]!==undefined && squad.toon[4]!==undefined && squad.toon[5]!==undefined) {
					var row = "<td><a href='https://swgoh.gg/u/"+squad.url.split("/")[2]+"/collection' target='_blank'>"+player+"</a></td><td>"+printToon(squad.toon[1])+"</td><td>"+printToon(squad.toon[2])+"</td><td>"+printToon(squad.toon[3])+"</td><td>"+printToon(squad.toon[4])+"</td><td>"+printToon(squad.toon[5])+"</td><td>"+numberWithCommas(squad.power)+"</td></tr>";
					rows.push({power:squad.power,html:row});
				}
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
			return toon[0]+"<i class='fa fa-fw fa-star'></i> "+toon[1]+"<i class='fa fa-fw fa-cog'></i> "+toon[2]+"<i class='fa fa-fw fa-angle-double-up'></i> "+numberWithCommas(toon[3])+"<i class='fa fa-fw fa-bolt last'></i>";
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

		<p>This tool will inspect your guild's roster and allow you to build different squads for Territory War. It needs to fetch the information from <a href="https://www.swgoh.gg">swgoh.gg</a> to do this. If your guild doesn't have everyone registered there, you'll need to fix that first.</p>
	
		<b>What is your SWGOH.GG Guild Number and Name:</b><br />
		https://swgoh.gg/g/<input type="text" id="gg" name="gg" placeholder="1234/name" />/ <input type="button" id="ok" value="ok" /><br /><br />

		<div id="step2" style="display:none">
			<b>SWGOH.GG does not allow automatic fetching of your account information, so we'll have to do it manually <i class="fa fa-smile-o"></i>.</b>
			<br /><br />
			Step 1:<br />
			<a href="" id="step2url" target="_blank">Click here to open your guilds collection page on swgoh.gg</a>
			<br /><br />		
			Step 2:<br />
			Copy and paste everything you see on that page into this box:<br />
			<textarea name="manual" id="manual" cols="50" rows="5"></textarea>
			<br />
			<div id="badData" style="display:none">
				<b>Sorry, but the box above was left blank, or had bad data inside of it. Please try again.</b><br /><br />
			</div>
			<input type="submit" value="Lets Go!" id="go" class="btn" />
		</div>

		<div id="step3" style="display:none">
			<table class="styled">
				<thead>
				<tr>
					<td colspan="2">Player</td>
					<td><select id="toon1"></select></td>
					<td><select id="toon2"></select></td>
					<td><select id="toon3"></select></td>
					<td><select id="toon4"></select></td>
					<td><select id="toon5"></select></td>
					<td>Squad Power</td>
				</tr>
				</thead>
				<tbody id="table">

				</tbody>
			</table>
			<p>Toons with a power of less than 6000 cannot be used in Territory Wars, so they are omitted. Any guild member who is missing one of these toons has been omitted.</p>
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