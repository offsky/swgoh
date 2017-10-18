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
?>
<html>
<head>
	<title>SWGOH Tools - Squad High Scores</title>
	<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="upload.css" media="screen" />
	<style>
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
	#popImage {
		display: none;
		position: absolute;
		z-index: 1000;
		max-width: 90%;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		border: 3px solid black;
		border-radius: 10px;
		min-width: 200px;
		min-height: 200px;
	}
	#screen {
		display: none;
		position: absolute;
		z-index: 999;
		top:0;
		left:0;
		right:0;
		bottom:0;
		background-color: #fff;
		opacity: 0.8;
	}
	@media(min-width: 520px) { #top { padding-bottom:0px} #ad { height:90px;} .swgoh_ad { left:inherit; top:0; right:0; width: 328px; height: 90px; } }
	@media(min-width: 720px) { #top { padding-bottom:0px} #ad { height:90px;} .swgoh_ad { left:inherit; top:0; right:0; width: 528px; height: 90px; } }
	@media(min-width: 920px) { #top { padding-bottom:0px} #ad { height:90px;} .swgoh_ad { left:inherit; top:0; right:0; width: 728px; height: 90px; } }
	@media(min-width: 1200px) { #top { padding-bottom:0px} #ad { height:90px;} .swgoh_ad { left:inherit; top:0; right:0; width: 1000px; height: 90px; } }

	</style>
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
  	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
   <script type="text/javascript">
   	$(function(){
  			$('.viewImage').on("click",function(e) {
  				var path = $(e.target).data("src");
  				$("#popImage").attr('src',path).show();
  				$("#screen").show();
  			});
  			$('#screen').on("click",function(e) {
  				$("#popImage").hide();
  				$("#screen").hide();
  			});
  			$('#popImage').on("click",function(e) {
  				$("#popImage").hide();
  				$("#screen").hide();
  			});
  		});
	</script>
</head>
<body>
	<div id="top">
		<a href="index.html" id="logoClick"></a>
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
	<h1><a href="index.html">More Tools</a> &gt; Squad High Scores</h1>

	<p>Please share your raid high score screenshots from the SWGOH Raids Here so that everyone can learn how to do better, and for bragging rights!</p>
	<br />
	<a href="squad_submit.php" class="btn">Submit Your Score</a>
	<br /><br />
	
	<?
		function translateName($short) {
			if($short=="rancor") return "The Pit (Rancor)";
			else if($short=="rancorh") return "The Pit (Rancor) HEROIC";
			else if($short=="aat") return "Tank Takedown (AAT)";
			else if($short=="aath") return "Tank Takedown (HAAT) HEROIC";
		}

		$raid = "";
		$phase = 0;
		echo "<table>";
		$rs = $db->query("SELECT * FROM swgoh_screenshot WHERE verified=0 order by raid asc,score desc");
		while($row = $db->getNext($rs,1)) {
			if($raid !== $row['raid']) {
				$phase = $row['phase'];
				$raid = $row['raid'];
				echo "</table>";
				echo "<h2>".translateName($raid)." Phase ".$phase."</h2>";
				echo "<table><tr><th>Score</th><th>Player</th><th>Squad</th><th>View</th></tr>";
			} else if($phase !== $row['phase']) {
				$phase = $row['phase'];
				echo "</table>";
				echo "<h2>".translateName($raid)." Phase ".$phase."</h2>";
				echo "<table><tr><th>Score</th><th>Player</th><th>Squad</th><th>View</th></tr>";
			}
			echo "<tr><td>".number_format($row['score'])."</td>";
			if(empty($row['gg'])) echo "<td>".$row['name']."</td>";
			else echo "<td><a href='https://swgoh.gg/u/".$row['gg']."/' target='_blank'>".$row['name']."</a></td>";

			echo "<td>";
				if($row['zeta1']) echo "z";
				echo $row['toon1'];
				echo "(L), ";

				if($row['zeta2']) echo "z";
				echo $row['toon2'];
				echo ", ";
				
				if($row['zeta3']) echo "z";
				echo $row['toon3'];
				echo ", ";
				
				if($row['zeta4']) echo "z";
				echo $row['toon4'];
				echo ", ";
				
				if($row['zeta5']) echo "z";
				echo $row['toon5'];
			
			echo "</td>";
			
			echo "<td><a href='#' class='viewImage' data-src='https://lambdaexperiment.s3.amazonaws.com/".$row['id']."'>View Image</a></td></tr>";
		}
		echo "</table>";
	?>

	<img src="" id="popImage" />
	<div id="screen"></div>

	<br /><br /><br /><ins class="adsbygoogle"
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