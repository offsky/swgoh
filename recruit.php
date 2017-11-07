<?
if(strpos($_SERVER['SERVER_NAME'], "docker")===false) {
	require_once("../vars.php");
	$db = new mymysqli("swgoh");
	error_reporting(0);
} else {
	require_once("../../vars.php");
	$db = new mymysqli("toodledo");
	error_reporting(E_ALL);
}

function makeInteger($value) {
	$value=preg_replace("/[^0-9]/","",$value); //remove non numeric
	return intval($value);
}

$self = explode("/",$_SERVER['PHP_SELF']);
$self = array_pop($self);

$gname = empty($_POST['gname']) ? "" : trim($_POST['gname']);
$gg = empty($_POST['gg']) ? "" : trim($_POST['gg']);
$gpass = empty($_POST['gpass']) ? "" : strtolower(trim($_POST['gpass']));
$glevel = empty($_POST['glevel']) ? 85 : makeInteger(trim($_POST['glevel']));
$gtoons = empty($_POST['gtoons']) ? 10 : makeInteger(trim($_POST['gtoons']));
$ggear = empty($_POST['ggear']) ? 10 : makeInteger(trim($_POST['ggear']));
$ggp = empty($_POST['ggp']) ? 1000000 : makeInteger(trim($_POST['ggp']));
$gtb = empty($_POST['gtb']) ? 0 : makeInteger(trim($_POST['gtb']));
$ghaat = empty($_POST['ghaat']) ? 0 : makeInteger(trim($_POST['ghaat']));
$active = empty($_POST['active']) ? 0 : makeInteger(trim($_POST['active']));

$grequire = empty($_POST['grequire']) ? '' : trim($_POST['grequire']);
$contact = empty($_POST['contact']) ? '' : trim($_POST['contact']);

if(!empty($gpass)) {
	$rs = $db->query("SELECT * FROM recruit_guild WHERE gg='".$db->str($gg)."' AND password='".$db->str($gpass)."'");
	if($row = $db->getNext($rs,1)) {
		$db->query("UPDATE recruit_guild SET name='".$db->str($gname)."',lvl=".$glevel.",stars=".$gtoons.",gear=".$ggear.",gp=".$ggp.",tb=".$gtb.",haat=".$ghaat.",active=".$active.",other='".$db->str($grequire)."',contact='".$db->str($contact)."',lastupdate=".time()." WHERE gg='".$db->str($gg)."' AND password='".$db->str($gpass)."'");
		$_GET['g']=$row['id'];
	} else {
		$db->query("INSERT INTO recruit_guild(name,password,gg,lvl,stars,gear,gp,tb,haat,active,other,contact,lastupdate) VALUES('".$db->str($gname)."','".$db->str($gpass)."','".$db->str($gg)."',".$glevel.",".$gtoons.",".$ggear.",".$ggp.",".$gtb.",".$ghaat.",".$active.",'".$db->str($grequire)."','".$db->str($contact)."',".time().")");
		$_GET['g']=$db->lastID();
	}
	$_GET['p']=4;
}

$level = empty($_GET['level']) ? 85 : makeInteger(trim($_GET['level']));
$toons = empty($_GET['toons']) ? 10 : makeInteger(trim($_GET['toons']));
$gear = empty($_GET['gear']) ? 10 : makeInteger(trim($_GET['gear']));
$gp = empty($_GET['gp']) ? 100000 : makeInteger(trim($_GET['gp']));


