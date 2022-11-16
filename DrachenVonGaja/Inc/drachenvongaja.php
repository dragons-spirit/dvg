<!--DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"-->

<?php
	
	session_cache_limiter('private');
	session_cache_expire(0);
	session_start();
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
		<!--<script src="../index.js" type="text/javascript"></script>-->
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<title>Drachen von Gaja</title>
		<?php
		if($_SESSION['browser'] == "Opera"){
			?>
			<style>
				head {font-family:Lucida Calligraphy,Georgia,fantasy,EG Dragon Caps,Elementary Gothic; font-size:smaller;}
				body {font-family:Lucida Calligraphy,Georgia,fantasy,EG Dragon Caps,Elementary Gothic; font-size:smaller;}
				input {outline:none;}
                input[type=submit] {font-family:Lucida Calligraphy,Georgia,fantasy,EG Dragon Caps,Elementary Gothic; font-size:smaller;}
				input[type=button] {font-family:Lucida Calligraphy,Georgia,fantasy,EG Dragon Caps,Elementary Gothic; font-size:smaller;}
			</style>
			<?php
		}
		?>
		
	</head>
	
	<body style="background-color:black;">
	<form id="drachenvongaja" method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<?php
		include("klassen.php");
		##### Logout löscht alle Session-Parameter #####
		if (isset($_POST["button_logout"]))
		{
			if (isset($_SESSION["account_id"])) {
				$session = new Session($_SESSION["account_id"], true);
				$session->beenden_logout();
			}
			session_unset();
		}
		if (isset($_SESSION['account_id'])){
			$account_id = $_SESSION['account_id'];
			$konfig = new Konfig($account_id);
			$session = new Session($account_id, true);
			if ($session == false or $session->id == null){
				# Keine offene Session gefunden
				session_unset();
				?>
				<script type="text/javascript">
					window.location.href = "../index.php";
					alert("Ihre letzte Session wurde unvorhergesehen beendet.");
				</script>
				<?php
			} else {
				if ($session->ist_gueltig() == false){
					# Session abgelaufen oder andere IP-Adresse
					session_unset();
					?>
					<script type="text/javascript">
						window.location.href = "../index.php";
						alert("Ihre Session ist abgelaufen.");
					</script>
					<?php
				}
			}
		} else {
			$konfig = new Konfig();
			session_unset();
			?>
			<script type="text/javascript">
				window.location.href = "../index.php";
			</script>
			<?php
		}
		if ($session->aktiv){
			$session->aktualisieren();
		}
		include("db_funktionen.php");
		
		global $debug;
		
		#Aktivieren/Deaktivieren von Anzeigen
		$levelbilder_anzeigen = false;
			
		# Ist der Spieler auch eingeloggt?
		# "nein"	-> zurück zur Anmeldung
		# "ja" 		-> Charakterdaten laden/setzen
		if (isset($_POST["button_zur_spielerauswahl"])){
			?>
			<script type="text/javascript">
				window.location.href = "../index.php";
			</script>
			<?php
		}
			
