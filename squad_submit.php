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

$uploaded = false;
if(!empty($_POST['fileid']) && !empty($_POST['raid'])) {
	$uploaded = true;
	$id = $db->str($_POST['fileid']);
	$raid = $db->str($_POST['raid']);
	$phase = empty($_POST['phase']) ? 0 : makeInteger(trim($_POST['phase']));
	$score = empty($_POST['score']) ? 0 : makeInteger(trim($_POST['score']));

	$name = empty($_POST['name']) ? '' : $db->str($_POST['name']);
	$gg = empty($_POST['gg']) ? '' : $db->str($_POST['gg']);
	$team1 = empty($_POST['team1']) ? '' : $db->str($_POST['team1']);
	$team2 = empty($_POST['team2']) ? '' : $db->str($_POST['team2']);
	$team3 = empty($_POST['team3']) ? '' : $db->str($_POST['team3']);
	$team4 = empty($_POST['team4']) ? '' : $db->str($_POST['team4']);
	$team5 = empty($_POST['team5']) ? '' : $db->str($_POST['team5']);
	$zeta1 = empty($_POST['zeta1']) ? 0 : 1;
	$zeta2 = empty($_POST['zeta2']) ? 0 : 1;
	$zeta3 = empty($_POST['zeta3']) ? 0 : 1;
	$zeta4 = empty($_POST['zeta4']) ? 0 : 1;
	$zeta5 = empty($_POST['zeta5']) ? 0 : 1;
	
	$db->query("INSERT INTO swgoh_screenshot(id,stamp,raid,phase,score,name,gg,toon1,toon2,toon3,toon4,toon5,zeta1,zeta2,zeta3,zeta4,zeta5) VALUES('".$id."',".time().",'".$raid."',".$phase.",".$score.",'".$name."','".$gg."','".$team1."','".$team2."','".$team3."','".$team4."','".$team5."',".$zeta1.",".$zeta2.",".$zeta3.",".$zeta4.",".$zeta5.")");
}
?>
<html class="js">
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
	#photo {
		max-width: 100px;
		max-height: 400px;
		transition: all .5s linear;
	}
	#photo.uploaded {
		max-width: 100%;
	}
	#details {
		display: none;
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
  	<script src="https://use.fontawesome.com/c278e2b3ff.js"></script>
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<script src="https://sdk.amazonaws.com/js/aws-sdk-2.77.0.min.js"></script>
  	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
  	<script src="upload.js"></script>
  	<script type="text/javascript">
  		var bucketName = 'lambdaexperiment';
  		var s3;

  		<? if(empty($uploaded)) { ?>
  		$(function(){
  			setupAWS();
  			setupFileUpload();
  		});
		<? } ?>

		function setupAWS() {
			//http://docs.aws.amazon.com/AWSJavaScriptSDK/latest/AWS/Lambda.html#invoke-property
			AWS.config.region = 'us-east-1';
			AWS.config.credentials = new AWS.CognitoIdentityCredentials({
				IdentityPoolId: 'us-east-1:b633bb3d-72f6-428d-830a-14e8783a35ae'
			});
			
			s3 = new AWS.S3({
				apiVersion: '2006-03-01',
				params: {Bucket: bucketName}
			});
		}

		function uploadPic(files) {
			var file = files[0];
			var id = new Date().getTime()+"_"+Math.round(Math.random()*10000000);
			var ext = file.name.split('.').pop();
			var fileName = id+"."+ext;

			if(file.type != "image/jpeg" && file.type != "image/png" && file.type != "image/jpg"){
				uploadedPhotoError({message:"Unsupported file type. Please upload a jpg or png image"});
			} else {
				$('#fileid').val(fileName);
				s3.upload({
					Key: fileName,
					Body: file,
					ACL: 'public-read'
				}, function(err, data) {
					if(err) {
						uploadedPhotoError(err);
					} else {
						uploadedPhotoSuccess(data);
					}
				});
			}
		}
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
	<h1><a href="http://www.swgoh.life/index.html">More Tools</a> &gt; Squad High Scores</h1>

	<p>Please share your raid high score screenshots from the SWGOH Raids Here so that everyone can learn how to do better, and for bragging rights!</p>

	<? if(empty($uploaded)) { ?>
	<h2>Step 1: Upload a screenshot of your Raid damage.</h2>

	<div id="uploadForm" class="box">			
		<img src="haat/p4_offsky.png" id="photo" />
		<div class="box__input">
			<svg class="box__icon" xmlns="http://www.w3.org/2000/svg" width="50" height="43" viewBox="0 0 50 43"><path d="M48.4 26.5c-.9 0-1.7.7-1.7 1.7v11.6h-43.3v-11.6c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v13.2c0 .9.7 1.7 1.7 1.7h46.7c.9 0 1.7-.7 1.7-1.7v-13.2c0-1-.7-1.7-1.7-1.7zm-24.5 6.1c.3.3.8.5 1.2.5.4 0 .9-.2 1.2-.5l10-11.6c.7-.7.7-1.7 0-2.4s-1.7-.7-2.4 0l-7.1 8.3v-25.3c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v25.3l-7.1-8.3c-.7-.7-1.7-.7-2.4 0s-.7 1.7 0 2.4l10 11.6z"/></svg>
			<input type="file" name="files[]" id="file" class="box__file" data-multiple-caption="{count} files selected" multiple />
			<label for="file"><strong>Choose a file</strong><span class="box__dragndrop"> or drag it here</span>.</label>
		</div>

		<div class="box__uploading">Uploading<br /><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i></div>
		<div class="box__success">Done!</div>
		<div class="box__error">Error! <span></span>. </div>
	</div>

	<form action="squad_submit.php" method="post" id="details">
		<input type="hidden" name="fileid" id="fileid" value="" />
		<h2>Step 2: Which RAID is your screenshot from?</h2>
		<select name="raid">
			<option value="" selected="selected">Please Select</option>
			<option value="rancorh">The Pit (Rancor) HEROIC</option>
			<option value="aath">Tank Takedown (HAAT) HEROIC</option>
		</select><br />
		Starting Phase: <input type="text" name="phase" size="2" /><br />

		<h2>Step 3: Please enter some information about this screenshot:</h2>
		Your Name: <input type="text" name="name" /><br />
		Your SWGOH.GG ID: https://swgoh.gg/u/<input type="text" name="gg" />/ (optional)<br />

		Score: <input type="text" name="score" /><br />
		<br />
		Please use short names ("Palp" instead of "Emperor Palpatine") when possible.<br />
		Leader: <input type="text" name="team1" /> <input type="checkbox" name="zeta1" value="1" />Has Zeta?<br />
		Toon 1: <input type="text" name="team2" /> <input type="checkbox" name="zeta2" value="1" />Has Zeta?<br />
		Toon 2: <input type="text" name="team3" /> <input type="checkbox" name="zeta3" value="1" />Has Zeta?<br />
		Toon 3: <input type="text" name="team4" /> <input type="checkbox" name="zeta4" value="1" />Has Zeta?<br />
		Toon 4: <input type="text" name="team5" /> <input type="checkbox" name="zeta5" value="1" />Has Zeta?<br />

		<br />
		<input type="submit" class="btn" value="Upload Screenshot" />
	</div>
	<? } else { ?>

		<br /><br />
		<b>Your score was uploaded!</b> 
		<p>Please wait a day for it to be verified and added. Thanks!</p>
		<a href="squad_submit.php">Upload Another</a> or <a href="squads.php">View Scores</a>
		<br /><br /><br /><br />
	<? } ?>



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