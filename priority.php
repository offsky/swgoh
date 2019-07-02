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

$numberOftoons = 15;

$self = explode("/",$_SERVER['PHP_SELF']);
$self = array_pop($self);

$ally = empty($_REQUEST['ally']) ? "" : trim($_REQUEST['ally']);
$ally = intval(preg_replace("/[^0-9]/","",$ally));

$guild = isset($_GET['guild'])?1:0;
$guild_id = 0;

if(!empty($ally) && !$guild) {
	$username = fetchPlayerFromSWGOHHelp($ally);
} else if(!empty($ally) && $guild) {
	list($guild_id,$data,$newData) = fetchGuildFromSWGOHHelp($ally,0,true);
} else if(empty($ally) && $guild && !empty($_GET['g'])) {
	$guild_id = intval($_GET['g']);
	list($guild_id,$data,$newData) = fetchGuildFromSWGOHHelp(0,$guild_id,true);
}

?><!DOCTYPE html>
<html>
	<head>
		<title>SWGOH Tools - Farming Priority List Maker</title>
 		<meta name="viewport" content="width=device-width, initial-scale=1">
	  	<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
		<meta charset="utf-8" />
		<style>
	#refresh {
		float:right;
		padding: 10px;
	}
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
   div.tooncell {
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
	td.tooncell i {
		font-size: 0.8em;
		margin-right: 2px;
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
		margin-left: 1px;
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

		for(var i=1;i<=<?=$numberOftoons?>;i++) {
			$('#toon'+i+' option[value="'+$('#toon'+i+'').data("pre")+'"]').prop("selected", "selected");
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

		var url = "";
		var url2 = "";
		<?
		//http://docker.experiment.com:8090/swgoh/api/guild.php
		//http://shard.swgoh.life/api/guild.php
		$url = "http://docker.experiment.com:8090/swgoh/api/guild.php";
		if(!empty($ally) && $guild) {
			echo "url='".$url."?a=".$ally."';\n";
			echo "url2='".$url."?gg=".$ally."';\n";
		} else if(empty($ally) && $guild && !empty($_GET['g'])) {
			$guild_id = intval($_GET['g']);
			echo "url='".$url."?g=".$guild_id."';\n";
		}
		?>

		function backupFetch(url) {
			if(url) {
				$.ajax({
					url: url,
					dataType: "json",
					timeout: 30000
				})
				.done(function(d) {
					if(d==1) {
						$('#refresh').html("New data availble. Refresh the page to see it.");
					} else {
						$('#refresh').hide();
						$('#sync_notice').hide();
						$('#sync_error').show();
					}
				}).fail(function(d) {
					$('#refresh').hide();
					$('#sync_notice').hide();

					if(d.statusText=="timeout") {
						$('#sync_timeout').show();
					} else {
						$('#sync_error').show();
					}
				});
			} else {
				$('#refresh').hide();
			}
		}

		if(url) {
			$.ajax({
				url: url,
				dataType: "json",
				timeout: 30000
			})
			.done(function(d) {
				if(d==1) {
					$('#refresh').html("New data availble. Refresh the page to see it.");
				} else {
					$('#refresh').hide();
					$('#sync_notice').hide();
					$('#sync_error').show();
				}
			}).fail(function(d) {
				if(url2) {
					backupFetch(url2);
				} else {
					$('#refresh').hide();
					$('#sync_notice').hide();

					if(d.statusText=="timeout") {
						$('#sync_timeout').show();
					} else {
						$('#sync_error').show();
					}
				}
			});
		} else {
			$('#refresh').hide();
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

		<? if(empty($ally) && empty($guild_id)) { ?>

			<p>This tool will allow you to make a list of farming priorities for personal use, or to share with your guild. To find your ally code, open the game to the main screen and tap on your name in the upper left corner. Then look below your name for a 9 digit number.</p>

			<form action="<?=$self?>" method="get">
			<b>What is your SWGOH Ally Code:</b><br />
			<input type="text" id="ally" name="ally" placeholder="123-456-789" /> 
			<br /><br />
			<b>Pick up to <?=$numberOftoons?> characters that you want to work on:</b><br />
			<? 
				$ships = false;
				$options = "<option disabled>-- TOONS --</option>";
				$rs = $db->query("SELECT id,name,type FROM swgoh_toons2 order by type asc, name asc");
				while($row = $db->getNext($rs,1)) {
					if($row['type']==2 && !$ships) {
						$ships = true;
						$options .= "<option disabled> </option><option disabled>-- SHIPS --</option>";
					}
					$options .= "<option value='".$row['id']."'>".$row['name']."</option>";
				}

				for($i=1;$i<=$numberOftoons;$i++) { 
					?>
					<select class="toonselect" name="t<?=$i?>" id="toon<?=$i?>" data-pre="<?=empty($_GET['t'.$i])?"":$_GET['t'.$i]?>">
						<option value="">Select</option>
						<?=$options?>
					</select><br />
					<? 
				} 
			?>
			(you can leave some blank)<br />
			<br />
			<input type="submit" value="Show Me" class="btn" />
			</form>

		<? } else { ?>
					
			<p>Here is your farming priority list. Bookmark this page to return quickly and see your progress. You can share also this with your guild by copy and pasting the link in the text box below.</p>
			<?
			unset($_GET['ally']);
			unset($_GET['guild']);
			$query = http_build_query($_GET);
			?>
			<? if(!empty($ally)) { ?>
				Account: <?=!empty($username)?$username:""?> (<?=$ally?>) (<a href="priority.php?<?=$query?>">pick another</a>)<br />
			<? } else { ?>
				Account: Guild (<?=$guild_id?>) (<a href="priority.php?<?=$query?>">pick another</a>)<br />
			<? } ?>

			<? if(!$guild) { ?>
				<b>Share Generic Link:</b><input type="text" value="http://shard.swgoh.life/priority.php?<?=$query?>" size="40" /> (copy and paste this link)
			<? } else { ?>
				<b>Share Guild Link:</b><input type="text" value="http://shard.swgoh.life/priority.php?g=<?=$guild_id?>&guild=1&<?=$query?>" size="40" /> (copy and paste this link)
			<? } ?>
			<br />
			<span id="refresh"><i class="fa fa-spin fa-spinner"></i> Refreshing</span>
			<div class="link_grp">
				<a href="priority.php?ally=<?=$ally?>&<?=$query?>" class="seg<? if(!$guild) echo ' active';?>">Show Just Me</a>
				<a href="priority.php?ally=<?=$ally?>&guild=1&<?=$query?>" class="seg<? if($guild) echo ' active';?>">Show My Guild</a>
			</div>

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

			$toonDict = array();
			$rs = $db->query("SELECT id,name FROM swgoh_toons2 order by type asc, name asc");
			while($row = $db->getNext($rs,1)) {
				$toonDict[$row['id']] = $row['name'];
			}

			$sql = "";
			$selected = array();
			for($i=1;$i<=$numberOftoons;$i++) {
				if(isset($_GET['t'.$i]) && isset($toonDict[$_GET['t'.$i]])) {
					$toon = $toonDict[$_GET['t'.$i]];
					$selected[$_GET['t'.$i]] = $toon;
					if(empty($sql)) $sql .= " toon='".$db->str($toon)."'";
					else $sql .= " OR toon='".$db->str($toon)."'";
				}
			}

			if(!$guild) {
				$rs = $db->query("SELECT toon,level,gear,stars,zeta,ship from toons WHERE (".$sql.") AND user='".$db->str($ally)."' order by toon asc");
				$count = $db->count($rs);
				while($row = $db->getNext($rs,1)) {
					$per = 0;
					if(isset($perday[$row['toon']])) $per = $perday[$row['toon']];
					printOneToonFarm($row,7,11,$per);
				}
				if(empty($count) && empty($username)) {
					echo "We were unable to fetch your account. Sorry. Please check your ally code.";
				} else if(empty($count)) {
					echo "You do not have any of the selected characters.";
				}
			} else { 
				
				$roster = array();
				foreach($data as $key=>$users) {
					foreach($users as $user) {
						if(empty($roster[$user->player])) $roster[$user->player] = array();
						$roster[$user->player][$key] = array("stars"=>$user->starLevel,"gear"=>$user->gearLevel,"level"=>$user->level,"zeta"=>count($user->zetas),"ship"=>($user->type==2));
					}
				}
				// echo "<pre>";
				// print_r($data);
				// echo "</pre>";
				?>
				<table class="styled">
					<thead>
					<tr>
						<td>Player</td>
						<? foreach($selected as $sel) echo "<td>".$sel."</td>";?>
					</tr>
					</thead>
					<tbody id="table">
						<? foreach($roster as $name=>$player) { ?>
						<tr><td><?=$name?></td>
						<? 
							foreach($selected as $key=>$sel) {
								echo "<td class='tooncell'>";
								if(isset($player[$key])) printOneToonTable($player[$key],6,11);
								echo "</td>";
							}
						?>
						</tr>
						<? } ?>
					</tbody>
				</table>

				<? 
				if(empty($roster)) {
					echo "<br /><br /><span id='sync_notice'>We are fetching your data. Please refresh the page when it's ready (see above). It may take a full minute.</span><span id='sync_error' style='display:none'>We were unable to fetch your guild's account. Sorry. Please check your ally code.</span><span id='sync_timeout' style='display:none'>Sorry, the servers are overloaded. Please try again later.</span>";
				}
				?>

			<? } ?>

			<? if(count($selected)==0) echo "You didn't pick any characters to farm. Please go back and pick at least one character from the drop down menus.";?>

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