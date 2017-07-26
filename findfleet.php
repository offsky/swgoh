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

$me = empty($_POST['gamename']) ? "" : strtolower(trim($_POST['gamename']));
$ally = empty($_POST['ally']) ? "" : trim($_POST['ally']);
$gg = empty($_POST['gg']) ? "" : trim($_POST['gg']);
$contact = empty($_POST['contact']) ? "" : substr(trim($_POST['contact']),0,500);

$mates = array();
for($i=1;$i<=10;$i++) {
	if(!empty($_POST['mate'.$i]) && trim($_POST['mate'.$i])!==$me) $mates[] = strtolower(trim($_POST['mate'.$i]));
}

//Insert me, or update me
if(!empty($me) && !empty($ally)) {
	$rs = $db->query("SELECT * FROM swgoh_fleet WHERE ally='".$db->str($ally)."'");
	if($user = $db->getNext($rs,1)) {
		$set = "";
		if(!empty($gg)) $set .= ",gg='".$db->str($gg)."'";
		if(!empty($contact)) $set .= ",contact='".$db->str($contact)."'";
		if(!empty($mates)) {
			$old = explode(",",$user['mates']);
			$mates = array_merge($old,$mates);
			$mates = array_unique($mates);
			$mm = implode(",", $mates);
			$mm = str_replace(",".$me.",", ",", $mm);
			$mm = str_replace(",,", ",", $mm);
			$set .= ",mates=',".$db->str($mm).",'";
		}
		if(!empty($set)) $db->query("UPDATE swgoh_fleet SET t=".time()."".$set." WHERE user='".$db->str($me)."' AND ally='".$db->str($ally)."'");
	} else {
		$mm = implode(",", $mates);
		$mm = str_replace(",".$me.",", ",", $mm);
		$db->query("INSERT INTO swgoh_fleet(user,mates,t,ally,gg,contact) VALUES('".$db->str($me)."',',".$db->str($mm).",',".time().",'".$db->str($ally)."','".$db->str($gg)."','".$db->str($contact)."')");
	}
}

