<!--DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"-->

<?php
	session_start();
	session_cache_limiter('private');
	session_cache_expire(0);
	include("connect.inc.php");
	$connect_db_dvg = open_connection($default_user, $default_pswd, $default_host, $default_db);
?>

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
		<script src="../index.js" type="text/javascript"></script>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<title>Drachen von Gaja</title>
		<?php
		if($_SESSION['browser'] == "Opera"){
			?>
			<style>
				head 				{font-family:Lucida Calligraphy,Georgia,fantasy,EG Dragon Caps; font-size:smaller;}
				body 				{font-family:Lucida Calligraphy,Georgia,fantasy,EG Dragon Caps; font-size:smaller;}
				input                           {outline:none;}
                                input[type=submit] 	{font-family:Lucida Calligraphy,Georgia,fantasy,EG Dragon Caps; font-size:smaller;}
				input[type=button] 	{font-family:Lucida Calligraphy,Georgia,fantasy,EG Dragon Caps; font-size:smaller;}
			</style>
			<?php
		}
		?>
		
	</head>
	
	<body style="background-color:black;">
	<form id="drachenvongaja" method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<?php
		include("klassen.php");
		include("db_funktionen.php");
		
		global $debug;
		
		#Aktivieren/Deaktivieren von Anzeigen
		$levelbilder_anzeigen = false;
			
