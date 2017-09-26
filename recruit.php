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

$self = explode("/",$_SERVER['PHP_SELF']);
$self = array_pop($self);

$gname = empty($_POST['gname']) ? "" : strtolower(trim($_POST['gname']));
$gpass = empty($_POST['gpass']) ? "" : strtolower(trim($_POST['gpass']));
$glevel = empty($_POST['glevel']) ? 85 : intval(trim($_POST['glevel']));
$gtoons = empty($_POST['gtoons']) ? 10 : intval(trim($_POST['gtoons']));
$ggear = empty($_POST['ggear']) ? 10 : intval(trim($_POST['ggear']));


$me = empty($_POST['gamename']) ? "" : strtolower(trim($_POST['gamename']));
$ally = empty($_POST['ally']) ? "" : trim($_POST['ally']);
$gg = empty($_POST['gg']) ? "" : trim($_POST['gg']);
$toons = empty($_POST['toons']) ? "" : trim($_POST['toons']);
$gear = empty($_POST['gear']) ? "" : trim($_POST['gear']);
$contact = empty($_POST['contact']) ? "" : substr(trim($_POST['contact']),0,500);


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
			@media(min-width: 520px) { #top { padding-bottom:0px} #ad { height:90px;} .swgoh_ad { left:inherit; top:0; right:0; width: 328px; height: 90px; } }
			@media(min-width: 720px) { #top { padding-bottom:0px} #ad { height:90px;} .swgoh_ad { left:inherit; top:0; right:0; width: 528px; height: 90px; } }
			@media(min-width: 920px) { #top { padding-bottom:0px} #ad { height:90px;} .swgoh_ad { left:inherit; top:0; right:0; width: 728px; height: 90px; } }
			@media(min-width: 1200px) { #top { padding-bottom:0px} #ad { height:90px;} .swgoh_ad { left:inherit; top:0; right:0; width: 1000px; height: 90px; } }
  		</style>
  		<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
  		<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
		<script>
		$(document).ready(function() {

			
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
			<h1><a href="http://www.swgoh.life/index.html">More Tools</a> &gt; Guild Recruiting Tools</h1>
		
			<? if(empty($_GET['p'])) { ?>
				<p>Are you a guild looking for new members, or a player looking for a great guild?  This page will help both of you. All information entered into this tool will be publicly available to anyone.</p>
		
				<br />
				<a href="recruit.php?p=1" class="btn">I am a Guild looking for players</a><br /><br /><br /><br />

				<a href="recruit.php?p=2" class="btn">I am a Player looking for a guild</a><br /><br />
			
			<? } else if(!empty($_GET['p']) && $_GET['p']==1) { ?>
				<p>Your are a guild looking for new members.</p>

				<form action="<?=$self?>" method="post">
					<div class="half">
					<b>Your Guild Name:</b> (required)<br />
					<input type="text" id="gname" name="gname" value="<?=$gname?>" /><br /><br />

					<b>Your Guild's SWGOH.GG URL:</b> (required)<br />
					<input type="text" id="gg" name="gg" size="40" placeholder="https://swgoh.gg/g/1234/guildname/"/><br /><br />
					
					<b>Required Player Level:</b><br />
					<input type="text" id="glevel" name="glevel" value="<?=$glevel?>" /><br /><br />

					<b>Number of 7* Toons Required:</b><br />
					<input type="text" id="gtoons" name="gtoons" value="<?=$gtoons?>" /><br /><br />

					<b>Number of Gear 11 Toons Required:</b><br />
					<input type="text" id="ggear" name="ggear" value="<?=$ggear?>" /><br /><br />

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


					<b>Passcode:</b><br />
					This is not intended to be strong security. You can share it with your guild leaders. It will be required to edit your listing. Do not forget it because we have not built a way to recover it yet.<br />
					<input type="text" id="gpass" name="gpass" value="<?=$gpass?>" /><br /><br />

					<br />
					<input type="submit" value="Search for Players" class="btn" />
					</div>
				</form>

			<? } else if(!empty($_GET['p']) && $_GET['p']==2) { ?>
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
					Don't know? We filled in in for you or <a href="https://whatismytimezone.com/">check here</a>. Ex: GMT-8<br />
					<input type="text" id="gmt" name="gmt" /><br /><br />



					</div>
					<div class="half">

					<b>How many toons do you have at 7&#9733;?</b><br />
					<input type="text" id="toons" name="toons" size="5" /><br /><br />

					<b>How many toons do you have at gear tier 11?</b><br />
					<input type="text" id="gear" name="gear" size="5" /><br /><br />

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