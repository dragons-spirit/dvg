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
				
########################################
# Spielerdaten aus der Datenbank laden #
########################################
			if ($spielerdaten = get_spieler($_SESSION['spieler_id']))
			{
				$spieler_id = $spielerdaten[0]; 
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
			
			
			<?php 
			if($aktionen = get_aktion_spieler($spieler_id))
			{
				$aktion_titel = null;
				$aktion_text = "keine aktuelle Aktion";
				$aktion_start = 0;
				$aktion_ende = 0;
				$aktion_statusbild = null;
				$aktion_any_id_1 = 0;
				
				while($row = $aktionen->fetch_array(MYSQLI_NUM))
				{
					$aktion_titel = $row[0];
					$aktion_text = $row[1];
					$aktion_start = $row[4];
					$aktion_ende = $row[5];
					$aktion_statusbild = $row[6];
					$aktion_any_id_1 = $row[8];
				}
			} else {
				echo "<br />\nKeine Aktionen gefunden.<br />\n";
			} ?>
			
			
			<!-- Hintergrundbild -->
			<div id="mitte" align="center">
				<?php
				# Können Aktionen abgeschlossen werden?
				if(isset($_POST["aktion_abgeschlossen"]))
				{
					# Abschluss Gebietswechsel		
					switch($aktion_text)
					{
						case "Laufen":
							update_aktion_spieler($spieler_id, $aktion_text);
							gebietswechsel($_SESSION['spieler_id'], $aktion_any_id_1);
							zeige_hintergrundbild($aktion_any_id_1);
							break;
							
						case "Fliegen":
							update_aktion_spieler($spieler_id, $aktion_text);
							gebietswechsel($_SESSION['spieler_id'], $aktion_any_id_1);
							zeige_hintergrundbild($aktion_any_id_1);
							break;
							
						case "Gegend erkunden":
							update_aktion_spieler($spieler_id, $aktion_text);
							?>
							<p align="center" style="margin-top:10%; margin-bottom:0px; font-size:16pt;">
								Ihr habt das Gebiet erkundet und folgende Dinge entdeckt:
							</p>
							<table border="1px" border-color="white" style="margin:auto;margin-top:20px;">
							<?php
							if ($npcs_gebiet = get_npcs_gebiet($gebiet_id, "angreifbar"))
							{		
								while($row = $npcs_gebiet->fetch_array(MYSQLI_NUM))
								{
									if(check_wkt($row[3])){
									?>
										<tr align="center">
											<td width="25px"><?php echo $row[0] ?></td>
											<td width="150px"><span title="<?php echo $row[2] ?>"><h3><u><?php echo $row[1] ?></u></h3></span></td>
											<td width="25px"><?php echo $row[3] ?></td>
											<td style="background:url(./../Bilder/jagenbutton.png); background-repeat:no-repeat;"><input type="submit" style="height:100px; width:200px; opacity: 0.0;" alt="jagenbutton" name="button_jagen" value="<?php echo $row[0];?>"></td>
										</tr>
									<?php
									}
								}
							} else {
								echo "<br />\nKeine NPCs gefunden.<br />\n";
							}
							if ($npcs_gebiet = get_npcs_gebiet($gebiet_id, "sammelbar"))
							{		
								while($row = $npcs_gebiet->fetch_array(MYSQLI_NUM))
								{
									if(check_wkt($row[3])){
									?>
										<tr align="center">
											<td	width="25px"><?php echo $row[0] ?></td>
											<td	width="150px"><span title="<?php echo $row[2] ?>"><h3><u><?php echo $row[1] ?></u></h3></span></td>
											<td	width="25px"><?php echo $row[3] ?></td>
											<td style="background:url(./../Bilder/pflanzenbutton.png); background-repeat:no-repeat;"><input type="submit" style="height:100px; width:200px; opacity: 0.0;" alt="pflanzenbutton" name="button_sammeln" value="<?php echo $row[0];?>"></td>
										</tr>
									<?php
									}
								}
							} else {
								echo "<br />\nKeine NPCs gefunden.<br />\n";
							}
							?>
							</table>
							<p align="center" style="margin-top:25px; margin-bottom:0px; font-size:16pt;">
								Zum Erlegen von Tieren oder zum Sammeln von Pflanzen und anderem, klickt auf die Buttons hinter den Dingen.<br>
							</p>
							<p align="center">
								<input type="submit" name="verwerfen" value="gefundene Dinge ignorieren">
							</p>
							<?php
							break;
						
						case "Jagen":
							update_aktion_spieler($spieler_id, $aktion_text);
							if ($npc = get_npc($aktion_any_id_1))
							{		
								while($row = $npc->fetch_array(MYSQLI_NUM))
								{
									#if(check_wkt($row[3])){
									?>
									<table align="center" style="margin-top:75px;" width="500px">
										<tr><td><?php echo $row[0] ?></td></tr>
										<tr><td><?php echo $row[1] ?></td></tr>
										<tr><td><?php echo $row[2] ?></td></tr>
									</table>
									<?php
									#}
								}
							} else {
								echo "<br />\nNPC mit id=[" . $aktion_any_id_1 . "] nicht gefunden.<br />\n";
							}
							?>
							<p align="center" style="margin-top:75px; margin-bottom:0px; font-size:16pt;">
								<?php echo "Ihr habt die Jagd abgeschlossen." ?>
							</p>
							<p align="center">
								<input type="submit" name="weiter" value="weiter">
							</p>
							<?php
							break;
							
						case "Sammeln":
							update_aktion_spieler($spieler_id, $aktion_text);
							if ($npc = get_npc($aktion_any_id_1))
							{		
								while($row = $npc->fetch_array(MYSQLI_NUM))
								{
									#if(check_wkt($row[3])){
									?>
									<table align="center" style="margin-top:75px;" width="500px">
										<tr><td><?php echo $row[0] ?></td></tr>
										<tr><td><?php echo $row[1] ?></td></tr>
										<tr><td><?php echo $row[2] ?></td></tr>
									</table>
									<?php
									#}
								}
							} else {
								echo "<br />\nNPC mit id=[" . $aktion_any_id_1 . "] nicht gefunden.<br />\n";
							}
							?>
							<p align="center" style="margin-top:75px; margin-bottom:0px; font-size:16pt;">
								<?php echo "Ihr habt das Sammeln beendet." ?>
							</p>
							<p align="center">
								<input type="submit" name="weiter" value="weiter">
							</p>
							<?php
							break;
							
						default:
							# Hintergrundbild einblenden, wenn Aktion schon verarbeitet
							zeige_hintergrundbild($gebiet_id);
							break;
					}
				} else {
					# Hintergrundbild einblenden, wenn nichts los ist
					zeige_hintergrundbild($gebiet_id);
				
					# Müssen neue Aktionen gestartet werden?
					if(isset($_POST["gebiet_erkunden"]) OR isset($_POST["button_zum_zielgebiet"]) OR isset($_POST["button_jagen"]) OR isset($_POST["button_sammeln"]))
					{				
						if ($aktion_titel)
						{
						?>
							<p align="center" style="margin-top:20px; margin-bottom:0px; font-size:16pt;">
								Ihr seid noch beschäftigt!<br>
							</p>
							<p align="center">
								<input type="submit" name="zurueck" value="zurück">
							</p>
						<?php
						} else {	
							if(isset($_POST["gebiet_erkunden"])) insert_aktion_spieler($spieler_id, "erkunden_kurz");
							if(isset($_POST["button_zum_zielgebiet"])) insert_aktion_spieler($spieler_id, "laufen", get_gebiet_id($_POST["button_zum_zielgebiet"]));
							if(isset($_POST["button_jagen"])) insert_aktion_spieler($spieler_id, "jagen_normal", $_POST["button_jagen"]);
							if(isset($_POST["button_sammeln"])) insert_aktion_spieler($spieler_id, "sammeln_normal", $_POST["button_sammeln"]);
						}
					}
				}
				?>
			</div>
			
			<!-- Level-/Zahlenzeichen ? -->
			<div id="l7"><?php echo $level7 ?></div>
			<div id="l6"><?php echo $level6 ?></div>
			<div id="l5"><?php echo $level5 ?></div>
			<div id="l4"><?php echo $level4 ?></div>
			<div id="l3"><?php echo $level3 ?></div>
			<div id="l2"><?php echo $level2 ?></div>
			<div id="l1"><?php echo $level1 ?></div>
			
			
			
			<!-- Zielgebiete -->
			<div id="zielgebiete">
				<?php
					if ($zielgebiete = get_gebiet_gebiet($gebiet_id))
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
			<table style="margin-top:20px;"> <!--border-color="white" border="1px"-->
				<tr>
					<td colspan="2"><img align="center" src="../Bilder/<?php bild_zu_spielerlevel($level_id); ?>" height="120px" alt="Spielerbild"/></td>
				</tr>
				<tr>
					<td colspan="2"><p align="center" style="font-size:16pt"><?php echo get_gattung_titel($gattung_id) . " " . $name;?></p></td>
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
				   <td><img src="../Bilder/feuerbutton.png" alt="feuerbutton" width="100%"/></td>
				   <td><img src="../Bilder/flugbutton.png" alt="flugbutton" width="100%"/></td>
				</tr>
				<tr>
				   <td><input type="submit" name="gebiet_erkunden" value="Gebiet erkunden"></td>
				</tr>
			</table>
			
			<!-- Tabelle mit aktuellen Zeiten -->
			<?php
			$aktion_titel = null;
			$aktion_text = "keine aktuelle Aktion";
			$aktion_start = 0;
			$aktion_ende = 0;
			$aktion_statusbild = null;
			$aktion_any_id_1 = 0;
			if($aktionen = get_aktion_spieler($spieler_id))
			{
				while($row = $aktionen->fetch_array(MYSQLI_NUM))
				{
					$aktion_titel = $row[0];
					$aktion_text = $row[1];
					$aktion_start = $row[4];
					$aktion_ende = $row[5];
					$aktion_statusbild = $row[6];
					$aktion_any_id_1 = $row[8];
				}
			} else {
				echo "<br />\nKeine Aktionen gefunden.<br />\n";
			}
			
			?>			
			<table border="1px" border-color="white">
				<tr>
					<td>
						Clientzeit: <b id="clientzeit"></b>
					</td>
				</tr>
				<tr>
					<td>
						<?php
							$asb_breite = "100px";
							$asb_hoehe = "100px";
						?>
						<div id="aktion_status_wartend"><img src="<?php echo get_bild_zu_titel('Drache_wartend'); ?>" width="<?php echo $asb_breite; ?>" height="<?php echo $asb_hoehe; ?>"/></div>
						<div id="aktion_status_kaempfend"><img src="<?php echo get_bild_zu_titel('Drache_kaempfend'); ?>" width="<?php echo $asb_breite; ?>" height="<?php echo $asb_hoehe; ?>"/></div>
						<div id="aktion_status_laufend"><img src="<?php echo get_bild_zu_titel('Drache_laufend'); ?>" width="<?php echo $asb_breite; ?>" height="<?php echo $asb_hoehe; ?>"/></div>
						<p align="center">
							<b id="aktion_text">
								<?php
									echo $aktion_text;
									# if ($aktion_text == "Laufen" OR $aktion_text == "Fliegen") echo " nach \"" . get_gebiet($aktion_any_id_1)[2] . "\"";
								?>
							</b><br>
						</p>
							
					</td>
				</tr>
				<tr>
					<td>
						<div class="laden">
							<div id="balken">
								<div id="prozent"></div>
								<input type='submit' id="button_abschluss" name='aktion_abgeschlossen' value='Aktion abschließen' />
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<input style="display:none;" id="startzeit_temp" value="<?php echo str_replace('-' , '/' , $aktion_start); ?>"/>
						<input style="display:none;" id="endezeit_temp" value="<?php echo str_replace('-' , '/' , $aktion_ende); ?>"/>
						<input style="display:none;" id="titel_temp" value="<?php echo $aktion_titel; ?>"/>
						<input style="display:none;" id="statusbild_temp" value="<?php echo $aktion_statusbild; ?>"/>
						<!--
						Start:  <b id="aktion_startzeit"></b><br>
						Ende:   <b id="aktion_endezeit"></b><br>
						Von:    <b id="aktion_diffVonStart"></b><br>
						Bis:    <b id="aktion_diffBisEnde"></b><br>
						Gesamt: <b id="aktion_gesamtzeit"></b><br>
						-->
					</td>
				</tr>
			</table>
			
			
				<script>
					/*client_times();*/
					window.setInterval("client_times()", 100);
				</script>
			
			
				<a href="javascript:Elemente()" >Elemente</a>
			</div>
			
			<!-- Anzeige für Fähigkeiten -->
			<div id="elemente"><img src="../Bilder/Erdzauber.svg" alt="Elemente" width="200%" /></div>
			
			<div id="zur_spielerauswahl">
				<input type="submit" name="button_zur_spielerauswahl" value="Zurück zur Spielerauswahl">
			</div>
			
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
		</div>
		
		
	</body>
</html>   
    
</form>