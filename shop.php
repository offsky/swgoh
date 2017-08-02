<?
if(strpos($_SERVER['SERVER_NAME'], "docker")===false) {
	require_once("../vars.php");
	$db = new mymysqli("swgoh");
	error_reporting(0);
	$ads = true;
} else {
	require_once("../../vars.php");
	$db = new mymysqli("toodledo");
	error_reporting(E_ALL);
	$ads = false;
}

require("../libs.php");

$self = explode("/",$_SERVER['PHP_SELF']);
$self = array_pop($self);

if(empty($_POST['gear'])) {
	$gear = "";
	$cost = "";
	$qty = "";
} else {
	$gear = trim($_POST['gear']);
	$cost = !isset($_POST['cost']) ? 0 : intval($_POST['cost']);
	$qty = !isset($_POST['qty']) ? 0 : intval($_POST['qty']);
}

if(!empty($gear)) {
	//Collect the information
	$rs = $db->query("UPDATE swgoh_shop SET votes=votes+1 WHERE gear='".$db->str($gear)."' AND price=".$cost." and qty=".$qty);
	if($db->affected()) {
	} else {
		$rs = $db->query("INSERT INTO swgoh_shop(gear,price,qty,votes) VALUES('".$db->str($gear)."',".$cost.",".$qty.",1)");
	}
	//pull the ingredients and deconstruct
	$collection = [];
	$collection = getGearComponents($gear,$collection,1);
}


//construct the pull down menu
$options = array();
$rs = $db->query("SELECT src FROM swgoh_gear WHERE src like '%/gear/%' order by SUBSTRING_INDEX(`src`,'/',-2) asc");
while($row = $db->getNext($rs,1)) {
	$name = str_replace("http://www.swgoh.gg/db/gear/", "", $row['src']);
	$name = explode("/", $name);
	$name = str_replace("-"," ",$name[1]);
	$name = ucwords($name);
	$options[$name] = $row['src'];
}

?><!DOCTYPE html>
<html>
	<head>
		<title>SWGOH Tools - Shipments Price Tool</title>
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

			function update() {
				var es = $('#energyspend');
				if(es.length) {
					var energies = $('.js_energy');
					var sum = 0;
					var raid = false;
					$.each(energies, function(i,input) {
						var val = parseInt($(input).val());
						var item = $(input).data('item');

						var num = parseInt($('#n'+item).val());
						var has = parseInt($('#h'+item).val());
						if(isNaN(has)) has = 0;
						var needed = num-has;
						if(needed<0) needed = 0;

						if(val<999999) {
							sum += val*needed;
						} else {
							if(needed>0) raid = true;
						}
					});

					es.html(sum);
					if(raid) $('#raid').show();
					else $('#raid').hide();

					var alt = parseInt($('#alternative').html());

					if(alt>=sum && raid) $('#conclusion').html("It is cheaper to farm this, but you'll have to be very patient for the raid gear. If you can't wait for raids, buy!");
					else if(alt>=sum) $('#conclusion').html("It is better to farm this item.");
					else $('#conclusion').html("This is better to buy this item from shipments.");
				}
			}

			update();
			$('.js_has').on("change",update);
		});
		</script>
	</head>
	<body>
		<div id="top">
      	<a href="http://www.swgoh.life/index.html" id="logoClick"></a>
			<div id="ad">
			<? if($ads) { ?>
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
			<? } ?>
			</div>
		</div>
		<div id="middleSolo">
			<h1><a href="http://www.swgoh.life/index.html">More Tools</a> &gt; Shipments Price Tool</h1>
			<p>Thinking of buying something from Shipments? Wondering if it's worth it?</p>
			
			<form action="<?=$self?>" method="post">
				<b>What do you want to buy?</b><br />
				<select name="gear">
					<? foreach($options as $name=>$url) { ?>
						<option value="<?=$url?>" <? if($url==$gear) echo "selected='selected'";?>><?=$name?></option>
					<? } ?>
				</select>
				<br /><br />

				<b>Cost:</b><br />
				<input type="text" name="cost" size="10" placeholder="1400" value="<?=$cost?>" /> crystals<br /><br />
			
				<b>Quantity:</b><br />
				<input type="text" name="qty" size="10" placeholder="20" value="<?=$qty?>" /><br /><br />
	
				<br />
				<input type="submit" value="Calculate" class="btn" />
			</form>

			<?  if(!empty($collection)) { ?>
				<h3>Farming Requirements:</h3>
				
				<table class="info">
				<tr><th>Gear</th><th>Needed</th><th>You Have</th></tr>
				<?
				$i=0;
				foreach($collection as $url=>$data) {
					$i++;
					$hide = "";
					if($url==$gear) $hide = "style='display:none'";
					echo "<tr><td>".$data['title']."</td><td class='center'>".($data['num']*$qty)."</td>";
					echo "<td><input type='hidden' class='js_energy' value='".$data['energy']."' data-item='".$i."' /><input type='hidden' value='".($data['num']*$qty)."' id='n".$i."' />";
					echo "<input type='text' size='10' class='js_has' id='h".$i."' value='0' ".$hide." /></td></tr>";
				}
				?>
				</table>
				<br />
				<div class="rect">
					It will require <span id="energyspend">??</span> energy <span id="raid" style="display:none">and several raids</span> to farm <?=$qty?> of these.
					<br />
					With those <?=$cost?> crystals, you could use refreshes to get <span id="alternative"><?= ($cost/50)*120 ?></span> energy.
					<br /><br />
					<b id="conclusion"></b>
				</div>

			<? } ?>

			<br /><br /><hr />
			<? if($ads) { ?>
			<ins class="adsbygoogle"
				 style="display:block; text-align:center;"
				 data-ad-format="fluid"
				 data-ad-layout="in-article"
				 data-ad-client="ca-pub-0176506581219642"
				 data-ad-slot="6732943283"></ins>
			<script>
				 (adsbygoogle = window.adsbygoogle || []).push({});
			</script>
			<? } ?>

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