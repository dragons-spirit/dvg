<!--DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"-->



<html>
	
	<head>
		<meta http-equiv="Content-Language" content="de">
		<meta http-equiv="Content-Script-Type" content="text/javascript">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<meta http-equiv="last-modified" content="18.12.2016 19:00:00" >
		<meta name="description" content="Drachen von Gaja - Browsergame">
		<meta name="keywords" content="Drachen, Elemente, Browsergame, Browserspiel,">
		<meta name="Author" content="Tina Schmidtbauer, Hendrik Matthes" >
		<meta charset="utf-8">
	
		<link rel="stylesheet" type="text/css" href="../index.css">
		<script src="index.js" type="text/javascript"></script>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<title>Drachen von Gaja</title>
		
		
		
	</head>
	
	<body>
	<form id="drachenvongaja" method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<?php
			session_start();
			
			include("navi.inc.php");
			include("db_funktionen.php");
			
			global $debug;
			
# Ist der Spieler auch eingeloggt?
# "nein"	-> zurück zur Anmeldung
# "ja" 		-> Charakterdaten laden/setzen
			if (!isset($_SESSION['login_name']) OR isset($_POST["button_zur_spielerauswahl"]))
			{
		?>
				<!-- Zurück zur Anmeldung -->
				<script type="text/javascript">
					window.location.href = "../index.php"
				</script>
		<?php
			}
			else
			{
				if ($debug)
				{
					echo "Los geht's ...<br/>";
					echo "Der Spieler [spieler_id=" . $_SESSION['spieler_id'] . "] ist über Account [" . $_SESSION['login_name'] . "] eingeloggt. <br/>";
				}
			}
			
			
# Gebietswechsel angeklickt?			
			if (isset($_POST["button_zum_zielgebiet"]))
			{
				gebietswechsel($_SESSION['spieler_id'], get_gebiet_id($_POST["button_zum_zielgebiet"]));
			}
			else
			{
				if ($debug)
				{
					echo "Los geht's ...<br/>";
					echo "Der Spieler [spieler_id=" . $_SESSION['spieler_id'] . "] ist über Account [" . $_SESSION['login_name'] . "] eingeloggt. <br/>";
				}
			}
			
########################################
# Spielerdaten aus der Datenbank laden #
########################################
			if ($spielerdaten = get_spieler($_SESSION['spieler_id']))
			{
				$id = $spielerdaten[0]; 
				$account_id = $spielerdaten[1]; 
				$bilder_id = $spielerdaten[2]; 
				$gattung_id = $spielerdaten[3]; 
				$level_id = $spielerdaten[4]; 
				$gebiet_id = $spielerdaten[5]; 
				$name = $spielerdaten[6]; 
				$geschlecht = $spielerdaten[7]; 
				$staerke = $spielerdaten[8]; 
				$intelligenz = $spielerdaten[9]; 
				$magie = $spielerdaten[10];
				$element_feuer = $spielerdaten[11];
				$element_wasser = $spielerdaten[12];
				$element_erde = $spielerdaten[13];
				$element_luft = $spielerdaten[14];
				$gesundheit = $spielerdaten[15];
				$max_gesundheit = $spielerdaten[16];
				$energie = $spielerdaten[17];
				$max_energie = $spielerdaten[18];
				$balance = $spielerdaten[19];
			}
			else
			{
				echo "Keine Spielerdaten gefunden.<br/>";
			}
			

######################################################################################
# Weitere fiktive Spielerdaten setzen || sollten später mit aus der Datenbank kommen #
######################################################################################
			$speipunkte = 5;
			$flugpunkte = 5;
			
			$level7 = 7;
			$level6 = 6;
			$level5 = 5;
			$level4 = 4;
			$level3 = 3;
			$level2 = 2;
			$level1 = 1;
			
			$erd1 = 1; $wasser1 = 1; $feuer1 = 1; $luft1 = 1;
			$erd2 = 2; $wasser2 = 2; $feuer2 = 2; $luft2 = 2;
			$erd3 = 3; $wasser3 = 3; $feuer3 = 3; $luft3 = 3;
			$erd4 = 4; $wasser4 = 4; $feuer4 = 4; $luft4 = 4;
			$erd5 = 5; $wasser5 = 5; $feuer5 = 5; $luft5 = 5;

		?>
		
