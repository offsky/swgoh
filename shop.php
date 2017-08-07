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
					else $('#conclusion').html("It is better to buy this item from shipments.");
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
			
			<div class="half">
				<form action="<?=$self?>" method="post">
					<b>What do you want to buy?</b><br />
					<select name="gear">
						<? foreach($options as $name=>$url) { ?>
							<option value="<?=$url?>" <? if($url==$gear) echo "selected='selected'";?>><?=$name?></option>
						<? } ?>
					</select>
					<br /><br />

					<b>Cost:</b><br />
					<input type="text" name="cost" size="10" placeholder="200" value="<?=$cost?>" /> crystals<br /><br />
				
					<b>Quantity:</b><br />
					<input type="text" name="qty" size="10" placeholder="10" value="<?=$qty?>" /><br /><br />
		
					<br />
					<input type="submit" value="Calculate" class="btn" />
				</form>
			</div>
			<div class="half">
				<? if(!empty($collection)) { ?>
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
			</div>

			<br /><br /><hr />

			<p>In the Shipments section you can use crystals or credits to purchase gear and character shards. The four gear items at the bottom are very cheap and you should purchase these every chance you get to fulfill your daily challenge requirement and to stockpile gear that you may need in the future.  If you do well in arena, you'll have free crystals to spend, otherwise you'll have to purchase crystals with real money.</p>
			<p>If you want to spend your crystals wisely, its important to understand that some items are a good value, and other items are a bad value. For example, a full "Mk 3 Carbanti Sensor Array" costs 1400 crystals.  With those 1400 crystal you could do 28 refreshes of your Battle Energy which is 3360 energy. If you spend all of that energy farming nodes 6-G-Dark, 7-G-Light, and 8-F-Light you'll get between 70 and 90 "Mk 3 Carbanti Sensor Array Salvage" (It only takes 50 to craft a full array) as well as hundreds of other gear items that you probably need. So, for this gear item, it's a better deal to spend your crystals on refreshes and avoid purchasing it directly from the store.  On the other hand, you can purchase a full "Mk 8 Biotech Implant Prototype" for only 750 crystals. With these 750 crystals you could refresh your energy 15 times for 1800 energy.  Use this to farm node 9-E-Dark and you'll only get 40 of the Biotech Implant Components (you need 50), so in this case it's a better use of crystals to buy it from the store.</p>

			<table class='info'><tr><th>Raid Only Gear</th><th>Store Price</th><th>Farming Price</th></tr>	
			<tr><td>Mk 10 TaggeCo Holo Lens Salvage</td><td>1250</td>
			<td>-</td></tr>	
			<tr><td>Mk 11 BlasTech Weapon Mod Salvage</td><td>560</td>
			<td>-</td></tr>	
			<tr><td>Mk 11 BlasTech Weapon Mod Salvage</td><td>1400</td>
			<td>-</td></tr>	
			<tr><td>Mk 3 Zaltin Bacta Gel Salvage</td><td>1400</td>
			<td>-</td></tr>	
			<tr><td>Mk 4 Sienar Holo Projector Salvage</td><td>1400</td>
			<td>-</td></tr>	
			<tr><td>Mk 5 CEC Fusion Furnace Salvage</td><td>560</td>
			<td>-</td></tr>	
			<tr><td>Mk 6 CEC Fusion Furnace Salvage</td><td>1400</td>
			<td>-</td></tr>	
			<tr><td>Mk 6 Merr-Sonn Thermal Detonator Salvage</td><td>560</td>
			<td>-</td></tr>	
			<tr><td>Mk 6 Merr-Sonn Thermal Detonator Salvage</td><td>1400</td>
			<td>-</td></tr>	
			<tr><td>Mk 7 Nubian Security Scanner Salvage</td><td>560</td>
			<td>-</td></tr>	
			<tr><td>Mk 10 TaggeCo Holo Lens</td><td>1250</td>
			<td>-</td></tr>	
			<tr><td>Mk 5 Arakyd Droid Caller</td><td>1400</td>
			<td>-</td></tr>	
			<tr><td>Mk 6 Nubian Design Tech</td><td>1400</td>
			<td>-</td></tr>	
			<tr><td>Mk 4 Sienar Holo Projector</td><td>2800</td>
			<td>-</td></tr><tr><th>Good Deal to buy from Shipments</th><th>Store Price</th><th>Farming Price</th></tr>	
			<tr><td>Mk 6 BioTech Implant</td><td>273</td>
			<td>379</td></tr>	
			<tr><td>Mk 2 Zaltin Bacta Gel</td><td>273</td>
			<td>356</td></tr>	
			<tr><td>Mk 3 Chedak Comlink</td><td>545</td>
			<td>713</td></tr>	
			<tr><td>Mk 10 BlasTech Weapon Mod Component</td><td>375</td>
			<td>469</td></tr>	
			<tr><td>Mk 10 BlasTech Weapon Mod Prototype</td><td>750</td>
			<td>938</td></tr>	
			<tr><td>Mk 5 Athakam Medpac Prototype</td><td>750</td>
			<td>938</td></tr>	
			<tr><td>Mk 8 BioTech Implant Component</td><td>375</td>
			<td>469</td></tr>	
			<tr><td>Mk 8 BioTech Implant Prototype</td><td>750</td>
			<td>938</td></tr>	
			<tr><td>MK 8 Neuro-Saav Electrobinoculars Prototype</td><td>750</td>
			<td>938</td></tr>	
			<tr><td>Mk 9 Fabritech Data Pad Prototype</td><td>750</td>
			<td>938</td></tr>	
			<tr><td>Mk 5 A/KT Stun Gun</td><td>1400</td>
			<td>1699</td></tr>	
			<tr><td>Mk 6 Nubian Security Scanner</td><td>545</td>
			<td>623</td></tr>	
			<tr><td>Mk 2 Zaltin Bacta Gel Prototype</td><td>273</td>
			<td>300</td></tr>	
			<tr><td>Mk 3 Chedak Comlink Prototype</td><td>273</td>
			<td>300</td></tr>	
			<tr><td>Mk 4 CEC Fusion Furnace Prototype</td><td>273</td>
			<td>300</td></tr>	
			<tr><td>Mk 5 Chiewab Hypo Syringe Prototype</td><td>273</td>
			<td>300</td></tr>	
			<tr><td>Mk 5 Nubian Design Tech Prototype</td><td>273</td>
			<td>300</td></tr>	
			<tr><td>Mk 6 BioTech Implant Prototype</td><td>273</td>
			<td>300</td></tr>	
			<tr><td>Mk 6 Nubian Security Scanner Prototype</td><td>273</td>
			<td>300</td></tr>	
			<tr><td>Mk 8 BlasTech Weapon Mod Prototype</td><td>273</td>
			<td>300</td></tr><tr><th>Neutral</th><th>Store Price</th><th>Farming Price</th></tr>	
			<tr><td>Mk 3 Sienar Holo Projector</td><td>300</td>
			<td>300</td></tr>	
			<tr><td>Mk 4 SoroSuub Keypad</td><td>300</td>
			<td>300</td></tr>	
			<tr><td>Mk 5 SoroSuub Keypad</td><td>300</td>
			<td>300</td></tr><tr><th>Bad Deal to buy. Farm instead.</th><th>Store Price</th><th>Farming Price</th></tr>	
			<tr><td>Mk 5 Athakam Medpac</td><td>2250</td>
			<td>2063</td></tr>	
			<tr><td>Mk 10 BlasTech Weapon Mod Salvage</td><td>1250</td>
			<td>938</td></tr>	
			<tr><td>Mk 10 Neuro-Saav Electrobinoculars Salvage</td><td>500</td>
			<td>375</td></tr>	
			<tr><td>Mk 10 Neuro-Saav Electrobinoculars Salvage</td><td>1250</td>
			<td>938</td></tr>	
			<tr><td>Mk 4 Carbanti Sensor Array Prototype</td><td>1274</td>
			<td>938</td></tr>	
			<tr><td>Mk 4 Chedak Comlink Prototype</td><td>1274</td>
			<td>938</td></tr>	
			<tr><td>Mk 5 A/KT Stun Gun Prototype</td><td>1274</td>
			<td>938</td></tr>	
			<tr><td>Mk 5 Athakam Medpac Salvage</td><td>1500</td>
			<td>1125</td></tr>	
			<tr><td>Mk 5 Merr-Sonn Thermal Detonator Prototype</td><td>1274</td>
			<td>938</td></tr>	
			<tr><td>Mk 7 Merr-Sonn Shield Generator Salvage</td><td>1250</td>
			<td>938</td></tr>	
			<tr><td>Mk 8 BioTech Implant Salvage</td><td>1500</td>
			<td>1125</td></tr>	
			<tr><td>MK 8 Neuro-Saav Electrobinoculars Salvage</td><td>1250</td>
			<td>938</td></tr>	
			<tr><td>Mk 9 Fabritech Data Pad Salvage</td><td>500</td>
			<td>375</td></tr>	
			<tr><td>Mk 9 Neuro-Saav Electrobinoculars Salvage</td><td>1250</td>
			<td>938</td></tr>	
			<tr><td>Mk 9 Neuro-Saav Electrobinoculars</td><td>1250</td>
			<td>938</td></tr>	
			<tr><td>Mk 3 Czerka Stun Cuffs Salvage</td><td>560</td>
			<td>375</td></tr>	
			<tr><td>Mk 3 Czerka Stun Cuffs</td><td>1400</td>
			<td>938</td></tr>	
			<tr><td>Mk 6 Chiewab Hypo Syringe</td><td>1400</td>
			<td>938</td></tr>	
			<tr><td>Mk 7 Merr-Sonn Shield Generator</td><td>2800</td>
			<td>1838</td></tr>	
			<tr><td>Mk 5 Merr-Sonn Shield Generator</td><td>300</td>
			<td>180</td></tr>	
			<tr><td>Mk 3 Carbanti Sensor Array Salvage</td><td>560</td>
			<td>300</td></tr>	
			<tr><td>Mk 3 Carbanti Sensor Array</td><td>1400</td>
			<td>750</td></tr>	
			<tr><td>Mk 4 A/KT Stun Gun</td><td>1400</td>
			<td>300</td></tr></table>


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