?><!DOCTYPE html>
<html>
	<head>
		<title>SWGOH Tools - Guild Recruiting Tools</title>
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
		   b.large {
		   	font-size: 2em;
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
  		<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
  		<script type="text/javascript">
		$(document).ready(function() {
			// console.log($('#gmt'));
			// if($('#gmt').length) {
			// 	$('#gmt').value(2);
			// }
			
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
			<? if(empty($_GET['p'])) { ?>
				<h1><a href="http://www.swgoh.life/index.html">More Tools</a> &gt; Guild Recruiting Tools</h1>
	
				<p>Are you a guild looking for new members, or a player looking for a great guild?  This page will help both of you. All information entered into this tool will be publicly available to anyone.</p>
		
				<br />
				<a href="recruit.php?p=2" class="btn">Find a Guild</a> (for players)<br /><br /><br /><br />
				<a href="recruit.php?p=1" class="btn">Add or Update Your Guild</a> (for guild officers)<br /><br /><br /><br />

			
			<? } else if(!empty($_GET['p']) && $_GET['p']==1) { ?>
				<h1><a href="http://www.swgoh.life/index.html">More Tools</a> &gt; <a href="recruit.php">Guild Recruiting Tools</a> &gt; Add Guild</h1>

				<p>Your are a guild looking for new members.</p>

				<form action="<?=$self?>" method="post">
					<div class="half">
					<b>Your Guild Name:</b> (required)<br />
					<input type="text" id="gname" name="gname" value="<?=$gname?>" /><br /><br />

					<b>Your Guild's SWGOH.GG URL:</b> (required)<br />
					<input type="text" id="gg" name="gg" size="40" placeholder="https://swgoh.gg/g/1234/guildname/"/><br /><br />
					
					<b>Required Player Level:</b><br />
					<input type="text" id="glevel" name="glevel" value="<?=$glevel?>" size="5" /><br /><br />

					<b>Number of 7<i class="fa fa-star"></i> Toons Required:</b><br />
					<input type="text" id="gtoons" name="gtoons" value="<?=$gtoons?>" size="5" /><br /><br />

					<b>Number of Gear 11 Toons Required:</b><br />
					<input type="text" id="ggear" name="ggear" value="<?=$ggear?>" size="5" /><br /><br />

					<b>Galactic Power Required:</b><br />
					<input type="text" id="ggp" name="ggp" value="<?=number_format($ggp)?>" /><br /><br />

					<b>Your Guild's Best Territory Battle:</b><br />
					<input type="text" id="gtb" name="gtb" value="<?=$gtb?>" size="5" /> stars<br /><br />

					<b>Can your Guild complete Heroic AAT (HAAT)?</b><br />
					<input type="radio" id="ghaat" name="ghaat" value="0" checked="checked" />NO &nbsp;&nbsp; <input type="radio" id="ghaat" name="ghaat" value="1" />YES<br /><br />

					</div>
					<div class="half">

					
					<b>Other Requirements or Information:</b><br />
					You can explain your Raid rules, guild reset time, which guild chat app you use, etc.<br />
					<textarea type="text" id="grequire" name="grequire" rows="5" cols="40"></textarea>
					<br /><br />


					<b>Your Contact Info:</b><br />
					Explain how you want to be contacted. Ex: "Message me on Discord at User#1234"<br />
					<textarea type="text" id="contact" name="contact" rows="3" cols="40"></textarea>
					<br /><br />


					<b>Actively Recruiting</b><br />
					<input type="radio" id="active" name="active" value="0" checked="checked" />NO &nbsp;&nbsp; <input type="radio" id="active" name="active" value="1" />YES<br /><br />


					<b>Passcode:</b>  (required)<br />
					This is not intended to be strong security. You can share it with your guild leaders. It will be required to edit your listing. Do not forget it because we have not built a way to recover it yet.<br />
					<input type="text" id="gpass" name="gpass" value="<?=$gpass?>" /><br /><br />

					<br />
					<input type="submit" value="Save My Guild" class="btn" />
					</div>
				</form>

			<? } else if(!empty($_GET['p']) && $_GET['p']==2) { ?>
				<h1><a href="http://www.swgoh.life/index.html">More Tools</a> &gt; <a href="recruit.php">Guild Recruiting Tools</a> &gt; Find Guild</h1>

				<p>Your are a player looking for a guild.</p>

				<form action="<?=$self?>" method="get">
					<input type="hidden" name="p" value="3" />
					<div class="half">
					
					<b>Your Player Level:</b><br />
					<input type="text" id="level" name="level" value="<?=$level?>" /><br /><br />

					<b>Number of 7<i class="fa fa-star"></i> Toons You Have:</b><br />
					<input type="text" id="toons" name="toons" value="<?=$toons?>" /><br /><br />

					<b>Number of Gear 11 Toons You Have:</b><br />
					<input type="text" id="gear" name="gear" value="<?=$gear?>" /><br /><br />

					<b>Your Galactic Power:</b><br />
					<input type="text" id="gp" name="gp" value="<?=number_format($gp)?>" /><br /><br />

					<input type="submit" value="Search" class="btn" />

					</div>
				
				</form>
			<? } else if(!empty($_GET['p']) && $_GET['p']==3) { ?>
				<h1><a href="http://www.swgoh.life/index.html">More Tools</a> &gt; <a href="recruit.php">Guild Recruiting Tools</a> &gt; <a href="recruit.php?p=2">Find Guild</a> &gt; Search Results</h1>

				<table>
					<tr>
						<th></th>
						<th>Guild Name</th>
						<th>Level Requirement</th>
						<th>Min 7<i class="fa fa-star"></i></th>
						<th>Min Gear 11</th>
						<th>Min Galactic Power</th>
					</tr>
				<?
				$sql = "";
				if(!empty($level)) $sql .= " AND lvl<=".$level;
				if(!empty($toons)) $sql .= " AND stars<=".$toons;
				if(!empty($gear)) $sql .= " AND gear<=".$gear;
				if(!empty($gp)) $sql .= " AND gp<=".$gp;

				$rs = $db->query("SELECT * FROM recruit_guild WHERE active=1".$sql." order by lastupdate desc");
				$num=0;
				while($row = $db->getNext($rs,1)) {
					$num++;
					?>
					<tr>
						<td><?=$num?></td>
						<td><a href="<?=$self?>?p=4&g=<?=$row['id']?>"><?=$row['name']?></a></td>
						<td><?=$row['lvl']?></td>
						<td><?=$row['stars']?></td>
						<td><?=$row['gear']?></td>
						<td><?=number_format($row['gp'])?></td>
					</tr>
					<?
				}
				if($num==0) {
					?>
					<tr><td colspan="7">No Guilds were found matching your level and abilities.</td></tr>
					<?
				}
				?>
				</table>
			<? } else if(!empty($_GET['p']) && $_GET['p']==4) { ?>
				<h1><a href="http://www.swgoh.life/index.html">More Tools</a> &gt; <a href="recruit.php">Guild Recruiting Tools</a> &gt; <a href="recruit.php?p=2">Find Guild</a> &gt; Guild Page</h1>
				<?				
				$rs = $db->query("SELECT * FROM recruit_guild WHERE id=".intval($_GET['g']));
				if($row = $db->getNext($rs,1)) {
					?>
					<br />
					<p><b class="large"><?=$row['name']?></b> is <? if(empty($row['active'])) echo "not currently"?> looking for new members.</p>
					<p>They can get <?=$row['tb']?><i class="fa fa-star"></i> in Territory Battles and are <? if(empty($row['haat'])) echo "not yet completing Heroic AAT Raids (HAAT)"; else echo "completing Heroic AAT Raids (HAAT)"; ?>.</p>
					<br />
					<h2>Requirements</h2>
					<table>
						<tr>
							<th>Min Player Level</th>
							<th>Min 7<i class="fa fa-star"></i></th>
							<th>Min Gear 11</th>
							<th>Min Galactic Power</th>
						</tr>
						<tr>
							<td><?=$row['lvl']?></td>
							<td><?=$row['stars']?></td>
							<td><?=$row['gear']?></td>
							<td><?=number_format($row['gp'])?></td>
						</tr>
					</table>

					<h2>Other Requirements</h2>
					<?=nl2br(htmlentities($row['other']))?>

					<h2>Contact Info</h2>
					<a href="<?=$row['gg']?>" target="_blank">View Their SWGOH.GG Page</a>
					<br />
					<?=nl2br(htmlentities($row['contact']))?>

					<br />

				<? } else { ?>
					<p>Guild Not found. Please <a href="<?=$self?>">Search Again</a></p>
				<? } ?>
			<? } else if(false) { ?>
				<p>Your are a player looking for new members.</p>

				<form action="<?=$self?>" method="post">
					<div class="half">
					<b>Your Game Name:</b> (required)<br />
					Found in the upper left corner inside the game on the home screen.<br />
					<input type="text" id="gamename" name="gamename" value="<?=$me?>" /><br /><br />

					<b>Your Ally Code:</b> (required)<br />
					Found in the Allies section of the game at the top. Ex: 123-456-789.<br />
					<input type="text" id="ally" name="ally" /><br /><br />
					
					<b>Your SWGOH.GG Name:</b> (optional)<br />
					This helps guilds evaluate you.<br />
					https://swgoh.gg/u/<input type="text" id="gg" name="gg" />/<br /><br />

					<b>What is your Timezone offset from GMT?</b><br />
					Don't know? We filled it in for you or <a href="https://whatismytimezone.com/">check here</a>. Ex: GMT-8<br />
					<input type="text" id="gmt" name="gmt" /><br /><br />



					</div>
					<div class="half">

					<b>How many toons do you have at 7<i class="fa fa-star"></i>?</b><br />
					<input type="text" id="toons" name="toons" size="5" value="<?=$toons?>" /><br /><br />

					<b>How many toons do you have at gear tier 11?</b><br />
					<input type="text" id="gear" name="gear" size="5" value="<?=$gear?>" /><br /><br />

					<b>What is your total GP?</b><br />
					<input type="text" id="gp" name="gp" size="12" value="<?=number_format($gp)?>" /><br /><br />

					<b>Have you ever soloed the Rancor on Heroic mode?</b><br />
					<input type="checkbox" value="1" name="rancor" /> Yes<br /><br />

					<b>Have you ever scored at least 1 Million combined damage in Heroic AAT?</b><br />
					<input type="checkbox" value="1" name="haat" /> Yes<br /><br />


					<b>Your Contact Info:</b><br />
					Explain how you want to be contacted. Ex: "Message me on Discord at User#1234"<br />
					<textarea type="text" id="contact" name="contact" rows="3" cols="40"></textarea>
					<br /><br />


					<br />
					<input type="submit" value="Search for a Guild" class="btn" />
					</div>
				</form>

			<? } ?>




			<br /><br /><hr />
			<ins class="adsbygoogle"
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