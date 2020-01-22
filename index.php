<?php
if (isset($_GET['steamid'])) {
	$steamid = $_GET['steamid'];
} else {
	$steamid = "76561197960287930"; //Gaben
}

  /////////////////////////
 // Configuration Steam //
/////////////////////////
$api_key = 'A REMPLACER PAR TA CLE STEAM API'; //Steam API Key
$api_url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$api_key&steamids=$steamid";
$json = json_decode(file_get_contents($api_url), true);

  ///////////////////////
 // Configuration BDD //
///////////////////////
$bddHost = 'localhost'; //Adresse du serveur MySQL
$bddUser = 'loading-screen'; //Utilisateur
$bddMdp = 'Pa020135*'; //Mot de passe
$bddName = 'loading-screen'; //Nom de la base de donnée
$bdd = new PDO("mysql:host=$bddHost;dbname=$bddName", $bddUser, $bddMdp);
$bdd->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
$bdd->exec("CREATE TABLE `loading-screen`.`players` ( `id` INT NOT NULL AUTO_INCREMENT , `steamid` BIGINT(255) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");

$query="SELECT steamid FROM players WHERE steamid = $steamid";
$query_res =   $bdd->query($query);
$count= count($query_res->fetchAll());
if($count > 0){
	$newPlayer = 0;
} else {
	$newPlayer = 1;
	$insererBdd = $bdd->prepare("INSERT INTO players(id, steamid) VALUES(?, ?)");
    $insererBdd->execute(array(NULL, $steamid));
}
?>
<html>
<head>
	<meta charset="utf-8">
	<title>Loading Screen</title> <!-- Nom de la page -->
	<link rel="stylesheet" href="styles.css">
</head>

<body onLoad="setup();">
	<audio id="audio"  src="sounds/1.ogg" autoplay type="audio/ogg"> </audio>
	<div class="server-name">SL-Projects</div>
	<section class="loading-text-center"><img class="coach-frank" src="images/coach-frank.png" alt="" width="100" height="111">
		<?php 
		if($newPlayer == 1){
			echo "Hey salut le nouveau !";
		} else {
			echo "Coach Frank a dit";
		}
		?>
		<span class="loading__author">
		<?php 
		if($newPlayer == 1){
			echo "Alors comme ça on est nouveau à Riverside? J'y allais quand j'étais petit, mon oncle habitait dans un chalet...<br>
			Tu verras c'est un chouette village, paumé au milieu de nulle part entre les montagnes mais cool quand même! Ah oui au fait, j'ai une astuce à te donner. 
			Si tu veux rejoindre plus rapidement je te conseille de t'abonner à la collection Workshop du serveur. https://bit.ly/39dQj9Q";
		} else {
			echo "C'est pas en restant assis là à rien faire que tu vas être productif! Prépare des plans et fait de la pub pour ton commerce si tu veux que ton chiffre monte.
			<br>Moi en attendant, je t'attend à Port Caverton. Mes associés sauronts t'accueillir sur le tapis rouge si tu leur présentes quelque chose de convainquant!";
		}
		?>
		</span>
	</section>
	<div class="left-bottom">
		<section class="player-logo"><img src=<?=$json["response"]["players"][0]["avatarfull"];?> alt="" width="120" height="120"></section>
		<section class="player-info">Bonjour <?=$json["response"]["players"][0]["personaname"];?></section>
		<section class="loading-text">Chargement... <label id="FileStatus"></label></section>
	</div>
	<section class="topright-text">Carte: <label id="MapName"></label></section>
	<section class="loading-anim-pos">
		<img class="circle-inter" src="images/inter-circle.png" alt="" width="120" height="120">
		<img class="circle-outer" src="images/outer-circle.png" alt="" width="120" height="120">
		<img class="spotlight" src="images/light.png" alt="" width="400" height="400">
	</section>
		<script type="text/javascript">
			function GameDetails( servername, serverurl, mapname, maxplayers, steamid, gamemode ) {
				document.getElementById("MapName").innerHTML = mapname;
			}
			function SetStatusChanged( status ) { 
				document.getElementById("FileStatus").innerHTML = status;
			}

			var i=1;
			var nextSong= "";
			function setup() {
			    document.getElementById('audio').addEventListener('ended', function(){
			        i++;
			        nextSong = "sounds/"+i+".ogg"; //  d'où le fait d'avoir nommé les musiques 1, 2, 3...
			        audioPlayer = document.getElementById('audio');
			        audioPlayer.src = nextSong;
			        audioPLayer.load();
			        audioPlayer.play();
			        if(i == 5) // Normalement tu n'atteinds jamais les 5 musiques xD
			        {
			            i = 1;
			        }
			         
			        }, false);
			}
		</script>
</body>
</html>