# Ist der Spieler auch eingeloggt?
# "nein"	-> zurück zur Anmeldung
# "ja" 		-> Charakterdaten laden/setzen
		if (!isset($_SESSION['login_name']) OR isset($_POST["button_zur_spielerauswahl"])){
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
		if ($spielerdaten = get_spieler($_SESSION['spieler_id'])){
			$spieler = new Spieler($spielerdaten);
		} else {
			echo "Keine Spielerdaten gefunden.<br/>";
		}
		?>
		
<!--
################
# Seitenaufbau #
################
-->

		<div id="rahmen">
			<!-- Initialisierung von Aktionen (Aktueller Status des Spielers) -->
			<?php 
			if($aktionen = get_aktion_spieler($spieler->id)){
				$aktion_spieler = new AktionSpieler();
				while($row = $aktionen->fetch_array(MYSQLI_NUM)){
					$aktion_spieler->set($row);
				}
			} else {
				echo "<br />\nKeine Aktionen gefunden.<br />\n";
			}
			
			if ($spieler->level_id == 1) $erfahrung_benoetigt_davor = 0;
				else $erfahrung_benoetigt_davor = get_erfahrung_naechster_level($spieler->level_id - 1);
			$erfahrung_benoetigt_aktuell = get_erfahrung_naechster_level($spieler->level_id);
			if 	($erfahrung_benoetigt_aktuell == 0) $erfahrung_benoetigt_aktuell = $spieler->erfahrung;
			?>
			
			<!-- Obere Leiste -->
			<div id="obere_Leiste">
				<table align="center">
					<tr>
						<td>Stärke <?php echo "&#160;".floor_x($spieler->staerke, 0); ?></td>
						<td style="padding-left:30pt;">Intelligenz <?php echo "&#160;".floor_x($spieler->intelligenz, 0); ?></td>
						<td style="padding-left:30pt;">Magie <?php echo "&#160;".floor_x($spieler->magie, 0); ?></td>
					</tr>
				</table>
			</div>
			
			<!-- Hauptseite -->
			<div id="mitte">
				<div id="mitte_zentral">
					<!-- Hauptanzeigefenster Mitte -->
					<div id="hauptfenster" align="center">
					<?php
					##########################
					# Abschluss von Aktionen #
					##########################
						if(isset($_POST["aktion_abgeschlossen"])){
							# Abschluss Gebietswechsel		
							switch($aktion_spieler->text){
								#################################################################
								case "Laufen":
									update_aktion_spieler($spieler->id, $aktion_spieler->titel);
									gebietswechsel($_SESSION['spieler_id'], $aktion_spieler->any_id_1);
									zeige_hintergrundbild($aktion_spieler->any_id_1);
									$spieler->gebiet_id = $aktion_spieler->any_id_1;
									break;
								
								#################################################################
								case "Fliegen":
									update_aktion_spieler($spieler->id, $aktion_spieler->titel);
									gebietswechsel($_SESSION['spieler_id'], $aktion_spieler->any_id_1);
									zeige_hintergrundbild($aktion_spieler->any_id_1);
									$spieler->gebiet_id = $aktion_spieler->any_id_1;
									break;
								
								#################################################################
								case "Gegend erkunden":
									update_aktion_spieler($spieler->id, $aktion_spieler->titel);
									?>
									<p align="center" style="margin-top:10%; margin-bottom:0px; font-size:14pt;">
										Ihr habt das Gebiet erkundet und folgende Dinge entdeckt:
									</p>
									<table border="1px" border-color="white" style="margin:auto;margin-top:20px;">
									<?php
									if ($npcs_gebiet = get_npcs_gebiet($spieler->gebiet_id, "angreifbar")){		
										while($row = $npcs_gebiet->fetch_array(MYSQLI_NUM)){
											if(check_wkt($row[3])){
											?>
												<tr align="center">
													<!--<td width="25px"><?php echo $row[0] ?></td>-->
													<td width="85px"><img src="<?php echo get_bild_zu_id($row[4]) ?>" style="max-height:100px; max-width:200px;" alt=""/></td>
													<td width="150px"><span title="<?php echo $row[2] ?>"><h3><u><?php echo $row[1] ?></u></h3></span></td>
													<!--<td width="25px"><?php echo $row[3] ?></td>-->
													<td style="background:url(./../Bilder/jagenbutton.png); background-repeat:no-repeat;"><input type="submit" style="height:100px; width:200px; opacity: 0.0;" alt="jagenbutton" name="button_jagen" value="<?php echo $row[0];?>"></td>
												</tr>
											<?php
											}
										}
									} else {
										echo "<br />\nKeine NPCs gefunden.<br />\n";
									}
									if ($npcs_gebiet = get_npcs_gebiet($spieler->gebiet_id, "sammelbar")){		
										while($row = $npcs_gebiet->fetch_array(MYSQLI_NUM)){
											if(check_wkt($row[3])){
												?>
												<tr align="center">
													<!--<td	width="25px"><?php echo $row[0] ?></td>-->
													<td width="85px"><img src="<?php echo get_bild_zu_id($row[4]) ?>" style="max-height:100px; max-width:200px;" alt=""/></td>
													<td	width="150px"><span title="<?php echo $row[2] ?>"><h3><u><?php echo $row[1] ?></u></h3></span></td>
													<!--<td	width="25px"><?php echo $row[3] ?></td>-->
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
									<p align="center" style="margin-top:25px; margin-bottom:0px; font-size:14pt;">
										Zum Erlegen von Tieren oder zum Sammeln von Pflanzen und anderem, klickt auf die Buttons hinter den Dingen.<br>
									</p>
									<p align="center">
										<input type="submit" name="verwerfen" value="gefundene Dinge ignorieren">
									</p>
									<?php
									break;
								
								#################################################################
								case "Jagen":
									update_aktion_spieler($spieler->id, $aktion_spieler->titel);
									$npc_id = $aktion_spieler->any_id_1;
									$npc = get_npc($npc_id);
									?>
									<p align="center" style="margin-top:10%; margin-bottom:0px; font-size:14pt;">
										<?php echo "Ihr habt das NPC " . $npc->name . " gestellt und macht euch für den Kampf bereit."; ?>
									</p>
									<table style="margin:auto;margin-top:20px;">
										<tr align="center">
											<td style="background:url(./../Bilder/drachenkampf.png); background-repeat:no-repeat;">
												<input type="submit" style="height:100px; width:200px; opacity: 0.0;" alt="kampfbutton" name="button_kampf" value="<?php echo $npc_id;?>">
											</td>
										</tr>
									</table>
									<?php
									$kampf_id = insert_kampf($spieler->gebiet_id);
									insert_aktion_spieler($spieler->id, "kampf", $kampf_id);
									insert_kampf_teilnehmer($kampf_id, $spieler->id, "spieler", 0);
									#insert_kampf_teilnehmer($kampf_id, 26, "spieler", 0); # Rashiel
									#insert_kampf_teilnehmer($kampf_id, 45, "spieler", 0); # Flammi
									insert_kampf_teilnehmer($kampf_id, $npc_id, "npc", 1);
									#insert_kampf_teilnehmer($kampf_id, 2, "npc", 1); # Ratte
									if ($helferlein) insert_kampf_teilnehmer($kampf_id, 45, "npc", 0); # Helferlein im Kampf
									break;
									
								
								#################################################################
								case "Sammeln":
									update_aktion_spieler($spieler->id, $aktion_spieler->titel);
									$npc_id = $aktion_spieler->any_id_1;
									add_npc_spieler_statistik($spieler->id, $npc_id);
									zeige_erbeutete_items($spieler, $npc_id, "Pflanzen");
									?>
									<p align="center" style="padding-top:10pt;">
										<input type="submit" name="weiter" value="weiter">
									</p>
									<?php
									$spieler->neuberechnung();
									break;
								
								#################################################################
								case "Kampf":
									if ($_POST["aktion_abgeschlossen"] == "Kampf beenden"){
										$kampf_id = $aktion_spieler->any_id_1;
										update_aktion_spieler($spieler->id, $aktion_spieler->titel);
										$kt_0 = get_all_kampf_teilnehmer($kampf_id, 0);
										$kt_1 = get_all_kampf_teilnehmer($kampf_id, 1);
										foreach ($kt_0 as $kt){
											if ($kt->id == $spieler->id AND $kt->typ == "spieler"){
												$spieler->uebernehme_kt_werte($kt);
											}
										}
										$gewinner_seite = ist_kampf_beendet(array_merge($kt_0, $kt_1));
										if ($gewinner_seite == 0){
											$npc_ids = get_all_npcs_kampf($kampf_id);
											add_npc_spieler_statistik($spieler->id, $npc_ids);
											zeige_erbeutete_items($spieler, $npc_ids, "Tiere");
										} else {
										?>
											<p align="center" style="margin-top:5%; margin-bottom:0px; font-size:14pt;">
												Ihr wurdet besiegt!
											</p>
										<?php
										}
										?>
										<p align="center" style="padding-top:10pt;">
											<input type="submit" name="weiter" value="weiter">
										</p>
										<?php
										$spieler->neuberechnung();
									} else {
										# Backup falls Kampf nicht sauber beendet wurde // F5-Bug nach Aktion "Jagen"
										zeige_hintergrundbild($spieler->gebiet_id);
									}
									break;
									
								#################################################################
								case "Ausruhen":
									update_aktion_spieler($spieler->id, $aktion_spieler->titel);
									$spieler->erholung_prozent(100, 100, 100);
									?>
									<p align="center" style="padding-top:10pt;">
										Langsam schlagt ihr die Augen auf und seid bereit für neue Taten.
									</p>
									<p align="center" style="padding-top:10pt;">
										<input type="submit" name="weiter" value="weiter">
									</p>
									<?php
									break;
								
								#################################################################
								default:
									# Hintergrundbild einblenden, wenn Aktion schon verarbeitet
									zeige_hintergrundbild($spieler->gebiet_id);
									break;
							}
						} else {
							# Elementebuttons auswerten Parameter zur Anzeige übergeben
							$elementebutton = 0;
							if(isset($_POST["button_erde"]) OR (isset($_POST["anzeige_element"])) AND $_POST["anzeige_element"] == "Erde"){
								elemente_anzeigen("Erde","3B170B", $spieler);
								$elementebutton = true;
							}
							if(isset($_POST["button_wasser"]) OR (isset($_POST["anzeige_element"])) AND $_POST["anzeige_element"] == "Wasser"){
								elemente_anzeigen("Wasser","0B2161", $spieler);
								$elementebutton = true;
							}
							if(isset($_POST["button_feuer"]) OR (isset($_POST["anzeige_element"])) AND $_POST["anzeige_element"] == "Feuer"){
								elemente_anzeigen("Feuer","3B0B0B", $spieler);
								$elementebutton = true;
							}
							if(isset($_POST["button_luft"]) OR (isset($_POST["anzeige_element"])) AND $_POST["anzeige_element"] == "Luft"){
								elemente_anzeigen("Luft","088A85", $spieler);
								$elementebutton = true;
							}
							if(isset($_POST["button_kampf_standard"]) OR (isset($_POST["anzeige_element"])) AND $_POST["anzeige_element"] == "---ohne---"){
								elemente_anzeigen("---ohne---","556B2F", $spieler);
								$elementebutton = true;
							}
							$aktion_starten = (isset($_POST["button_gebiet_erkunden"]) OR isset($_POST["button_zum_zielgebiet"]) OR isset($_POST["button_jagen"]) OR isset($_POST["button_sammeln"]) OR isset($_POST["button_ausruhen"]));
							$dinge_anzeigen = (isset($_POST["button_inventar"]) OR $elementebutton > 0 OR isset($_POST["button_tagebuch"]) OR isset($_POST["button_drachenkampf"]) OR isset($_POST["button_handwerk"]) OR isset($_POST["button_kampf"]) OR (isset($_POST["kt_id_value"]) AND $_POST["kt_id_value"] > 0) OR isset($_POST["button_statistik"]) OR isset($_POST["button_charakterdaten"]));
							
							######################
							# Start von Aktionen #
							######################
							if($aktion_starten){				
								# Hintergrundbild einblenden, wenn neue Aktion gestartet werden soll
								# + Hinweis, falls noch eine Aktion aktiv ist (siehe zeige_hintergrundbild())
								zeige_hintergrundbild($spieler->gebiet_id, $aktion_spieler->titel);
								if (!$aktion_spieler->titel){	
									if(isset($_POST["button_gebiet_erkunden"])) beginne_aktion($spieler, "erkunden_kurz");
									if(isset($_POST["button_zum_zielgebiet"])) beginne_aktion($spieler, "laufen", get_gebiet_id($_POST["button_zum_zielgebiet"]));
									if(isset($_POST["button_jagen"])) beginne_aktion($spieler, "jagen_normal", $_POST["button_jagen"]);
									if(isset($_POST["button_sammeln"])) beginne_aktion($spieler, "sammeln_normal", $_POST["button_sammeln"]);
									if(isset($_POST["button_ausruhen"])) beginne_aktion($spieler, "ausruhen_normal");
								}
							}
							
							####################
							# Weitere Anzeigen #
							####################
							if($dinge_anzeigen){
								if(isset($_POST["button_inventar"])){
									if ($items = get_all_items_spieler($spieler->id)){
										?>
										<table border="1px" border-color="white" align="center" style="margin-top:10%;" width="700px" >
											<tr>
												<td>Item</td>
												<td>Bild</td>
												<td>Beschreibung</td>
												<td>Typ</td>
												<td>Anzahl</td>
											</tr>
											<?php
											foreach ($items as $item){
												?>
												<tr>
													<td align="left"><?php echo $item->name ?></td>
													<td align="center"><img src="<?php echo get_bild_zu_id($item->bilder_id) ?>" width="75px" alt=""/></td>
													<td align="left"><?php echo $item->beschreibung ?></td>
													<td align="left"><?php echo $item->typ ?></td>
													<td align="right"><?php echo $item->anzahl ?></td>
												</tr>
												<?php
											}
											if(!isset($items[0])){
												?>
												<tr>
													<td colspan=4>Keine Items gefunden.</td>
												</tr>
												<?php
											}
											?>
										</table>
										<p align="center" style="padding-top:10pt;">
											<input type="submit" name="zurueck" value="zurück">
										</p>
										<?php
									} else {
										echo "<br />\nEs sind noch keine Items im Rucksack vorhanden.<br />\n";
									}
								}
								
								if(isset($_POST["button_tagebuch"])){
									include('quest.inc.php');
								}
								if(isset($_POST["button_drachenkampf"]) OR isset($_POST["button_kampf"]) OR (isset($_POST["kt_id_value"]) AND $_POST["kt_id_value"] > 0)){
									include('drachenkampf.inc.php');
								}
								if(isset($_POST["button_handwerk"])){
									include('handwerk.inc.php');
								}
								if(isset($_POST["button_statistik"])){
									if ($statistikdaten = get_npc_spieler_statistik($spieler->id)){	
										$counter = 0;
										?>
										<p align="center" style="margin-top:5%;">
											<?php
											if ($statistik = get_spieler_statistik_balance($spieler)){
												echo "<font style='font-size:14pt;'>Aktuelle Balance ".floor_x($spieler->balance, 0)."%</font><br />";
												echo "Tiere : ".$statistik["angreifbar"]."<br />";
												echo "Pflanzen : ".$statistik["sammelbar"]."<br /><br />";
											}
											?>
										</p>
										<table border="1px" border-color="white" align="center" width="400px">
											<tr>
												<td>NPC</td>
												<td align="right">Anzahl</td>
												<td align="center">Wie</td>
											</tr>
											<?php
											foreach ($statistikdaten as $statistik){
												$counter = $counter + 1;
												?>
												<tr>
													<td align="left"><?php echo $statistik->npc_name ?></td>
													<td align="right"><?php echo $statistik->anzahl ?></td>
													<td align="center"><?php echo $statistik->wie ?></td>
												</tr>
												<?php
											}
											if($counter == 0){
												?>
												<tr>
													<td colspan=3>Noch keine Statistikdaten vorhanden.</td>
												</tr>
												<?php
											}
											?>
										</table>
										<p align="center" style="padding-top:10pt;">
											<input type="submit" name="zurueck" value="zurück">
										</p>
										<?php
									} else {
										?>
										<p align="center" style="margin-top:5%;">
											<?php
											if ($statistik = get_spieler_statistik_balance($spieler)){
												echo "<font style='font-size:14pt;'>Aktuelle Balance ".floor_x($spieler->balance, 0)."%</font><br />";
												echo "Tiere : ".$statistik["angreifbar"]."<br />";
												echo "Pflanzen : ".$statistik["sammelbar"]."<br /><br />";
											}
											?>
										</p>
										<?php
										echo "<br />\nEs liegen noch keine Statistikdaten vor.<br />\n";
									}
								}
								if(isset($_POST["button_charakterdaten"])){
									include('charakterdaten.php');
								}
							}
							else {
								if(!$aktion_starten){
									# Hintergrundbild einblenden, wenn nichts los ist
									zeige_hintergrundbild($spieler->gebiet_id);
								}
							}
						}
						?>
					</div>
				</div>
				
				<div id="mitte_links">
			
					<!-- Button zur Spielerauswahl -->
					<div id="zur_spielerauswahl">
						<input type="submit" name="button_zur_spielerauswahl" value="Zurück zur Spielerauswahl">
					</div>
					
					<!-- Level-/Zahlenzeichen -->
					<div id="levelanzeige">
						<?php 
						if($levelbilder_anzeigen){
							?>
							<div id="l7"><?php echo $level7 ?></div>
							<div id="l6"><?php echo $level6 ?></div>
							<div id="l5"><?php echo $level5 ?></div>
							<div id="l4"><?php echo $level4 ?></div>
							<div id="l3"><?php echo $level3 ?></div>
							<div id="l2"><?php echo $level2 ?></div>
							<div id="l1"><?php echo $level1 ?></div>	
							<?php
						}
						?>
					</div>
					
					<!-- Anzeige des Spielmenüs -->
					<div id="spielmenü_2">
						<div id="erde"><input id="menu2_button_gross" type="submit" name="button_erde" value="Erdelemente"></div>
						<div id="wasser"><input id="menu2_button_gross" type="submit" name="button_wasser" value="Wasserelemente"></div>
						<div id="feuer"><input id="menu2_button_gross" type="submit" name="button_feuer" value="Feuerelemente"></div>
						<div id="luft"><input id="menu2_button_gross" type="submit" name="button_luft" value="Luftelemente"></div>
						<div id="kampf_standard"><input id="menu2_button_gross" type="submit" name="button_kampf_standard" value="Standardangriffe"></div>
					</div>
					
					<div id="spielmenü_1">
						<?php	
						if(isset($_POST["button_elemente"]) OR isset($_POST["button_erde"]) OR isset($_POST["button_wasser"]) OR isset($_POST["button_feuer"]) OR isset($_POST["button_luft"]) OR isset($_POST["button_kampf_standard"]) OR isset($_POST["anzeige_element"])){
							?>
							<script>sichtbar_elemente("menü");</script>
							<div id="menu1"><input id="menu_button_klein" type="submit" name="button_drachenkampf" value="0"></div>
							<div id="menu2"><input id="menu_button_klein" type="submit" name="button_fliegen" value="Fliegen"></div>
							<div id="menu3"><input id="menu_button_klein" type="submit" name="button_gebiet_erkunden" value="Gebiet erkunden"></div>
							<div id="menu4"><input id="menu_button_klein" type="submit" name="button_inventar" value="Gepäck betrachten"></div>
							<div id="menu5"><input id="menu_button_klein" type="submit" name="button_elemente" value="Elemente beschwören"></div>
							<div id="menu6"><input id="menu_button_klein" type="submit" name="button_handwerk" value="Handwerk"></div>
							<div id="menu7"><input id="menu_button_klein" type="submit" name="button_tagebuch" value="Tagebuch"></div>
						<?php	
						} else {
						?>
							<div id="menu1"><input id="menu_button_gross" type="submit" name="button_drachenkampf" value="0"></div>
							<div id="menu2"><input id="menu_button_gross" type="submit" name="button_fliegen" value="Fliegen"></div>
							<div id="menu3"><input id="menu_button_gross" type="submit" name="button_gebiet_erkunden" value="Gebiet erkunden"></div>
							<div id="menu4"><input id="menu_button_gross" type="submit" name="button_inventar" value="Gepäck betrachten"></div>
							<div id="menu5"><input id="menu_button_gross" type="submit" name="button_elemente" value="Elemente beschwören"></div>
							<div id="menu6"><input id="menu_button_gross" type="submit" name="button_handwerk" value="Handwerk"></div>
							<div id="menu7"><input id="menu_button_gross" type="submit" name="button_tagebuch" value="Tagebuch"></div>
						<?php
						}
						?>
					</div>
				</div>
				
				<div id="mitte_rechts">
					
					<!-- Anzeige für Spielerdaten -->
					<div id="charakter">
						<table>
							<colgroup>
								<col width="50%">
								<col width="50%">
							 </colgroup>
							<tr>
								<td colspan="2" align="center"><img src="<?php echo get_bild_zu_id($spieler->bilder_id); ?>" width="200px" alt="Spielerbild"/></td>
							</tr>
							<tr>
								<td colspan="2"><p align="center" style="font-size:14pt"><?php echo get_gattung_titel($spieler->gattung_id) . " " . $spieler->name;?></p></td>
							</tr>
							<tr><td><br/>    </td></tr>
							<tr>
								<td><p align="left">Gesundheit</p></td>
								<td id="char_kurz_spalte2" width="100%">
									<script>var char_kurz_spalte2_breite = document.getElementById('char_kurz_spalte2').offsetWidth;</script>
									<div id="char_kurz_balken_1" style="bottom:0px;">
										<div id="char_kurz_inhalt_1" style="border:1px solid white; text-align:center;">
											<script>balkenanzeige('char_kurz_balken_1', 'char_kurz_inhalt_1', 'red', char_kurz_spalte2_breite, <?php echo "'".($spieler->gesundheit / $spieler->max_gesundheit)."'"; ?>);</script>
											<text><?php echo $spieler->gesundheit."/".$spieler->max_gesundheit; ?></text>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td><p align="left">Zauberpunkte</p></td>
								<td>
									<div id="char_kurz_balken_2" style="bottom:0px;">
										<div id="char_kurz_inhalt_2" style="border:1px solid white; text-align:center;">
											<script>balkenanzeige('char_kurz_balken_2', 'char_kurz_inhalt_2', 'blue', char_kurz_spalte2_breite, <?php echo "'".($spieler->zauberpunkte / $spieler->max_zauberpunkte)."'"; ?>);</script>
											<text><?php echo $spieler->zauberpunkte."/".$spieler->max_zauberpunkte; ?></text>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td><p align="left">Energie</p></td>
								<td>
									<div id="char_kurz_balken_3" style="bottom:0px;">
										<div id="char_kurz_inhalt_3" style="border:1px solid white; text-align:center;">
											<script>balkenanzeige('char_kurz_balken_3', 'char_kurz_inhalt_3', 'green', char_kurz_spalte2_breite, <?php echo "'".($spieler->energie / $spieler->max_energie)."'"; ?>);</script>
											<text><?php echo $spieler->energie."/".$spieler->max_energie; ?></text>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td><p align="left">Erfahrung</p></td>
								<td>
									<div id="char_kurz_balken_4" style="bottom:0px;">
										<div id="char_kurz_inhalt_4" style="border:1px solid white; text-align:center;">
											<script>balkenanzeige('char_kurz_balken_4', 'char_kurz_inhalt_4', 'purple', char_kurz_spalte2_breite, <?php echo "'".(($spieler->erfahrung - $erfahrung_benoetigt_davor) / $erfahrung_benoetigt_aktuell)."'"; ?>);</script>
											<text><?php echo floor_x($spieler->erfahrung, 0)."/".$erfahrung_benoetigt_aktuell; ?></text>
										</div>
									</div>
								</td>
							</tr>
						</table>
					</div>
					
					
					<!-- Anzeige für laufende Aktionen (Clientzeit, Animation, Ladebalken) -->
					<div id="aktionenrahmen">
						<?php
						$aktion_spieler->set_null();
						if($aktionen = get_aktion_spieler($spieler->id)){
							while($row = $aktionen->fetch_array(MYSQLI_NUM)){
								$aktion_spieler->set($row);
							}
						} else {
							echo "<br />\nKeine Aktionen gefunden.<br />\n";
						}
						
						?>			
						<table border="1px" border-color="white">
							<tr>
								<td style="display:none;">
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
											<?php echo $aktion_spieler->text; ?>
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
									<input style="display:none;" id="startzeit_temp" value="<?php echo str_replace('-' , '/' , $aktion_spieler->start); ?>"/>
									<input style="display:none;" id="endezeit_temp" value="<?php echo str_replace('-' , '/' , $aktion_spieler->ende); ?>"/>
									<input style="display:none;" id="titel_temp" value="<?php echo $aktion_spieler->titel; ?>"/>
									<input style="display:none;" id="statusbild_temp" value="<?php echo $aktion_spieler->statusbild; ?>"/>
								</td>
							</tr>
						</table>
					
						<!-- Hier wird definiert, wie oft Zeiten und Ladebalken aktualisiert werden (in Millisekunden) -->
						<script>
							window.setInterval("client_times()", 100);
						</script>
					
					</div>
					
					<div id="musik">
						<u><a href="musik.php" target="_blank" style="color:white;">Starte Musik</a></u>
					</div>
					
				</div>
			</div>
			
			<!-- Untere Leiste -->
			<div id="untere_Leiste">
				<table align="center">
					<tr>
						<td>Feuer <?php echo "&#160;".floor_x($spieler->element_feuer, 0); ?></td>
						<td style="padding-left:30pt;">Erde <?php echo "&#160;".floor_x($spieler->element_erde, 0); ?></td>
						<td style="padding-left:30pt;">Wasser <?php echo "&#160;".floor_x($spieler->element_wasser, 0); ?></td>
						<td style="padding-left:30pt;">Luft <?php echo "&#160;".floor_x($spieler->element_luft, 0); ?></td>
					</tr>
				</table>
			</div>
		</div>
		
		
	</body>
	<?php
		close_connection($connect_db_dvg);
	?>
	<script language="javascript" type="text/javascript" src="zauber.js"></script>
</html>   
    
</form>