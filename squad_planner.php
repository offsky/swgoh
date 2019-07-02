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
	$db = new mymysqli("swgoh");
	error_reporting(E_ALL);
}

$self = explode("/",$_SERVER['PHP_SELF']);
$self = array_pop($self);

$ally = empty($_REQUEST['ally']) ? "" : trim($_REQUEST['ally']);
$ally = intval(preg_replace("/[^0-9]/","",$ally));

$data = array();
if(!empty($ally)) {
	list($guild_id,$data) = fetchGuildFromSWGOHHelp($ally);
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

		$('#clickOnce').on("click", function() {
			$('#clickOnce').hide();
			$('#clickOnceWarning').show();
		});

		var toonData = <?=json_encode($data)?>;
		
		<?
			$toonDict = array();
			$rs = $db->query("SELECT id,name FROM swgoh_toons2 order by type asc, name asc");
			while($row = $db->getNext($rs,1)) {
				$toonDict[$row['id']] = $row['name'];
			}
			$toonDictStr = json_encode($toonDict);
		?>
		var id2Name = <?=$toonDictStr?>;

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

			var t1b = $('#toon1b');
			var t2b = $('#toon2b');
			var t3b = $('#toon3b');
			var t4b = $('#toon4b');
			var t5b = $('#toon5b');

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

				t1b.append(option);
				t2b.append(option);
				t3b.append(option);
				t4b.append(option);
				t5b.append(option);
			});

			$('#toon1 option[value="HERASYNDULLAS3"]').prop("selected", "selected");
			$('#toon2 option[value="CHOPPERS3"]').prop("selected", "selected");
			$('#toon3 option[value="KANANJARRUSS3"]').prop("selected", "selected");
			$('#toon4 option[value="ZEBS3"]').prop("selected", "selected");
			$('#toon5 option[value="SABINEWRENS3"]').prop("selected", "selected");

			$('#toon1b option[value="REYJEDITRAINING"]').prop("selected", "selected");
			$('#toon2b option[value="BB8"]').prop("selected", "selected");
			$('#toon3b option[value="R2D2_LEGENDARY"]').prop("selected", "selected");
			$('#toon4b option[value="GENERALKENOBI"]').prop("selected", "selected");
			$('#toon5b option[value="OLDBENKENOBI"]').prop("selected", "selected");
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
		$('#toon1b').on("change",runReport);
		$('#toon2b').on("change",runReport);
		$('#toon3b').on("change",runReport);
		$('#toon4b').on("change",runReport);
		$('#toon5b').on("change",runReport);
		$('#limit').on("change",runReport);
		$('#limitb').on("change",runReport);

		$('.js_ss').on("click",shortcut);

		function runReport() {
			var t1 = $('#toon1').val();
			var t2 = $('#toon2').val();
			var t3 = $('#toon3').val();
			var t4 = $('#toon4').val();
			var t5 = $('#toon5').val();

			var t1b = $('#toon1b').val();
			var t2b = $('#toon2b').val();
			var t3b = $('#toon3b').val();
			var t4b = $('#toon4b').val();
			var t5b = $('#toon5b').val();

			var squad = [];
			var squadB = [];


			for(var toon in toonData) { //for each toon in the raw data
				if(toonData[toon][0].type==1) { //if its a character
					if(toon==t1) squad[0] = toonData[toon]; //if its the one we want, add our guilds roster into the array
					if(toon==t2) squad[1] = toonData[toon];
					if(toon==t3) squad[2] = toonData[toon];
					if(toon==t4) squad[3] = toonData[toon];
					if(toon==t5) squad[4] = toonData[toon];

					if(toon==t1b) squadB[0] = toonData[toon]; //if its the one we want, add our guilds roster into the array
					if(toon==t2b) squadB[1] = toonData[toon];
					if(toon==t3b) squadB[2] = toonData[toon];
					if(toon==t4b) squadB[3] = toonData[toon];
					if(toon==t5b) squadB[4] = toonData[toon];
				}
			}

			var g1 = rankSquads(squad);
			var g2 = rankSquads(squadB);

			printTable(g1,g2);
		}

		function rankSquads(squad) {
			var guild = [];

			squad.forEach(function(toon,index) { //for each toon in the squad
				toon.forEach(function(player) { //for each player that has the toon
					if(guild[player.player]==undefined) guild[player.player] = {power:0,toon:[]};
					
					if(player.gp>=6000) {
						guild[player.player].toon[index+1] = [player.starLevel,player.gearLevel,player.level,player.gp];
						guild[player.player].power += player.gp;
					}
				});
			});

			return guild;
		}

		function printTable(guild, guildB) {

			var rows = [];
			for(var player in guild) {
				var squad = guild[player];

				var row = "<td>"+player+"</td><td>Squad 1</td><td>"+printToon(squad.toon[1])+"</td><td>"+printToon(squad.toon[2])+"</td><td>"+printToon(squad.toon[3])+"</td><td>"+printToon(squad.toon[4])+"</td><td>"+printToon(squad.toon[5])+"</td><td>"+numberWithCommas(squad.power)+"</td></tr>";
				rows.push({power:squad.power,html:row});
			}
			rows.sort(function(a,b) {
				return b.power-a.power;
			});

			var rowsB = [];
			for(var player in guildB) {
				var squadB = guildB[player];

				var rowB = "<td>"+player+"</td><td>Squad 2</td><td>"+printToon(squadB.toon[1])+"</td><td>"+printToon(squadB.toon[2])+"</td><td>"+printToon(squadB.toon[3])+"</td><td>"+printToon(squadB.toon[4])+"</td><td>"+printToon(squadB.toon[5])+"</td><td>"+numberWithCommas(squadB.power)+"</td></tr>";
				rowsB.push({power:squadB.power,html:rowB});
			}
			rowsB.sort(function(a,b) {
				return b.power-a.power;
			});

			var limit = $('#limit').val();
			var limitB = $('#limitb').val();
			
			rows = rows.slice(0,limit);		
			rowsB = rowsB.slice(0,limitB);		

			var toPrint = rows.concat(rowsB);
			toPrint.sort(function(a,b) {
				return b.power-a.power;
			});

			var table = $('#table');
			table.html("");
			var rownum = 0;
			toPrint.forEach(function(row) {
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

			<p>This tool will inspect your guild's roster and allow you to build different squads for Territory War. To find your ally code, open the game to the main screen and tap on your name in the upper left corner. Then look below your name for a 9 digit number.</p>

			<form action="<?=$self?>" method="get">
			<b>What is your SWGOH Ally Code:</b><br />
			<input type="text" id="ally" name="ally" placeholder="123-456-789" /> <input type="submit" value="ok" id="clickOnce" /><span id="clickOnceWarning" style="display:none">Please Wait</span>
			</form>

		<? } else { ?>
	
			<a name="top"></a>
			<div id="step3">
				<table class="styled">
					<thead>
					<tr>
						<td colspan="3">Squad 1</td>
						<td><select id="toon1"><option value="">ANY TOON</option></select></td>
						<td><select id="toon2"><option value="">ANY TOON</option></select></td>
						<td><select id="toon3"><option value="">ANY TOON</option></select></td>
						<td><select id="toon4"><option value="">ANY TOON</option></select></td>
						<td><select id="toon5"><option value="">ANY TOON</option></select></td>
						<td><select id="limit"><option value="10">Show Top 10</option><option value="20">Show Top 20</option><option value="25">Show Top 25</option><option value="50">Show All</option></select></td>
					</tr>
					<tr>
						<td colspan="3">Squad 2</td>
						<td><select id="toon1b"><option value="">ANY TOON</option></select></td>
						<td><select id="toon2b"><option value="">ANY TOON</option></select></td>
						<td><select id="toon3b"><option value="">ANY TOON</option></select></td>
						<td><select id="toon4b"><option value="">ANY TOON</option></select></td>
						<td><select id="toon5b"><option value="">ANY TOON</option></select></td>
						<td><select id="limitb"><option value="10">Show Top 10</option><option value="20">Show Top 20</option><option value="25">Show Top 25</option><option value="50">Show All</option></select></td>
					</tr>
					<tr>
						<td colspan="3">Player</td>
						<td>Leader</td>
						<td>Toon 2</td>
						<td>Toon 3</td>
						<td>Toon 4</td>
						<td>Toon 5</td>
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