<!--DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"-->

<html>
	
	<head>
		<meta http-equiv="Content-Language" content="de">
		<meta http-equiv="Content-Script-Type" content="text/javascript">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<meta name="description" content="Drachen von Gaja - Administration">
		<meta name="Author" content="Tina Schmidtbauer, Hendrik Matthes" >
		<meta charset="utf-8">
		<!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->
	
		<link rel="stylesheet" type="text/css" href="../index_admin.css">
		<script src="index.js" type="text/javascript"></script>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<title>Drachen von Gaja - Administration</title>		
	</head>
	
	<body>
	<form id="dvg_admin" method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<?php
		#header("Content-Type: text/html; charset=utf-8");
		session_start();
		include("db_funktionen_login.php");
		include("db_funktionen_admin.php");
		
		if(isset($_POST["button_name"])) $button_name = $_POST["button_name"];
		else $button_name = false;
		if(isset($_POST["button_value"])) $button_value = $_POST["button_value"];
		else $button_value = false;
		
		$ergebnis = get_anmeldung($_SESSION['login_name']);
		if(!$ergebnis or $ergebnis[5] != "Admin" or $button_name == "zur_spielerauswahl")
		{
			?>
			<script type="text/javascript">
				window.location.href = "../index.php";
			</script>
			<?php
		} else {
	### Hier beginnt der eigentliche Seitenaufbau ###		
			#print_r($_POST);
			?>
			<input type="submit" style="visibility: hidden;">
			<input type="hidden" id="button_name_id" name="button_name" value="">
			<input type="hidden" id="button_value_id" name="button_value" value="">			
			
			<div id="zur_spielerauswahl">
				<input type="button" name="button_zur_spielerauswahl" value="Zurück zur Spielerauswahl" onclick="set_button_submit('zur_spielerauswahl');">
			</div>
						
			<div id="rahmen">
				<?php
				switch($button_name)
				{
					# Bilder laden
					case "BilderLaden":
						$ordner = "../Bilder"; # Standardordner für neue Bilder
						$endungen_bilder = array('jpg','jpeg','bmp','png','gif','ico','tiff'); # erkannte Dateiendungen
						$neue_dateien = array(); # array für alle neuen Dateien im Ordner
						scanneNeueBilder($ordner); # Bilder die noch nicht inder Datenbank stehen, werden in $neue_dateien eingetragen
						?>
						<table border="1px" border-color="white">
							<caption style="font-size:x-large;" colspan="3">Neu in die Datenbank eingefügte Bilder</caption>
							<tr align="left" style="margin:5px;">
								<th width="50px">Lfd Nr</th>
								<th width="150px">Titel</th>
								<th width="300px">Pfad</th>
								<th width="100px">Bild</th>
							</tr>
						<?php
						$anz_gesamt = 0;
						$anz_ok = 0;
						foreach ($neue_dateien as $ds)
						{
							$anz_gesamt = $anz_gesamt + 1;
							?>
							<tr>
								<td><?php echo $anz_gesamt ?></td>
								<td><?php echo $ds['titel'] ?></td>
								<td><?php echo $ds['pfad'] ?></td>
								<td><img src="<?php echo $ds['pfad'] ?>" width="90px" alt="<?php echo $ds['titel'] ?>"/>
							</tr>
							<?php
						}
						?>
						</table>
						<br>
						<?php
						$anz_ok = insert_bilder($neue_dateien);
						echo $anz_ok."/".$anz_gesamt." Bildern erfolgreich eingefügt<br>";
						if($anz_ok < $anz_gesamt) echo "Bitte prüfen!<br>";
						echo "<br>";
						zeigeZurueckButton();
						break;
					
					# NPCs anlegen			
					case "NPCsAnlegen":
						echo "# Lege neue NPCs an<br>"; 
						zeigeZurueckButton();
						break;
										
					# NPCs ändern
					case "NPCsAendern":
						#if($npc = get_npc_by_id(1))
						$titel = "";
						$familie = "";
						$beschreibung = "";
						$typ = "";
						if(isset($_POST['filter_titel'])) $titel = $_POST['filter_titel'];
						if(isset($_POST['filter_familie'])) $familie = $_POST['filter_familie'];
						if(isset($_POST['filter_beschreibung'])) $beschreibung = $_POST['filter_beschreibung'];
						if(isset($_POST['filter_typ'])) $typ = $_POST['filter_typ'];
						?>
						<h2>NPCs</h2>
						<br>
						<table>
							<tr>
								<td>Titel: </td>
								<td><input type="input" name="filter_titel" value="<?php echo $titel ?>" autofocus onFocus="set_button('NPCsAendern','titel');"></td>
							</tr>
							<tr>
								<td>Familie: </td>
								<td><input type="input" name="filter_familie" value="<?php echo $familie ?>" onFocus="set_button('NPCsAendern','familie');"></td>
							</tr>
							<tr>
								<td>Beschreibung: </td>
								<td><input type="input" name="filter_beschreibung" value="<?php echo $beschreibung ?>" onFocus="set_button('NPCsAendern','beschreibung');"></td>
							</tr>
							<tr>
								<td>Typ: </td>
								<td><input type="input" name="filter_typ" value="<?php echo $typ ?>" onFocus="set_button('NPCsAendern','typ');"></td>
							</tr>
							<tr>
								<td></td>
								<td><input type="button" name="button_filterNPCs" value="filtern" onclick="set_button_submit('NPCsAendern');"></td>
							</tr>
						</table>
						<br>
						<?php
						if($npc = suche_npcs("%".$titel."%", "%".$familie."%", "%".$beschreibung."%", "%".$typ."%"))
						{
							?>
							<table border="1px" border-color="white">
								<tr align="left" style="margin:5px;">
									<th>Aktionen</th>
									<th>Id</th>
									<th>Bild</th>
									<th>Element</th>
									<th>Titel</th>
									<th>Familie</th>
									<!--<th>Stärke</th>
									<th>Intelligenz</th>
									<th>Magie</th>
									<th>Feuer</th>
									<th>Wasser</th>
									<th>Erde</th>
									<th>Luft</th>
									<th>Gesundheit</th>
									<th>Energie</th>-->
									<th>Beschreibung</th>
									<th>Typ</th>
								</tr>   
							<?php
							$anz_gesamt = 0;
							while($row = $npc->fetch_array(MYSQLI_NUM))
							{
								$anz_gesamt = $anz_gesamt + 1;
								?>
								<tr>
									<td><input type="button" name="button_NPCbearbeiten" value="bearbeiten" onclick="set_button_submit('NPCbearbeiten',<?php echo $row[0]; ?>);"></td>
									<?php
									$i = 0;
									$i_max = count($row) - 1;
									while($i <= $i_max)
									{
										if($i<5 or $i>13){
											?>
											<td><?php echo $row[$i]; ?></td>
											<?php
										}
										$i = $i + 1;
									}
									?>
								</tr>
								<?php
							}
							?>
							</table>
							<?php
							echo "<br>Das dürfte(n) ".$anz_gesamt." NPC(s) sein.<br>";
						} else {
							echo "<br>Kein NPC gefunden.<br>";
						}
						zeigeZurueckButton();
						break;
					
					# NPCs anlegen			
					case "NPCbearbeiten":
						?>
						<script>set_button_submit('NPCsAendern',$button_value);</script>
						<?php
						echo "# Das NPC mit id = ".$button_value. " soll bearbeitet werden.<br>";
						zeigeZurueckButton("NPCsAendern");
						break;
					
					# Items anlegen					
					case "ItemsAnlegen":
						echo "# Lege neue Items an<br>"; 
						zeigeZurueckButton();
						break;
					
					# Items ändern			
					case "ItemsAendern":
						echo "# Ändere Items<br>"; 
						zeigeZurueckButton();
						break;
					
					default:
						?>
						<div id="Bilder">
							<h3>Bilder</h3>
							<input type="button" name="button_BilderLaden" value="Neue Bilder laden" onclick="set_button_submit('BilderLaden');">
						</div>
						<div id="NPCs" style="padding-top:20px;">
							<h3>NPCs</h3>
							<input type="button" name="button_NPCsAnlegen" value="Neu anlegen" onclick="set_button_submit('NPCsAnlegen');"> (ohne Funktion)<br>
							<input type="button" name="button_NPCsAendern" value="Ändern" onclick="set_button_submit('NPCsAendern'); this.form.submit();">
						</div>
						<div id="Items" style="padding-top:20px;">
							<h3>Items</h3>
							<input type="button" name="button_ItemsAnlegen" value="Neu anlegen" onclick="set_button_submit('ItemsAnlegen');"> (ohne Funktion)<br>
							<input type="button" name="button_ItemsAendern" value="Ändern" onclick="set_button_submit('ItemsAendern');"> (ohne Funktion)
						</div>
						<?php
						break;
				}
				?>
			</div>
			<?php
		}
	?>
	</form>
	</body>
</html>

<?php

	# Blendet einen Button mit Aufschrift "zurück" ein.
	# Eine Betätigung lädt lediglich die Startseite des Adminbereiches neu.
	# Standardausrichtung ist links. Eine individuelle Ausrichtung kann jedoch als Parameter übergeben werden.
	function zeigeZurueckButton($ziel = "zurueck")
	{
		?>
		<input type="button" name="zurueck" value="zurück" style="float:left;" onclick="set_button_submit('<?php echo $ziel ?>');">
		<?php
	}
?>