########################################
# Spielerdaten aus der Datenbank laden #
########################################
		if (isset($_SESSION['spieler_id'])){
			$spieler = new Spieler($_SESSION['spieler_id']);
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
									$aktion = get_aktion($aktion_spieler->titel);
									$keine_npc = true;
									?>
									<p align="center" style="margin-top:10%; margin-bottom:0px; font-size:14pt;">
										Ihr habt die Gegend erkundet.
									</p>
									<table style="margin:auto;margin-top:20px;">
									<?php
									if ($npcs_gebiet = get_npcs_gebiet($spieler->gebiet_id, "angreifbar") and $aktion->art == 'erkunden'){
										foreach($npcs_gebiet as $npc_fund){
											if(check_wkt($npc_fund->wahrscheinlichkeit * $aktion->faktor_1)){
												$keine_npc = false;
												?>
												<tr align="center">
													<td width="85px"><img src="<?php echo get_bild_zu_id($npc_fund->bilder_id) ?>" style="max-height:100px; max-width:200px;" alt=""/></td>
													<td width="150px"><span title="<?php echo $npc_fund->beschreibung ?>"><h3><u><?php echo $npc_fund->name ?></u></h3></span></td>
													<td style="background:url(./../Bilder/Buttons/jagen_angreifen.png); background-repeat:no-repeat;"><input type="submit" style="height:100px; width:200px; opacity: 0.0;" alt="Jagen & Angreifen" name="button_jagen" value="<?php echo $npc_fund->id;?>"></td>
												</tr>
												<?php
											}
										}
									}
									if ($npcs_gebiet = get_npcs_gebiet($spieler->gebiet_id, "sammelbar") and $aktion->art == 'erkunden'){		
										foreach($npcs_gebiet as $npc_fund){
											if(check_wkt($npc_fund->wahrscheinlichkeit * $aktion->faktor_2)){
												$keine_npc = false;
												?>
												<tr align="center">
													<td width="85px"><img src="<?php echo get_bild_zu_id($npc_fund->bilder_id) ?>" style="max-height:100px; max-width:200px;" alt=""/></td>
													<td	width="150px"><span title="<?php echo $npc_fund->beschreibung ?>"><h3><u><?php echo $npc_fund->name ?></u></h3></span></td>
													<td style="background:url(./../Bilder/Buttons/pflanzen_sammeln.png); background-repeat:no-repeat;"><input type="submit" style="height:100px; width:200px; opacity: 0.0;" alt="Pflanzen sammeln" name="button_sammeln" value="<?php echo $npc_fund->id;?>"></td>
												</tr>
												<?php
											}
										}
									}
									if ($npcs_gebiet = get_npcs_gebiet($spieler->gebiet_id, "ansprechbar") and $aktion->art == 'dialog'){		
										foreach($npcs_gebiet as $npc_fund){
											if(check_wkt($npc_fund->wahrscheinlichkeit * $aktion->faktor_2)){
												$keine_npc = false;
												?>
												<tr align="center">
													<td width="85px"><img src="<?php echo get_bild_zu_id($npc_fund->bilder_id) ?>" style="max-height:100px; max-width:200px;" alt=""/></td>
													<td	width="150px"><span title="<?php echo $npc_fund->beschreibung ?>"><h3><u><?php echo $npc_fund->name ?></u></h3></span></td>
													<td><button class="button_standard" type="submit" name="button_dialog_start" value="<?php echo $npc_fund->id;?>">ansprechen</button></td>
												</tr>
												<?php
											}
										}
									}
									if ($keine_npc){
										?>
										</table>
										<br />
										Ihr konntet leider nichts finden.<br />
										<p align="center">
											<button class="button_standard" type="submit" name="verwerfen" value="zurück">zurück</button>
										</p>
										<?php
									} else {
										?>
										</table>
										<br />
										<p align="center">
											<button class="button_standard" type="submit" name="verwerfen" value="gefundene Dinge ignorieren">gefundene Dinge ignorieren</button>
										</p>
										<?php
									}
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
									<button class="button_bild" id="btn_drachenkampf" type="submit" name="button_kampf" value="<?php echo $npc_id;?>" style="margin:auto;margin-top:20px;"></button>
									<?php
									$kampf_id = insert_kampf($spieler->gebiet_id);
									insert_aktion_spieler($spieler->id, "kampf", $kampf_id);
									insert_kampf_teilnehmer($kampf_id, $spieler->id, "spieler", 0);
									insert_kampf_teilnehmer($kampf_id, $npc_id, "npc", 1);
									break;
									
								
								#################################################################
								case "Sammeln":
									update_aktion_spieler($spieler->id, $aktion_spieler->titel);
									$npc_id = $aktion_spieler->any_id_1;
									$gewinn = new Gewinn();
									$anzahl_neu = add_npc_spieler_statistik($spieler->id, $npc_id);
									if ($anzahl_neu == 1){
										$gewinn->staerke = $s_bonus_neu_staerke;
										$gewinn->intelligenz = $s_bonus_neu_intelligenz;
										$gewinn->magie = $s_bonus_neu_magie;
									} else {
										$gewinn->staerke = $s_bonus_staerke;
										$gewinn->intelligenz = $s_bonus_intelligenz;
										$gewinn->magie = $s_bonus_magie;
									}
									$gewinn->erfahrung = zeige_erbeutete_items($spieler, $npc_id, "Pflanzen");
									$gewinn->db_update();
									$spieler->gewinn_verrechnen($gewinn, true);
									?>
									<p align="center" style="padding-top:10pt;">
										<button class="button_standard" type="submit" name="weiter" value="weiter">weiter</button>
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
												$gewinn_id = $kt->gewinn_id;
											}
										}
										$gewinner_seite = ist_kampf_beendet(array_merge($kt_0, $kt_1));
										if ($gewinner_seite == 0){
											$npc_ids = get_all_npcs_kampf($kampf_id);
											add_npc_spieler_statistik($spieler->id, $npc_ids);
											$gewinn = get_gewinn($gewinn_id);
											$gewinn->erfahrung = zeige_erbeutete_items($spieler, $npc_ids, "Tiere");
											$gewinn->db_update();
											$spieler->gewinn_verrechnen($gewinn, true);
										} else {
										?>
											<p align="center" style="margin-top:5%; margin-bottom:0px; font-size:14pt;">
												Ihr wurdet besiegt!
											</p>
										<?php
										}
										?>
										<p align="center" style="padding-top:10pt;">
											<button class="button_standard" type="submit" name="weiter" value="weiter">weiter</button>
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
									$aktion = get_aktion($aktion_spieler->titel);
									$spieler->erholung_prozent(100*$aktion->faktor_1, 100*$aktion->faktor_2, 100*$aktion->faktor_2);
									?>
									<p align="center" style="padding-top:10pt; font-size:14pt;">
										Langsam schlagt ihr die Augen auf und seid bereit für neue Taten.
									</p>
									<p align="center" style="padding-top:10pt;">
										<button class="button_standard" type="submit" name="weiter" value="weiter">weiter</button>
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
							$aktion_starten = (isset($_POST["button_gebiet_erkunden_start"]) OR isset($_POST["button_zum_zielgebiet"]) OR isset($_POST["button_jagen"]) OR isset($_POST["button_sammeln"]) OR isset($_POST["button_ausruhen"]));
							$dinge_anzeigen = (isset($_POST["button_gebiet_erkunden"]) OR isset($_POST["button_inventar"]) OR $elementebutton > 0 OR isset($_POST["button_tagebuch"]) OR isset($_POST["button_drachenkampf"]) OR isset($_POST["button_handwerk"]) OR isset($_POST["button_kampf"]) OR (isset($_POST["kt_id_value"]) AND $_POST["kt_id_value"] > 0) OR isset($_POST["button_statistik"]) OR isset($_POST["button_charakterdaten"]) OR isset($_POST["button_konfiguration"]) OR isset($_POST["button_konfiguration_speichern"]) OR isset($_POST["button_dialog_start"]) OR isset($_POST["button_dialog_weiter"]));
							
							
							######################
							# Start von Aktionen #
							######################
							if($aktion_starten){				
								# Hintergrundbild einblenden, wenn neue Aktion gestartet werden soll
								# + Hinweis, falls noch eine Aktion aktiv ist (siehe zeige_hintergrundbild())
								zeige_hintergrundbild($spieler->gebiet_id, $aktion_spieler->text, $spieler->bewusstlos());
								if (!$aktion_spieler->titel){	
									if(isset($_POST["button_gebiet_erkunden_start"]) and !$spieler->bewusstlos()) beginne_aktion($spieler, $_POST["button_gebiet_erkunden_start"]);
									if(isset($_POST["button_zum_zielgebiet"]) and !$spieler->bewusstlos()) beginne_aktion($spieler, "laufen", get_gebiet_id($_POST["button_zum_zielgebiet"]));
									if(isset($_POST["button_jagen"]) and !$spieler->bewusstlos()) beginne_aktion($spieler, "jagen", $_POST["button_jagen"]);
									if(isset($_POST["button_sammeln"]) and !$spieler->bewusstlos()) beginne_aktion($spieler, "sammeln", $_POST["button_sammeln"]);
									if(isset($_POST["button_ausruhen"]) and !$spieler->bewusstlos()) beginne_aktion($spieler, $_POST["button_ausruhen"]);
									if(isset($_POST["button_ausruhen"]) and $spieler->bewusstlos() and $_POST["button_ausruhen"]=="ausruhen_voll") beginne_aktion($spieler, $_POST["button_ausruhen"]);
								}
							}
							
							####################
							# Weitere Anzeigen #
							####################
							if($dinge_anzeigen){
								if(isset($_POST["button_gebiet_erkunden"])){
									include('gebiet_erkunden.php');
								}
								if(isset($_POST["button_inventar"])){
									include('inventar.php');
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
								if(isset($_POST["button_dialog_start"]) OR isset($_POST["button_dialog_weiter"])){
									include('dialog.php');
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
										<table class="tabelle" cellpadding="5px" align="center" width="400px">
											<tr class="tabelle_kopf">
												<td>NPC</td>
												<td align="right">Anzahl</td>
												<td align="center">Wie</td>
											</tr>
											<?php
											foreach ($statistikdaten as $statistik){
												$counter = $counter + 1;
												?>
												<tr class="tabelle_inhalt">
													<td align="left"><?php echo $statistik->npc_name ?></td>
													<td align="right"><?php echo $statistik->anzahl ?></td>
													<td align="center"><?php echo $statistik->wie ?></td>
												</tr>
												<?php
											}
											if($counter == 0){
												?>
												<tr class="tabelle_inhalt">
													<td colspan=3>Noch keine Statistikdaten vorhanden.</td>
												</tr>
												<?php
											}
											?>
										</table>
										<p align="center" style="padding-top:10pt;">
											<button class="button_standard" type="submit" name="zurueck" value="zurück">zurück</button>
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
								if(isset($_POST["button_konfiguration"]) OR isset($_POST["button_konfiguration_speichern"])){
									include('konfiguration.php');
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
					
					<div id="spielmenü">
						<div class="dropdown">
							<button class="button_standard_menue" type="button" name="button_menue" style="width:120px;">Menü</button>
							<div class="dropdown-inhalt-standard">
								<button class="button_standard_menue" type="submit" name="button_zur_spielerauswahl">Zurück zur Spielerauswahl</button>
								<button class="button_standard_menue" type="submit" formaction="musik.php" formtarget="_blank">Musik starten</button>
								<button class="button_standard_menue" type="submit" name="button_konfiguration" value="Einstellungen">Einstellungen</button>
								<button class="button_standard_menue" type="submit" name="button_logout" value="Logout">Logout</button>
							</div>
						</div>
						<div class="dropdown">
							<button class="button_bild" id="btn_weltkarte" type="submit" name="button_weltkarte" value="Weltkarte"></button>
						</div>
						<div class="dropdown">
							<button class="button_bild" id="btn_gegend_erkunden" type="submit" name="button_gebiet_erkunden" value="0"></button>
						</div>
						<div class="dropdown">
							<button class="button_bild" id="btn_drachenkampf" type="submit" name="button_drachenkampf" value="0"></button>
						</div>
						<div class="dropdown">
							<button class="button_bild" id="btn_gepaeck_betrachten" type="submit" name="button_inventar" value="Gepäck betrachten"></button>
						</div>
						<div class="dropdown">
							<button class="button_bild" id="btn_ausruhen" type="button" name="button_ausruhen_menue" style="width:120px;"></button>
							<div class="dropdown-inhalt-standard">
								<button class="button_standard_menue" type="submit" name="button_ausruhen" value="ausruhen_kurz">Power Nap</button><!--Kleines Nickerchen-->
								<button class="button_standard_menue" type="submit" name="button_ausruhen" value="ausruhen_normal">Mittagsschlaf</button><!--Kurzes Schläfchen-->
								<button class="button_standard_menue" type="submit" name="button_ausruhen" value="ausruhen_lang">Nachtruhe</button><!--Ausgedehnte Ruhepause-->
								<button class="button_standard_menue" type="submit" name="button_ausruhen" value="ausruhen_voll">Reinkarnation</button><!--Erneutes Erwachen-->
							</div>
						</div>
						<div class="dropdown">
							<button class="button_bild" id="btn_elemente_beschwoeren" type="button" name="button_elemente" value="Elemente beschwören"></button>
							<div class="dropdown-inhalt-bild">
								<button class="button_bild" id="btn_elemente_der_erde" type="submit" name="button_erde" value="Erdelemente"></button>
								<button class="button_bild" id="btn_elemente_des_wassers" type="submit" name="button_wasser" value="Wasserelemente"></button>
								<button class="button_bild" id="btn_elemente_des_feuers" type="submit" name="button_feuer" value="Feuerelemente"></button>
								<button class="button_bild" id="btn_elemente_der_luft" type="submit" name="button_luft" value="Luftelemente"></button>
								<button class="button_bild" id="btn_standardangriffe" type="submit" name="button_kampf_standard" value="Standardangriffe"></button>
							</div>
						</div>
						<div class="dropdown">
							<button class="button_bild" id="btn_fliegen" type="submit" name="button_fliegen" value="Fliegen"></button>
						</div>
						<div class="dropdown">
							<button class="button_bild" id="btn_handwerk" type="submit" name="button_handwerk" value="Handwerk"></button>
						</div>
						<div class="dropdown">
							<button class="button_bild" id="btn_tagebuch" type="submit" name="button_tagebuch" value="Tagebuch"></button>
							<div class="dropdown-inhalt-standard">
								<button class="button_standard_menue" type="submit" name="button_charakterdaten" value="Charakterdaten">Charakterdaten</button>
								<button class="button_standard_menue" type="submit" name="button_statistik" value="Statistik">Statistik</button>
							</div>
						</div>
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
											<script>balkenanzeige('char_kurz_balken_4', 'char_kurz_inhalt_4', 'purple', char_kurz_spalte2_breite, <?php echo "'".(($spieler->erfahrung - $erfahrung_benoetigt_davor) / ($erfahrung_benoetigt_aktuell - $erfahrung_benoetigt_davor))."'"; ?>);</script>
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
						<table>
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
										</b><br />
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
	<script language="javascript" type="text/javascript" src="mouseover.js"></script>
</html>   
    
</form>