<!--
################
# Seitenaufbau #
################
-->

		<div id="rahmen">
			<div id="zur_spielerauswahl">
				<input type="submit" name="button_zur_spielerauswahl" value="Zurück zur Spielerauswahl">
			</div>
			
			<!-- Obere Leiste -->
			<div id="obere_Leiste">
				<table align="center">
					<tr>
						<td>Stärke <?php echo $staerke ?></td>
						<td>Intelligenz <?php echo $intelligenz ?></td>
						<td>Magie <?php echo $magie ?></td>
					</tr>
				</table>
			</div>
			
			<!-- Hintergrundbild -->
			<div id="mitte" >
				<p align="center" style="margin-top:75px; margin-bottom:0px; font-size:20pt;">
					<img src="<?php echo get_bild_zu_gebiet($gebiet_id) ?>" width="60%" height="60%" alt=""/><br>
					<?php echo get_gebiet($gebiet_id)[3]; ?>
				</p> 
			</div>
			
			<!-- Level-/Zahlenzeichen ? -->
			<div id="l7"><?php echo $level7 ?></div>
			<div id="l6"><?php echo $level6 ?></div>
			<div id="l5"><?php echo $level5 ?></div>
			<div id="l4"><?php echo $level4 ?></div>
			<div id="l3"><?php echo $level3 ?></div>
			<div id="l2"><?php echo $level2 ?></div>
			<div id="l1"><?php echo $level1 ?></div>
			
			<!-- Untere Leiste -->
			<div id="untere_Leiste">
				<table align="center">
					<tr>
						<td>Feuer Speien <?php echo $speipunkte ?></td>
						<td>Karte</td>
						<td>Fliegen <?php echo $flugpunkte ?></td>
					</tr>
				</table>
			</div>
			
			<!-- Zielgebiete -->
			<div id="zielgebiete">
				<?php
					if ($zielgebiete = get_gebiet_zu_gebiet($gebiet_id))
					{		
						while($row = $zielgebiete->fetch_array(MYSQLI_NUM))
						{
				?>			
						<input style="height: 25px; width: 80px;"  type="submit" name="button_zum_zielgebiet" value="<?php echo $row[3]; ?>"/><br>
				<?php
						}
					}
					else{
						echo "<br />\nKeine Zielgebiete gefunden.<br />\n";
					}
				?>
			</div>
			
			<!-- Anzeige für Spielerdaten -->
			<div id="charakter">
			<table> <!--border-color="white" border="1px"-->
				<tr>
					<td colspan="2"><img align="center" src="../Bilder/<?php bild_zu_spielerlevel($level_id); ?>" height="150px" alt="Spielerbild"/></td>
				</tr>
				<tr>
					<td colspan="2"><p align="center" style="font-size:20pt"><?php echo get_gattung_titel($gattung_id) . " " . $name;?></p></td>
				</tr>
				<tr>
					<td><p align="left">Geschlecht</p></td>
					<td><p align="left">
					<?php 
						switch ($geschlecht){
						case "W":
							echo "weiblich";
							break;
						default:
							echo "männlich";
							break;
						}
					?>
				</tr>
				<tr>
					<td><p align="left">Element Feuer</p></td>
					<td><p align="left"><?php echo $element_feuer;?></p></td>
				</tr>
				<tr>
					<td><p align="left">Element Erde</p></td>
					<td><p align="left"><?php echo $element_erde;?></p></td>
				</tr>
				<tr>
					<td><p align="left">Element Wasser</p></td>
					<td><p align="left"><?php echo $element_wasser;?></p></td>
				</tr>
				<tr>
					<td><p align="left">Element Luft</p></td>
					<td><p align="left"><?php echo $element_luft;?></p></td>
				</tr>
				<tr>
					<td><p align="left">Gesundheit</p></td>
					<td><p align="left"><?php echo $gesundheit . "/" . $max_gesundheit;?></p></td>
				</tr>
				<tr>
					<td><p align="left">Energie</p></td>
					<td><p align="left"><?php echo $energie . "/" . $max_energie;?></p></td>
				</tr>
				<tr>
					<td><p align="left">Balance</p></td>
					<td><p align="left"><?php echo $balance;?></p></td>
				</tr>
				<tr>
				   <td><img src="../Bilder/pflanzenbutton.png" alt="pflanzenbutton" width="100%"/></td>
				   <td><img src="../Bilder/jagenbutton.png" alt="jagenbutton" width="100%"/></td>
				</tr>
				<tr>
				   <td><img src="../Bilder/feuerbutton.png" alt="feuerbutton" width="100%"/></td>
				   <td><img src="../Bilder/flugbutton.png" alt="flugbutton" width="100%"/></td>
				</tr>
			</table>
		
		<a href="javascript:Elemente()" >Elemente</a>
		</div>
		
        
		</div>
		<div id="elemente">
           <img src="../Bilder/Erdzauber.svg" alt="Elemente" width="100%" />
        </div>
		
		<script type="text/javascript">
		function Elemente()
		{
			var e = document.getElementById('elemente');
			switch(e.style.display)
			{
			case 'none':
				e.style.display='block';
				break;
			case 'block':
				e.style.display='none';
				break;
			default:
				e.style.display='block';
			}
		}
		</script>
		
	</body>
</html>   
    
</form>