?><!DOCTYPE html>
<html>
	<head>
		<title>SWGOH Tools - Find your SWGOH Fleet Arena Shard</title>
	  	<link rel="stylesheet" type="text/css" href="style.css?2" media="screen" />
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

		var storage = null;
		if(window.localStorage) storage = window.localStorage;
		else if(window.globalStorage) storage = window.globalStorage[location.hostname];

		var name = fetch("name");
		if(name) $('#gamename').val(name);
		$('#gamename').on("change",function(e) {
			store("name",$('#gamename').val());
		});

		var ally = fetch("ally");
		if(ally) $('#ally').val(ally);
		$('#ally').on("change",function(e) {
			store("ally",$('#ally').val());
		});

		var gg = fetch("gg");
		if(gg) $('#gg').val(gg);
		$('#gg').on("change",function(e) {
			store("gg",$('#gg').val());
		});

		var contact = fetch("contact");
		if(contact) $('#contact').val(contact);
		$('#contact').on("change",function(e) {
			store("contact",$('#contact').val());
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
		<h1><a href="http://www.swgoh.life/index.html">More Tools</a> &gt; Find your SWGOH Fleet Arena Shard</h1>

		<?	
			$rs = $db->query("SELECT * FROM swgoh_fleet WHERE user='".$db->str($me)."' AND ally='".$db->str($ally)."'");
			if($user = $db->getNext($rs,1)) {
				?>
				<b>Your Name:</b> <?=$user['user']?><br />
				<b>Your Ally Code:</b> <?=$user['ally']?><br />
				<b>Your SWGOH.GG Link:</b> <a href="http://www.swgoh.gg/u/<?=$user['gg']?>/" target="_blank"><?=$user['gg']?></a><br />
				<b>Your Contact Info:</b> <?=$user['contact']?><br />
				<?
			}
		?>


		<? if(empty($me) || empty($ally) || empty($user)) { ?>
			<p>Do you want to get in touch with the other players on your SWGOH Fleet Arena shard?  Please enter as much information as you can, and we'll try to find your shardmates and give you their contact information, if possible.</p>
			<p>Looking for Squad Arena? That's <a href="index.php">over here</a>.</p>

			<?
				$self = explode("/",$_SERVER['PHP_SELF']);
				$self = array_pop($self);
			?>
			<form action="<?=$self?>" method="post">
				<div class="half">
				<b>Your Game Name:</b> (required)<br />
				Found in the upper left corner inside the game on the home screen.<br />
				<input type="text" id="gamename" name="gamename" value="<?=$me?>" /><br /><br />

				<b>Your Ally Code:</b> (required)<br />
				Found in the Allies section of the game at the top. Ex: 123-456-789.<br />
				<input type="text" id="ally" name="ally" /><br /><br />
				
				<b>Your Contact Info:</b> (optional)<br />
				Explain how you want to be contacted. Ex: "Message me on Discord at User#1234"<br />
				<textarea type="text" id="contact" name="contact" rows="3" cols="40"></textarea>
				<br /><br />

				<b>Your SWGOH.GG Name:</b> (optional)<br />
				This helps people find you.<br />
				https://swgoh.gg/u/<input type="text" id="gg" name="gg" />/<br /><br />
				</div>
				<div class="half">
				<b>Enter the names of the top 10 people in your shard's Fleet Arena:</b><br />
				<input type="text" name="mate1" /><br />
				<input type="text" name="mate2" /><br />
				<input type="text" name="mate3" /><br />
				<input type="text" name="mate4" /><br />
				<input type="text" name="mate5" /><br />
				<input type="text" name="mate6" /><br />
				<input type="text" name="mate7" /><br />
				<input type="text" name="mate8" /><br />
				<input type="text" name="mate9" /><br />
				<input type="text" name="mate10" /><br />

				<br />
				<input type="submit" value="Search for Shard" class="btn" />
				</div>
			</form>
		<? } else { ?>
			<? 
				$rs = $db->query("SELECT count(*) FROM swgoh_fleet");
				$num = $db->getFirst($rs);
			?>
			<p><b>Note:</b> This tool is very new, so it hasn't collected enough names to be useful yet (<?=$num?> so far). Please encourage other people to enter their info so that this can work better.</p>

			<p>We found the following people who might be in your shard.</p>
			<br />
			<table class="info">
				<tr><th>Name</th><th>SWGOH.GG Link</th><th>Contact Info</th></tr>
				<? 
					$any = false;
					
					$where1 = "ally!='".$db->str($ally)."' AND mates LIKE '%,".$db->str($me).",%'"; //People who have listed me as mates
					$where2 = "ally!='".$db->str($ally)."' AND (1=2 "; //people who I listed as mates
					$where3 = "ally!='".$db->str($ally)."' AND (1=2 "; //people who listed my mates as their mates
					foreach ($mates as $mate) {
						if(!empty($mate)) {
							$where2 .= " OR user='".$db->str($mate)."'";
							$where3 .= " OR mates LIKE '%,".$db->str($mate).",%'";
						}
					}
					$where2 .= ")";
					$where3 .= ")";

					//find people who listed me as mates
					$rs = $db->query("SELECT * FROM swgoh_fleet WHERE (".$where1.") OR (".$where2.") OR (".$where3.")");
					while($row = $db->getNext($rs,1)) {
						$any = true;
						?>
							<tr>
								<td><b><?=$row['user']?></b></td>
								<td><a href="http://www.swgoh.gg/u/<?=$row['gg']?>/" target="_blank"><?=$row['gg']?></a></td>
								<td><?=$row['contact']?></td>
							</tr>
						<?
					}

					if(!$any) echo "<tr><td colspan='4'>None found. Please try again in a few days when we have more data collected.</td></tr>";
				?>
			</table>

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