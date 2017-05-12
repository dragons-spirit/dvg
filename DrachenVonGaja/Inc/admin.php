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
		$ergebnis = get_anmeldung($_SESSION['login_name']);
		if (!$ergebnis or $ergebnis[5] != "Admin" or isset($_POST["button_zur_spielerauswahl"]))
		{
			?>
			<script type="text/javascript">
				window.location.href = "../index.php";
			</script>
			<?php
		} else {
	### Hier beginnt der eigentliche Seitenaufbau ###		
			?>
			<div id="zur_spielerauswahl">
				<input type="submit" name="button_zur_spielerauswahl" value="Zurück zur Spielerauswahl">
			</div>
			
			<div id="rahmen">
				<?php
				$buttonThemaGedrueckt = false;
				if(isset($_POST["button_BilderLaden"]) or isset($_POST["button_ItemsAnlegen"]) or isset($_POST["button_NPCsAnlegen"]))
					$buttonThemaGedrueckt = true;
			
				if($buttonThemaGedrueckt)
				{
					# Bilder laden
					if(isset($_POST["button_BilderLaden"]))
					{
						$ordner = "../Bilder/NPC"; # Standardordner für neue Bilder
						$endungen_bilder = array('jpg','jpeg','bmp','png','gif','ico','tiff'); # erkannte Dateiendungen
						$alle_dateien = scandir($ordner); # array für alle Dateien im Ordner
						$neue_dateien = array(); # array für alle neuen Dateien im Ordner
						foreach ($alle_dateien as $datei)
						{
							$dateiinfo = pathinfo($ordner."/".$datei); # Dateiinfos holen
							$titel = ucwords(utf8_encode($dateiinfo['filename'])); # ersten Bildtitel aus Dateiname erzeugen
							$pfad = utf8_encode($dateiinfo['dirname'])."/".utf8_encode($dateiinfo['basename']); # kompletter Dateipfad für Datenbank
							if(array_key_exists('extension', $dateiinfo))
								$endung = $dateiinfo['extension']; # Dateiendung der aktuellen Datei
							else $endung = 'none';
							
							# Datei merken, wenn Dateipfad noch nicht in DB bekannt und Endung einer Bilddatei entspricht
							if (!check_bild_vorhanden($pfad) && in_array($endung, $endungen_bilder))
							{
								$neue_dateien[] = array('titel' => $titel, 'pfad' => $pfad);
							}
						}
						?>
						<table>
							<caption style="font-size:x-large;">Neue Bilder zum einfügen in die Datenbank</caption>
							<tr align="left" style="margin:5px;">
								<th>Einfügen?</th>
								<th>Lfd Nr</th>
								<th>Titel</th>
								<th>Beschreibung</th>
								<th>Pfad</th>
							</tr>
						<?php
						$counter = 0;
						foreach ($neue_dateien as $ds)
						{
							$counter = $counter + 1;
							?>
							<tr>
								<td><input type="checkbox" name="<?php echo 'select_'.$counter ?>"></td>
								<td><?php echo $counter ?></td>
								<td><input type="text" name="<?php echo 'titel_'.$counter ?>" value="<?php echo $ds['titel']; ?>"></td>
								<td><input type="text" name="<?php echo 'beschreibung_'.$counter ?>" style="width:750px;"></td>
								<td><input type="text" name="<?php echo 'pfad_'.$counter ?>" value="<?php echo $ds['pfad']; ?>" style="width:300px;"></td>
							</tr>
							<?php							
						}
						?>
						</table>
						<br>
						<?php
						zeigeZurueckButton();
						zeigeFertigButton();
					}
				
					# Items anlegen
					if(isset($_POST["button_ItemsAnlegen"]))
					{
						echo "# Lege neue Items an"; 
						zeigeZurueckButton();
					}
					
					# NPCs anlegen
					if(isset($_POST["button_NPCsAnlegen"]))
					{
						echo "# Lege neue NPCs an"; 
						zeigeZurueckButton();
					}
				}
			
				if(isset($_POST['button_fertig']))
				{
					$counter = 1;
					$bilderdaten = array();
					while(array_key_exists('titel_'.$counter, $_POST))
					{
						if(array_key_exists('select_'.$counter, $_POST))
						{	
							$bilderdaten[] = array(
								'titel' => $_POST['titel_'.$counter],
								'beschreibung' => $_POST['beschreibung_'.$counter],
								'pfad' => $_POST['pfad_'.$counter]);
						}
						$counter = $counter + 1;
					}
					echo insert_bilder($bilderdaten);
					echo "<br>";
					print_r($bilderdaten);
					echo "<br>";
					zeigeZurueckButton();
					
					
					
					
					
				}
				
				if(!$buttonThemaGedrueckt && !isset($_POST['button_fertig']))
				{
					?>
					<div><input type="submit" name="button_BilderLaden" value="Neue Bilder laden"></div>
					<div style="padding-top:5px;"><input type="submit" name="button_ItemsAnlegen" value="Neue Items anlegen"> (ohne Funktion)</div>
					<div style="padding-top:5px;"><input type="submit" name="button_NPCsAnlegen" value="Neue NPCs anlegen"> (ohne Funktion)</div>
					<?php
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
	function zeigeZurueckButton($ausrichtung = "left")
	{
		echo "<input type='submit' name='zurueck' value='zurück' style='float:" . $ausrichtung . ";'>";
	}
	
	function zeigeFertigButton($ausrichtung = "right")
	{
		echo "<input type='submit' name='button_fertig' value='Fertig' style='float:" . $ausrichtung . ";'>";
	}	
?>