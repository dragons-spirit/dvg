<!--DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"-->

<?php
	session_start();
	include("connect.inc.php");
	$connect_db_dvg = open_connection($default_user, $default_pswd, $default_host, $default_db);
?>

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
		<script src="../index.js" type="text/javascript"></script>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<title>Drachen von Gaja - Administration</title>
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
	
	<body>
	<form id="dvg_admin" method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<?php
		#header("Content-Type: text/html; charset=utf-8");
		include("klassen.php");
		include("db_funktionen.php");
		include("db_funktionen_admin.php");
		include("funktionen_system.php");
		
		if(isset($_POST["button_name"])) $button_name = $_POST["button_name"];
		else $button_name = "AdminStart";
		if(isset($_POST["button_value"])) $button_value = $_POST["button_value"];
		else $button_value = false;
		
		$ergebnis = get_anmeldung($_SESSION['login_name']);
		if(!$ergebnis or $ergebnis[5] != 1 or $button_name == "zur_spielerauswahl")
		{
			?>
			<script type="text/javascript">
				window.location.href = "../index.php";
			</script>
			<?php
		} else {
	### Hier beginnt der eigentliche Seitenaufbau ###
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
						scanneNeueBilder($ordner); # Bilder die noch nicht in der Datenbank stehen, werden in $neue_dateien eingetragen
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
						zurueckButton();
						break;
					
					# NPCs ändern
					case "NPCsSuchen":
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
									<th>Beschreibung</th>
									<th>Typ</th>
								</tr>
								<tr align="left" style="margin:5px;">
									<td><input type="button" name="button_NPCbearbeiten" value="hinzufügen" onclick="set_button_submit('NPCbearbeiten',0);"></td>
									<td></td><td></td><td></td>
									<td><input type="input" name="filter_titel" value="<?php echo $titel ?>" autofocus onFocus="set_button('NPCsSuchen','titel');"></td>
									<td><input type="input" name="filter_familie" value="<?php echo $familie ?>" onFocus="set_button('NPCsSuchen','familie');"></td>
									<td><input type="input" name="filter_beschreibung" value="<?php echo $beschreibung ?>" onFocus="set_button('NPCsSuchen','beschreibung');"></td>
									<td><input type="input" name="filter_typ" value="<?php echo $typ ?>" onFocus="set_button('NPCsSuchen','typ');"></td>
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
										if($i<5 or ($i>17 and $i<20)){
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
						zurueckButton();
						break;
					
					# NPCs anlegen			
					case "NPCbearbeiten":
						$npc_id = $button_value;
						?>
						<!-- Standardaktion - Seite neu laden mit selber NPC_Id -->
						<!--<script>set_button_submit('NPCaendern', $npc_id);</script>--> <!-- npc_id kann hier nicht einfach per php eingefügt werden -->
						<?php
						$npc = false;
						if($npc_id > 0){
							if($npc_result = get_npc_by_id($npc_id)){
								$npc = $npc_result->fetch_array(MYSQLI_NUM);
						}}
						$npc_gebiete = false;
						if($npc_id > 0){
							$npc_gebiete = get_npc_gebiete($npc_id);
						}
						$npc_items = false;
						if($npc_id > 0){
							$npc_items = get_npc_items($npc_id);
						}
						?>
						<table>
							<tr>
								<td>
									<?php eingabemaskeNPC($npc, $npc_id); ?>
								</td>
								<td valign="top" style="padding-top:50px;">
									<img src="<?php echo get_bild_zu_id($npc[1]) ?>" width="75px" alt=""/><br>
								</td>
								<td valign="top">
									<?php eingabemaskeNPCgebiete($npc_gebiete, $npc_id); ?>
								</td>
								<td valign="top" style="padding-left:20px;">
									<?php eingabemaskeNPCitems($npc_items, $npc_id); ?>
								</td>
							</tr>
						</table>
						<?php
						updateButton("NPCaendern",$npc_id);
						zurueckButton("NPCsSuchen");
						echo "<br><br>";
						break;
					
					/* Ausführung von Änderungen am NPC */
					case "NPCaendern":
						
						#Update NPC-Daten
						$npc_id = $button_value;
						$npc_daten = daten_aus_post("npc");
						if($npc_id > 0){
							if (updateNPC($npc_daten))
								echo "NPC erfolgreich geändert";
							else
								echo "Keine Änderungen an NPC vorgenommen";
						} else {
							if (insertNPC($npc_daten))
								echo "NPC erfolgreich hinzugefügt";								
							else
								echo "Fehler beim Hinzufügen des NPCs";
						}
						echo "<br><br>";
						
						#Update NPC_Gebiet-Daten
						$npc_gebiet_daten = daten_aus_post("npc_gebiete");
						#ausgabe_array($npc_gebiet_daten,2);
						$anz_delete = deleteNPCgebiete($npc_id);
						$anz_insert = 0;
						foreach ($npc_gebiet_daten as $ds){
							if ($ds["wkt"]>0 and $ds["gebiet_id"]<>12){ #Wahrscheinlichkeit größer 0 und Gebiet ungleich ---ohne---
								$anz_insert += insertNPCgebiet($ds);
							}
						}
						echo "Alle Vorkommen des NPC in Gebieten wurden aktualisiert [alt: ".$anz_delete."] [neu: ".$anz_insert."]";
						echo "<br><br>";
						
						#Update NPC_Item-Daten
						$npc_item_daten = daten_aus_post("npc_items");
						#ausgabe_array($npc_item_daten,2);
						$anz_delete = deleteNPCitems($npc_id);
						$anz_insert = 0;
						foreach ($npc_item_daten as $ds){
							if ($ds["wkt"]>0 and $ds["items_id"]<>9){ #Wahrscheinlichkeit größer 0 und Item ungleich ---ohne---
								$anz_insert += insertNPCitem($ds);
							}
						}
						echo "Alle Vorkommen von Items beim NPC wurden aktualisiert [alt: ".$anz_delete."] [neu: ".$anz_insert."]";
						echo "<br><br>";
						
						zurueckButton("NPCsSuchen");
						break;
					
					# Items ändern
					case "ItemsSuchen":
						$titel = "";
						$familie = "";
						$beschreibung = "";
						$slot = "";
						$essbar = "";
						$ausruestbar = "";
						$verarbeitbar = "";
						if(isset($_POST['filter_titel'])) $titel = $_POST['filter_titel'];
						if(isset($_POST['filter_familie'])) $familie = $_POST['filter_familie'];
						if(isset($_POST['filter_beschreibung'])) $beschreibung = $_POST['filter_beschreibung'];
						if(isset($_POST['filter_slot'])) $slot = $_POST['filter_slot'];
						if(isset($_POST['filter_essbar'])) $essbar = $_POST['filter_essbar'];
						if(isset($_POST['filter_ausruestbar'])) $ausruestbar = $_POST['filter_ausruestbar'];
						if(isset($_POST['filter_verarbeitbar'])) $verarbeitbar = $_POST['filter_verarbeitbar'];
						?>
						<h2>Items</h2>
						<br>
						<?php
						if($items = suche_items("%".$titel."%", "%".$beschreibung."%", "%".$slot."%", "%".$essbar."%", "%".$ausruestbar."%", "%".$verarbeitbar."%"))
						{
							?>
							<table border="1px" border-color="white">
								<tr align="left" style="margin:5px;">
									<th>Aktionen</th>
									<th>Id</th>
									<th>Titel</th>
									<th>Beschreibung</th>
									<th>Slot</th>
									<th>Bild</th>
									<th>Essen</th>
									<th>Ausrüsten</th>
									<th>Verarbeiten</th>
								</tr>
								<tr align="left" style="margin:5px;">
									<td><input type="button" name="button_ItemBearbeiten" value="hinzufügen" onclick="set_button_submit('ItemBearbeiten',0);"></td>
									<td></td>
									<td><input type="input" name="filter_titel" value="<?php echo $titel ?>" autofocus onFocus="set_button('ItemsSuchen','titel');"></td>
									<td><input type="input" name="filter_beschreibung" value="<?php echo $beschreibung ?>" onFocus="set_button('ItemsSuchen','beschreibung');"></td>
									<td><input type="input" name="filter_slot" value="<?php echo $slot ?>" onFocus="set_button('ItemsSuchen','slot');"></td>
									<td></td>
									<td align="center"><input type="input" name="filter_essbar" value="<?php echo $essbar ?>" onFocus="set_button('ItemsSuchen','essbar');"></td>
									<td align="center"><input type="input" name="filter_ausruestbar" value="<?php echo $ausruestbar ?>" onFocus="set_button('ItemsSuchen','ausruestbar');"></td>
									<td align="center"><input type="input" name="filter_verarbeitbar" value="<?php echo $verarbeitbar ?>" onFocus="set_button('ItemsSuchen','verarbeitbar');"></td>
								</tr>   
							<?php
							$anz_gesamt = 0;
							foreach ($items as $item){
								$anz_gesamt = $anz_gesamt + 1;
								?>
								<tr>
									<td><input type="button" name="button_ItemBearbeiten" value="bearbeiten" onclick="set_button_submit('ItemBearbeiten',<?php echo $item->id; ?>);"></td>
									<td align="left"><?php echo $item->id ?></td>
									<td align="left"><?php echo $item->name ?></td>
									<td align="left" style="max-width:500px;"><?php echo $item->beschreibung ?></td>
									<td align="left"><?php echo $item->slot->name ?></td>
									<td align="center"><img src="<?php echo get_bild_zu_id($item->bilder_id) ?>" width="75px" alt=""/></td>
									<td align="center"><?php echo $item->essbar; ?></td>
									<td align="center"><?php echo $item->ausruestbar; ?></td>
									<td align="center"><?php echo $item->verarbeitbar; ?></td>
								</tr>
								<?php
							}
							?>
							</table>
							<?php
							echo "<br>Das dürfte(n) ".$anz_gesamt." Item(s) sein.<br>";
						} else {
							echo "<br>Kein Item gefunden.<br>";
						}
						zurueckButton();
						break;
					
					# Items anlegen
					case "ItemBearbeiten":
						$item_id = $button_value;
						?>
						<!-- Standardaktion - Seite neu laden mit selber Item_Id -->
						<?php
						$item = false;
						if($item_id > 0){
							$item = get_item_by_id($item_id);
						}
						?>
						<table>
							<tr>
								<td>
									<?php eingabemaskeItem($item); ?>
								</td>
								<td valign="top" style="padding-top:50px;">
									<img src="<?php echo get_bild_zu_id($item->bilder_id) ?>" width="75px" alt=""/><br>
								</td>
							</tr>
						</table>
						<?php
						updateButton("ItemAendern",$item_id);
						zurueckButton("ItemsSuchen");
						echo "<br><br>";
						break;
						
					/* Ausführung von Änderungen am Item */
					case "ItemAendern":
						#Update Item-Daten
						$item_id = $button_value;
						$item = daten_aus_post("item");
						if($item_id > 0){
							if (updateItem($item))
								echo "Item erfolgreich geändert";
							else
								echo "Keine Änderungen an Item vorgenommen";
						} else {
							if (insertItem($item))
								echo "Item erfolgreich hinzugefügt";								
							else
								echo "Fehler beim Hinzufügen des Items";
						}
						echo "<br><br>";
						zurueckButton("ItemsSuchen");
						break;
					
					case "AdminStart":
						?>
						<div id="Bilder">
							<h3>Bilder</h3>
							<input type="button" name="button_BilderLaden" value="Neue Bilder laden" onclick="set_button_submit('BilderLaden');">
						</div>
						<div id="NPCs" style="padding-top:20px;">
							<h3>NPCs</h3>
							<input type="button" name="button_NPCsSuchen" value="Hinzufügen/Ändern" onclick="set_button_submit('NPCsSuchen'); this.form.submit();">
						</div>
						<div id="Items" style="padding-top:20px;">
							<h3>Items</h3>
							<input type="button" name="button_ItemsSuchen" value="Hinzufügen/Ändern" onclick="set_button_submit('ItemsSuchen'); this.form.submit();">
						</div>
						<?php
						break;
					
					default:
						?>
						Hier passiert nix !
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

<!------------------------------------------------------------------------------------------------------------------------------------------------------------------>
<!----------------------------------------------------------------------- Weitere Funktionen ----------------------------------------------------------------------->
<!------------------------------------------------------------------------------------------------------------------------------------------------------------------>
<?php

	# Blendet einen Button mit Aufschrift "zurück" ein.
	# Der Funktion wird die Zielseite mit übergeben ("NPCsAendern", "NPCsSuchen", ...). Wird keine Zielseite übergeben, so landet man auf der Startseite im Adminbereich.
	function zurueckButton($ziel = "AdminStart")
	{
		?>
		<input type="button" name="zurueck" value="zurück" style="float:left;" onclick="set_button_submit('<?php echo $ziel ?>');">
		<?php
	}
	
	
	function eingabemaskeNPC($row, $npc_id)
	{
		?>
		<table>
			<tr>
				<td colspan="2" align="left"><h2>Allgemeine Daten</h2></td>
			</tr>
			<tr>
				<td>Id</td>
				<td><input id="allg_info_eingabe" type="input" style="background-color:lightgrey;" name="npc_id" value="<?php if($row) echo $row[0]; ?>" readonly></td>
			</tr>
			<tr>
				<td>Bild</td>
				<td align="top">
					<?php
					if($bilder = get_bilder_titel("../Bilder/NPC/"))
					{
						?>
						<select name="npc_bild">
						<?php
						while($bild = $bilder->fetch_array(MYSQLI_NUM))
						{
							if($row[1] != $bild[0]){
								echo "<option value='".$bild[0]."' onFocus=\"set_button('NPCaendern',".$npc_id.");\">".$bild[1]."</option>";
							} else {
								echo "<option value='".$bild[0]."' onFocus=\"set_button('NPCaendern',".$npc_id.");\" selected>".$bild[1]."</option>";
							}
						}
						?>
						</select>
						<?php
					} else {
						echo "Fehler beim Laden von Bildern.";
					}
					?>
				</td>
			</tr>
			<tr>
				<td>Element</td>
				<td>
					<?php
					if($elemente = get_elemente_titel())
					{
						?>
						<select name="npc_element">
						<?php
						while($element = $elemente->fetch_array(MYSQLI_NUM))
						{
							if($row[2] != $element[0]){
								echo "<option value='".$element[0]."' onFocus=\"set_button('NPCaendern',".$npc_id.");\">".$element[1]."</option>";
							} else {
								echo "<option value='".$element[0]."' onFocus=\"set_button('NPCaendern',".$npc_id.");\" selected>".$element[1]."</option>";
							}
						}
						?>
						</select> 
					<?php
					} else {
						echo "Fehler beim Laden von Elementen.";
					}
					?>
				</td>
			</tr>
			<tr>
				<td>Titel</td>
				<td><input id="allg_info_eingabe_text" type="input" name="npc_titel" value="<?php if($row) echo $row[3]; ?>" onFocus="set_button('NPCaendern',<?php echo $npc_id; ?>);"></td>
			</tr>
			<tr>
				<td>Familie</td>
				<td><input id="allg_info_eingabe_text" type="input" name="npc_familie" value="<?php if($row) echo $row[4]; ?>" onFocus="set_button('NPCaendern',<?php echo $npc_id; ?>);"></td>
			</tr>
			<tr>
				<td>Stärke</td>
				<td><input id="allg_info_eingabe" type="input" name="npc_staerke" value="<?php if($row) echo $row[5]; ?>" onFocus="set_button('NPCaendern',<?php echo $npc_id; ?>);"></td>
			</tr>
			<tr>
				<td>Intelligenz</td>
				<td><input id="allg_info_eingabe" type="input" name="npc_intelligenz" value="<?php if($row) echo $row[6]; ?>" onFocus="set_button('NPCaendern',<?php echo $npc_id; ?>);"></td>
			</tr>
			<tr>
				<td>Magie</td>
				<td><input id="allg_info_eingabe" type="input" name="npc_magie" value="<?php if($row) echo $row[7]; ?>" onFocus="set_button('NPCaendern',<?php echo $npc_id; ?>);"></td>
			</tr>
			<tr>
				<td>Feuer</td>
				<td><input id="allg_info_eingabe" type="input" name="npc_feuer" value="<?php if($row) echo $row[8]; ?>" onFocus="set_button('NPCaendern',<?php echo $npc_id; ?>);"></td>
			</tr>
			<tr>
				<td>Wasser</td>
				<td><input id="allg_info_eingabe" type="input" name="npc_wasser" value="<?php if($row) echo $row[9]; ?>" onFocus="set_button('NPCaendern',<?php echo $npc_id; ?>);"></td>
			</tr>
			<tr>
				<td>Erde</td>
				<td><input id="allg_info_eingabe" type="input" name="npc_erde" value="<?php if($row) echo $row[10]; ?>" onFocus="set_button('NPCaendern',<?php echo $npc_id; ?>);"></td>
			</tr>
			<tr>
				<td>Luft</td>
				<td><input id="allg_info_eingabe" type="input" name="npc_luft" value="<?php if($row) echo $row[11]; ?>" onFocus="set_button('NPCaendern',<?php echo $npc_id; ?>);"></td>
			</tr>
			<tr>
				<td>Gesundheit</td>
				<td><input id="allg_info_eingabe" type="input" name="npc_gesundheit" value="<?php if($row) echo $row[12]; ?>" onFocus="set_button('NPCaendern',<?php echo $npc_id; ?>);"></td>
			</tr>
			<tr>
				<td>Energie</td>
				<td><input id="allg_info_eingabe" type="input" name="npc_energie" value="<?php if($row) echo $row[13]; ?>" onFocus="set_button('NPCaendern',<?php echo $npc_id; ?>);"></td>
			</tr>
			<tr>
				<td>Zauberpunnkte</td>
				<td><input id="allg_info_eingabe" type="input" name="npc_zauberpunkte" value="<?php if($row) echo $row[14]; ?>" onFocus="set_button('NPCaendern',<?php echo $npc_id; ?>);"></td>
			</tr>
			<tr>
				<td>Initiative</td>
				<td><input id="allg_info_eingabe" type="input" name="npc_initiative" value="<?php if($row) echo $row[15]; ?>" onFocus="set_button('NPCaendern',<?php echo $npc_id; ?>);"></td>
			</tr>
			<tr>
				<td>Abwehr</td>
				<td><input id="allg_info_eingabe" type="input" name="npc_abwehr" value="<?php if($row) echo $row[16]; ?>" onFocus="set_button('NPCaendern',<?php echo $npc_id; ?>);"></td>
			</tr>
			<tr>
				<td>Ausweichen</td>
				<td><input id="allg_info_eingabe" type="input" name="npc_ausweichen" value="<?php if($row) echo $row[17]; ?>" onFocus="set_button('NPCaendern',<?php echo $npc_id; ?>);"></td>
			</tr>
			<tr>
				<td>Beschreibung</td>
				<td><textarea id="allg_info_eingabe_text" style="height:150px; width:200px;" name="npc_beschreibung" onFocus="set_button('NPCaendern',<?php echo $npc_id; ?>);"><?php if($row) echo $row[18]; ?></textarea></td>
			</tr>
			<tr>
				<td>Typ</td>
				<td>
					<?php
					if($typen = get_npc_typen_titel())
					{
						?>
						<select name="npc_typ">
						<?php
						while($typ = $typen->fetch_array(MYSQLI_NUM))
						{
							if($row[19] != $typ[0]){
								echo "<option value='".$typ[0]."' onFocus=\"set_button('NPCaendern',".$npc_id.");\">".$typ[0]."</option>";
							} else {
								echo "<option value='".$typ[0]."' onFocus=\"set_button('NPCaendern',".$npc_id.");\" selected>".$typ[0]."</option>";
							}
						}
						?>
						</select> 
					<?php
					} else {
						echo "Fehler beim Laden von Typen.";
					}
					?>
				</td>
			</tr>
			<tr>
				<td>KI</td>
				<td align="top">
					<?php
					if($ki_namen = get_ki_namen())
					{
						?>
						<select name="npc_ki">
						<?php
						while($ki = $ki_namen->fetch_array(MYSQLI_NUM))
						{
							if($row[20] != $ki[0]){
								echo "<option value='".$ki[0]."' onFocus=\"set_button('NPCaendern',".$npc_id.");\">".$ki[1]."</option>";
							} else {
								echo "<option value='".$ki[0]."' onFocus=\"set_button('NPCaendern',".$npc_id.");\" selected>".$ki[1]."</option>";
							}
						}
						?>
						</select>
						<?php
					} else {
						echo "Fehler beim Laden von KIs.";
					}
					?>
				</td>
			</tr>
		</table>
		<br>
		<?php
	}
	
	
	function eingabemaskeNPCgebiete($npc_gebiete, $npc_id)
	{
		?>
		<table>
			<tr>
				<td colspan="2" align="left"><h2>Zu finden in</h2></td>
			</tr>
			<tr>
				<th>Gebiet</th>
				<th>Wkt in %</th>
			</tr>
			<?php
			$count = 0;
			if($npc_gebiete)
			{
				while($npc_gebiet = $npc_gebiete->fetch_array(MYSQLI_NUM))
				{
					?>
					<tr id="<?php echo 'npc_gebiet_neu_'.$count; ?>">
						<td>
							<?php
							if($gebiete = get_gebiete_titel())
							{
								?>
								<select id="<?php echo 'npc_gebiet_auswahl_'.$count; ?>" name="<?php echo 'npc_gebiet_auswahl_'.$count; ?>">
								<?php
								while($gebiet = $gebiete->fetch_array(MYSQLI_NUM))
								{
									if($npc_gebiet[0] != $gebiet[0]){
										echo "<option value='".$gebiet[0]."' onFocus=\"set_button('NPCaendern',".$npc_id.");\">".$gebiet[1]."</option>";
									} else {
										echo "<option value='".$gebiet[0]."' onFocus=\"set_button('NPCaendern',".$npc_id.");\" selected>".$gebiet[1]."</option>";
									}
								}
								?>
								</select> 
							<?php
							} else {
								echo "Fehler beim Laden von Gebieten.";
							}
							?>
						</td>
						<td>
							<input id="<?php echo 'npc_gebiet_wkt_'.$count; ?>" type="input" name="<?php echo 'npc_gebiet_wkt_'.$count; ?>" value="<?php echo $npc_gebiet[1]; ?>" onFocus="set_button('NPCaendern',<?php echo $npc_id; ?>);">
						</td>
					</tr>
					<?php
					$count++;
				}
			}
			?>
			<tr>
				<td colspan="2" align="left">
					<br><input type="button" name="weiteresGebiet" value="Weiteres Gebiet hinzufügen" onclick="weiteresElement('npc_gebiet_neu')"><br>
				</td>
			</tr>
			<tr id="<?php echo 'npc_gebiet_neu_'.$count; ?>">
				<td>
					<?php
					if($gebiete = get_gebiete_titel())
					{
						?>
						<select id="<?php echo 'npc_gebiet_auswahl_'.$count; ?>" name="<?php echo 'npc_gebiet_auswahl_'.$count; ?>">
						<?php
						while($gebiet = $gebiete->fetch_array(MYSQLI_NUM))
						{
							echo "<option value='".$gebiet[0]."' onFocus=\"set_button('NPCaendern',".$npc_id.");\">".$gebiet[1]."</option>";
						}
						?>
						</select> 
					<?php
					} else {
						echo "Fehler beim Laden von Gebieten.";
					}
					?>
				</td>
				<td>
					<input id="<?php echo 'npc_gebiet_wkt_'.$count; ?>" type="input" name="<?php echo 'npc_gebiet_wkt_'.$count; ?>" value="0" onFocus="set_button('NPCaendern',<?php echo $npc_id; ?>);">
				</td>
			</tr>
		</table>
		<br>
		<?php
	}
	
	
	function eingabemaskeNPCitems($npc_items, $npc_id)
	{
		?>
		<table>
			<tr>
				<td colspan="4" align="left"><h2>Mögliche Items</h2></td>
			</tr>
			<tr>
				<th>Item</th>
				<th>Wkt in %</th>
				<th>Minimum</th>
				<th>Maximum</th>
			</tr>
			<?php
			$count = 0;
			if($npc_items)
			{
				while($npc_item = $npc_items->fetch_array(MYSQLI_NUM))
				{
					?>
					<tr id="<?php echo 'npc_item_neu_'.$count; ?>">
						<td>
							<?php
							if($items = get_items_titel())
							{
								?>
								<select id="<?php echo 'npc_item_auswahl_'.$count; ?>" name="<?php echo 'npc_item_auswahl_'.$count; ?>">
								<?php
								while($item = $items->fetch_array(MYSQLI_NUM))
								{
									if($npc_item[0] != $item[0]){
										echo "<option value='".$item[0]."' onFocus=\"set_button('NPCaendern',".$npc_id.");\">".$item[1]."</option>";
									} else {
										echo "<option value='".$item[0]."' onFocus=\"set_button('NPCaendern',".$npc_id.");\" selected>".$item[1]."</option>";
									}
								}
								?>
								</select> 
							<?php
							} else {
								echo "Fehler beim Laden von Items.";
							}
							?>
						</td>
						<td>
							<input id="<?php echo 'npc_item_wkt_'.$count; ?>" type="input" name="<?php echo 'npc_item_wkt_'.$count; ?>" value="<?php echo $npc_item[1]; ?>" onFocus="set_button('NPCaendern',<?php echo $npc_id; ?>);">
						</td>
						<td>
							<input id="<?php echo 'npc_item_min_'.$count; ?>" type="input" name="<?php echo 'npc_item_min_'.$count; ?>" value="<?php echo $npc_item[2]; ?>" onFocus="set_button('NPCaendern',<?php echo $npc_id; ?>);">
						</td>
						<td>
							<input id="<?php echo 'npc_item_max_'.$count; ?>" type="input" name="<?php echo 'npc_item_max_'.$count; ?>" value="<?php echo $npc_item[3]; ?>" onFocus="set_button('NPCaendern',<?php echo $npc_id; ?>);">
						</td>
					</tr>
					<?php
					$count++;
				}
			}
			?>
			<tr>
				<td colspan="4" align="left">
					<br><input type="button" name="weiteresItem" value="Weiteres Item hinzufügen" onclick="weiteresElement('npc_item_neu')"><br>
				</td>
			</tr>
			<tr id="<?php echo 'npc_item_neu_'.$count; ?>">
				<td>
					<?php
					if($items = get_items_titel())
					{
						?>
						<select id="<?php echo 'npc_item_auswahl_'.$count; ?>" name="<?php echo 'npc_item_auswahl_'.$count; ?>">
						<?php
						while($item = $items->fetch_array(MYSQLI_NUM))
						{
							echo "<option value='".$item[0]."' onFocus=\"set_button('NPCaendern',".$npc_id.");\">".$item[1]."</option>";
						}
						?>
						</select> 
					<?php
					} else {
						echo "Fehler beim Laden von Items.";
					}
					?>
				</td>
				<td>
					<input id="<?php echo 'npc_item_wkt_'.$count; ?>" type="input" name="<?php echo 'npc_item_wkt_'.$count; ?>" value="0" onFocus="set_button('NPCaendern',<?php echo $npc_id; ?>);">
				</td>
				<td>
					<input id="<?php echo 'npc_item_min_'.$count; ?>" type="input" name="<?php echo 'npc_item_min_'.$count; ?>" value="0" onFocus="set_button('NPCaendern',<?php echo $npc_id; ?>);">
				</td>
				<td>
					<input id="<?php echo 'npc_item_max_'.$count; ?>" type="input" name="<?php echo 'npc_item_max_'.$count; ?>" value="0" onFocus="set_button('NPCaendern',<?php echo $npc_id; ?>);">
				</td>
			</tr>
		</table>
		<br>
		<?php
	}
	
	
	function eingabemaskeItem($item)
	{
		if($item) $item_id = $item->id;
			else $item_id = 0;
		?>
		<table>
			<tr>
				<td colspan="2" align="left"><h2>Allgemeine Daten</h2></td>
			</tr>
			<tr>
				<td>Id</td>
				<td><input id="allg_info_eingabe" type="input" style="background-color:lightgrey;" name="item_id" value="<?php if($item) echo $item->id; ?>" readonly></td>
			</tr>
			<tr>
				<td>Bild</td>
				<td align="top">
					<?php
					if($bilder = get_bilder_titel("../Bilder/NPC/"))
					{
						?>
						<select name="item_bild">
						<?php
						while($bild = $bilder->fetch_array(MYSQLI_NUM))
						{
							if($item->bilder_id != $bild[0]){
								echo "<option value='".$bild[0]."' onFocus=\"set_button('ItemAendern',".$item->id.");\">".$bild[1]."</option>";
							} else {
								echo "<option value='".$bild[0]."' onFocus=\"set_button('ItemAendern',".$item->id.");\" selected>".$bild[1]."</option>";
							}
						}
						?>
						</select>
						<?php
					} else {
						echo "Fehler beim Laden von Bildern.";
					}
					?>
				</td>
			</tr>
			<tr>
				<td>Titel</td>
				<td><input id="allg_info_eingabe_text" type="input" name="item_titel" value="<?php if($item) echo $item->name; ?>" onFocus="set_button('ItemAendern',<?php echo $item_id; ?>);"></td>
			</tr>
			<tr>
				<td>Beschreibung</td>
				<td><textarea id="allg_info_eingabe_text" style="height:150px; width:200px;" name="item_beschreibung" onFocus="set_button('ItemAendern',<?php echo $item_id; ?>);"><?php if($item) echo $item->beschreibung; ?></textarea></td>
			</tr>
			<tr>
				<td>Slot</td>
				<td>
					<?php
					if($slots = get_slots_titel())
					{
						?>
						<select name="item_slot">
						<?php
						while($slot = $slots->fetch_array(MYSQLI_NUM))
						{
							if($item->slot->id != $slot[0]){
								echo "<option value='".$slot[0]."' onFocus=\"set_button('ItemAendern',".$item->id.");\">".$slot[1]."</option>";
							} else {
								echo "<option value='".$slot[0]."' onFocus=\"set_button('ItemAendern',".$item->id.");\" selected>".$slot[1]."</option>";
							}
						}
						?>
						</select>
					<?php
					} else {
						echo "Fehler beim Laden von Slots.";
					}
					?>
				</td>
			</tr>
			<tr>
				<td>Essbar</td>
				<td>
					<select name="item_essbar">
						<?php optionJaNein($item->id, $item->essbar); ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Ausrüstbar</td>
				<td>
					<select name="item_ausruestbar">
						<?php optionJaNein($item->id, $item->ausruestbar); ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Verarbeitbar</td>
				<td>
					<select name="item_verarbeitbar">
						<?php optionJaNein($item->id, $item->verarbeitbar); ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Gesundheit</td>
				<td><input id="allg_info_eingabe" type="input" name="item_gesundheit" value="<?php if($item) echo $item->gesundheit; ?>" onFocus="set_button('ItemAendern',<?php echo $item_id; ?>);"></td>
			</tr>
			<tr>
				<td>Energie</td>
				<td><input id="allg_info_eingabe" type="input" name="item_energie" value="<?php if($item) echo $item->energie; ?>" onFocus="set_button('ItemAendern',<?php echo $item_id; ?>);"></td>
			</tr>
			<tr>
				<td>Zauberpunkte</td>
				<td><input id="allg_info_eingabe" type="input" name="item_zauberpunkte" value="<?php if($item) echo $item->zauberpunkte; ?>" onFocus="set_button('ItemAendern',<?php echo $item_id; ?>);"></td>
			</tr>
			<tr>
				<td>Stärke</td>
				<td><input id="allg_info_eingabe" type="input" name="item_staerke" value="<?php if($item) echo $item->staerke; ?>" onFocus="set_button('ItemAendern',<?php echo $item_id; ?>);"></td>
			</tr>
			<tr>
				<td>Intelligenz</td>
				<td><input id="allg_info_eingabe" type="input" name="item_intelligenz" value="<?php if($item) echo $item->intelligenz; ?>" onFocus="set_button('ItemAendern',<?php echo $item_id; ?>);"></td>
			</tr>
			<tr>
				<td>Magie</td>
				<td><input id="allg_info_eingabe" type="input" name="item_magie" value="<?php if($item) echo $item->magie; ?>" onFocus="set_button('ItemAendern',<?php echo $item_id; ?>);"></td>
			</tr>
			<tr>
				<td>Element Feuer</td>
				<td><input id="allg_info_eingabe" type="input" name="item_element_feuer" value="<?php if($item) echo $item->element_feuer; ?>" onFocus="set_button('ItemAendern',<?php echo $item_id; ?>);"></td>
			</tr>
			<tr>
				<td>Element Wasser</td>
				<td><input id="allg_info_eingabe" type="input" name="item_element_wasser" value="<?php if($item) echo $item->element_wasser; ?>" onFocus="set_button('ItemAendern',<?php echo $item_id; ?>);"></td>
			</tr>
			<tr>
				<td>Element Erde</td>
				<td><input id="allg_info_eingabe" type="input" name="item_element_erde" value="<?php if($item) echo $item->element_erde; ?>" onFocus="set_button('ItemAendern',<?php echo $item_id; ?>);"></td>
			</tr>
			<tr>
				<td>Element Luft</td>
				<td><input id="allg_info_eingabe" type="input" name="item_element_luft" value="<?php if($item) echo $item->element_luft; ?>" onFocus="set_button('ItemAendern',<?php echo $item_id; ?>);"></td>
			</tr>
			<tr>
				<td>Initiative</td>
				<td><input id="allg_info_eingabe" type="input" name="item_initiative" value="<?php if($item) echo $item->initiative; ?>" onFocus="set_button('ItemAendern',<?php echo $item_id; ?>);"></td>
			</tr>
			<tr>
				<td>Abwehr</td>
				<td><input id="allg_info_eingabe" type="input" name="item_abwehr" value="<?php if($item) echo $item->abwehr; ?>" onFocus="set_button('ItemAendern',<?php echo $item_id; ?>);"></td>
			</tr>
			<tr>
				<td>Ausweichen</td>
				<td><input id="allg_info_eingabe" type="input" name="item_ausweichen" value="<?php if($item) echo $item->ausweichen; ?>" onFocus="set_button('ItemAendern',<?php echo $item_id; ?>);"></td>
			</tr>
			<tr>
				<td>In Prozent</td>
				<td>
					<select name="item_prozent">
						<?php optionJaNein($item->id, $item->prozent); ?>
					</select>
				</td>
			</tr>
		</table>
		<br>
		<?php
	}
	
	
	function daten_aus_post($daten_art)
	{
		switch($daten_art)
		{
			# NPC-Daten aus $_POST auslesen und in separates Array schreiben
			case "npc":
				$daten = array(
					"npc_id" => $_POST["npc_id"],
					"npc_bild" => $_POST["npc_bild"],
					"npc_element" => $_POST["npc_element"]);
				if($_POST["npc_titel"] != "") $daten["npc_titel"] = $_POST["npc_titel"];
				else $daten["npc_titel"] = "---ohne---";
				if($_POST["npc_familie"] != "") $daten["npc_familie"] = $_POST["npc_familie"];
				else $daten["npc_familie"] = "---ohne---";
				if($_POST["npc_staerke"] != "") $daten["npc_staerke"] = $_POST["npc_staerke"];
				else $daten["npc_staerke"] = "0";
				if($_POST["npc_intelligenz"] != "") $daten["npc_intelligenz"] = $_POST["npc_intelligenz"];
				else $daten["npc_intelligenz"] = "0";
				if($_POST["npc_magie"] != "") $daten["npc_magie"] = $_POST["npc_magie"];
				else $daten["npc_magie"] = "0";
				if($_POST["npc_feuer"] != "") $daten["npc_feuer"] = $_POST["npc_feuer"];
				else $daten["npc_feuer"] = "0";
				if($_POST["npc_wasser"] != "") $daten["npc_wasser"] = $_POST["npc_wasser"];
				else $daten["npc_wasser"] = "0";
				if($_POST["npc_erde"] != "") $daten["npc_erde"] = $_POST["npc_erde"];
				else $daten["npc_erde"] = "0";
				if($_POST["npc_luft"] != "") $daten["npc_luft"] = $_POST["npc_luft"];
				else $daten["npc_luft"] = "0";
				if($_POST["npc_gesundheit"] != "") $daten["npc_gesundheit"] = $_POST["npc_gesundheit"];
				else $daten["npc_gesundheit"] = "1";
				if($_POST["npc_energie"] != "") $daten["npc_energie"] = $_POST["npc_energie"];
				else $daten["npc_energie"] = "1";
				if($_POST["npc_zauberpunkte"] != "") $daten["npc_zauberpunkte"] = $_POST["npc_zauberpunkte"];
				else $daten["npc_zauberpunkte"] = "1";
				if($_POST["npc_initiative"] != "") $daten["npc_initiative"] = $_POST["npc_initiative"];
				else $daten["npc_initiative"] = "100";
				if($_POST["npc_abwehr"] != "") $daten["npc_abwehr"] = $_POST["npc_abwehr"];
				else $daten["npc_abwehr"] = "10";
				if($_POST["npc_ausweichen"] != "") $daten["npc_ausweichen"] = $_POST["npc_ausweichen"];
				else $daten["npc_ausweichen"] = "10";
				if($_POST["npc_beschreibung"] != "") $daten["npc_beschreibung"] = $_POST["npc_beschreibung"];
				else $daten["npc_beschreibung"] = "---ohne---";
				$daten["npc_typ"] = $_POST["npc_typ"];
				$daten["npc_ki"] = $_POST["npc_ki"];
				return $daten;
			
			# NPC-Gebiete und Wahrscheinlichkeiten aus $_POST auslesen und in separates Array schreiben
			case "npc_gebiete":
				$count = 0;
				$daten = array();
				$npc_id = $_POST["npc_id"];
				if(!$npc_id) $npc_id = get_npc_id_by_titel($_POST["npc_titel"]);
				while (array_key_exists("npc_gebiet_auswahl_".$count, $_POST)){
					$gebiet_id = $_POST["npc_gebiet_auswahl_".$count];
					$wkt = $_POST["npc_gebiet_wkt_".$count];
					$daten[$count] = array(
						"id" => get_npc_gebiet_id($npc_id, $gebiet_id),
						"npc_id" => $npc_id,
						"gebiet_id" => $gebiet_id,
						"wkt" => $wkt);
					$count++;
				}
				return $daten;
				
			# NPC-Items, Wahrscheinlichkeiten und Anzahl-Min/Max aus $_POST auslesen und in separates Array schreiben
			case "npc_items":
				$count = 0;
				$daten = array();
				$npc_id = $_POST["npc_id"];
				if(!$npc_id) $npc_id = get_npc_id_by_titel($_POST["npc_titel"]);
				while (array_key_exists("npc_item_auswahl_".$count, $_POST)){
					$items_id = $_POST["npc_item_auswahl_".$count];
					$wkt = $_POST["npc_item_wkt_".$count];
					$min = $_POST["npc_item_min_".$count];
					$max = $_POST["npc_item_max_".$count];
					$daten[$count] = array(
						"id" => get_npc_items_id($npc_id, $items_id),
						"npc_id" => $npc_id,
						"items_id" => $items_id,
						"wkt" => $wkt,
						"min" => $min,
						"max" => $max);
					$count++;
				}
				return $daten;
				
				
			####################
			# ToDo
			####################
			# Item-Daten aus $_POST auslesen und in Item-Objekt schreiben
			
			case "item":
				$item = new Item("neu");
				$item->id = $_POST["item_id"];
				$item->bilder_id = $_POST["item_bild"];
				if($_POST["item_titel"] != "") $item->name = $_POST["item_titel"];
					else $item->name = "---ohne---";
				if($_POST["item_beschreibung"] != "") $item->beschreibung = $_POST["item_beschreibung"];
					else $item->beschreibung = "---ohne---";
				if($_POST["item_essbar"] != "") $item->essbar = $_POST["item_essbar"];
					else $item->essbar = 0;
				if($_POST["item_ausruestbar"] != "") $item->ausruestbar = $_POST["item_ausruestbar"];
					else $item->ausruestbar = 0;
				if($_POST["item_verarbeitbar"] != "") $item->verarbeitbar = $_POST["item_verarbeitbar"];
					else $item->verarbeitbar = 0;
				if($_POST["item_gesundheit"] != "") $item->gesundheit = $_POST["item_gesundheit"];
					else $item->gesundheit = 0;
				if($_POST["item_energie"] != "") $item->energie = $_POST["item_energie"];
					else $item->energie = 0;
				if($_POST["item_zauberpunkte"] != "") $item->zauberpunkte = $_POST["item_zauberpunkte"];
					else $item->zauberpunkte = 0;
				if($_POST["item_staerke"] != "") $item->staerke = $_POST["item_staerke"];
					else $item->staerke = 0;
				if($_POST["item_intelligenz"] != "") $item->intelligenz = $_POST["item_intelligenz"];
					else $item->intelligenz = 0;
				if($_POST["item_magie"] != "") $item->magie = $_POST["item_magie"];
					else $item->magie = 0;
				if($_POST["item_element_feuer"] != "") $item->element_feuer = $_POST["item_element_feuer"];
					else $item->element_feuer = 0;
				if($_POST["item_element_wasser"] != "") $item->element_wasser = $_POST["item_element_wasser"];
					else $item->element_wasser = 0;
				if($_POST["item_element_erde"] != "") $item->element_erde = $_POST["item_element_erde"];
					else $item->element_erde = 0;
				if($_POST["item_element_luft"] != "") $item->element_luft = $_POST["item_element_luft"];
					else $item->element_luft = 0;
				if($_POST["item_initiative"] != "") $item->initiative = $_POST["item_initiative"];
					else $item->initiative = 0;
				if($_POST["item_abwehr"] != "") $item->abwehr = $_POST["item_abwehr"];
					else $item->abwehr = 0;
				if($_POST["item_ausweichen"] != "") $item->ausweichen = $_POST["item_ausweichen"];
					else $item->ausweichen = 0;
				if($_POST["item_prozent"] != "") $item->prozent = $_POST["item_prozent"];
					else $item->prozent = 0;
				$item->slot = get_slot($_POST["item_slot"]);
				return $item;
			
			# Keine Ahnung
			default:
				return false;
		}
	}
	
	
	function updateButton($topic, $id)
	{
		?>
		<input type="button" name="update" value="Hinzufügen/Ändern" style="float:left;" onclick="set_button_submit('<?php echo $topic ?>','<?php echo $id ?>');">
		<?php
	}
	
	
	function optionJaNein($obj_id, $aktueller_wert)
	{
		if($aktueller_wert == 0){
			echo "<option value=0 onFocus=\"set_button('ItemAendern',".$obj_id.");\" selected>nein</option>";
			echo "<option value=1 onFocus=\"set_button('ItemAendern',".$obj_id.");\">ja</option>";
		} else {
			echo "<option value=0 onFocus=\"set_button('ItemAendern',".$obj_id.");\">nein</option>";
			echo "<option value=1 onFocus=\"set_button('ItemAendern',".$obj_id.");\" selected>ja</option>";
		}
	}
?>

<?php
	close_connection($connect_db_dvg);
?>