<div id="divzauber" align="left" > <!-- style="background-color:darkred;" -->
	<?php

#######################################
# Initialisierung Variablen für Kampf #
#######################################
	$kampf_id = $aktion_spieler->any_id_1;
	$neue_aktion = false;
	$kampf_anzeigen = false;
	
	if ($aktion_spieler->titel == "kampf"){
		$kt_0 = get_all_kampf_teilnehmer($kampf_id, 0);
		$kt_1 = get_all_kampf_teilnehmer($kampf_id, 1);
		$kt_all = array_merge($kt_0, $kt_1);
	}
	
	/* Kampf laufend? */
	if ($aktion_spieler->titel == "kampf" OR isset($_POST["button_kampf"])) $im_kampf = true;
		else $im_kampf = false;
	
	/* Kampf vorbei? */
	if (ist_kampf_beendet($kt_all) < 2) $kampf_vorbei = true;
		else $kampf_vorbei = false;

########################################################################
# Ausführung von Angriffen / Zaubern -> Aktualisierung Kampfteilnehmer #
########################################################################

	if ($im_kampf){
		# Spieleraktion verarbeiten
		if (isset($_POST["kt_id_ziel_value"]) AND $_POST["kt_id_ziel_value"] > 0){
			$kt_zaubert = false;
			$kt_ziel = false;
			$zauber = get_zauber($_POST["zauber_id_value"]);
			foreach ($kt_all as $kt){
				if ($kt->kt_id == $_POST["kt_id_value"]){
					$kt_zaubert = $kt;
				}
				if ($kt->kt_id == $_POST["kt_id_ziel_value"]){
					$kt_ziel = $kt;
				}
			}
			$temp = insert_kampf_aktion($kampf_id, $kt_zaubert, $kt_ziel, $zauber);
			# Wenn kein Fehler dann neue Aktion und nachfolgende Kampfrunden (NPCs)
			if ($temp AND !$temp[1]){
				$neue_aktion = true;
			}
		}
		
		if ($neue_aktion){
			# Positive Effekte für Spieler ausführen
			kampf_effekte_ausführen($kt_zaubert, "verteidigung");
			
			# Nächsten Kampfteilnehmer bestimmen
			while ($kt = naechster_kt($kt_all) AND $kt->seite != 0){
				# Negative Effekte für Nicht-Spieler ausführen
				kampf_effekte_ausführen($kt, "angriff");
				
				##############################################
				########## HIER MUSS DIE NPC-KI HIN ##########
				##############################################
				$temp = insert_kampf_aktion($kampf_id, $kt, $kt_zaubert, get_zauber(77));
				if ($temp == null OR $temp[1]) break;
			
				# Positive Effekte für Nicht-Spieler ausführen
				kampf_effekte_ausführen($kt, "verteidigung");
			}
			
			# Negative Effekte für Spieler ausführen
			kampf_effekte_ausführen($kt, "angriff");
			
			# Kampf vorbei?
			if (ist_kampf_beendet($kt_all) < 2) $kampf_vorbei = true;
				else $kampf_vorbei = false;
			
			# Aktualisierte Daten der Kampfteilnehmer in Datenbank zurückschreiben
			update_kampf_teilnehmer($kt_all);
		}
	}

################################
# Anzeige/Aufbau Kampfumgebung #
################################
	
	if ($im_kampf AND !$kampf_vorbei){
		$count=0;
		$counter_0=0;
		$counter_1=0;
		?>
		<table id="kampf_arena" width="100%" style="border-collapse:collapse;">
			<colgroup>
				<col width="50%">
				<col width="50%">
			</colgroup>
			<tr>
				<td valign="top" style="border-right:1px solid white;">
					<?php
					foreach ($kt_0 as $kt){
						?>
						<div id="<?php echo "kt_div_0_".$counter_0 ?>" width="100%" height="100%" style="background-color:darkgreen;" kt_id="<?php echo $kt->kt_id;?>">
							<table style="border-collapse:collapse;" width="100%">
								<tr>
									<td><img align="left" src="<?php echo get_bild_zu_id($kt->bilder_id);?>" style="max-height:150px; width:auto;" alt="<?php echo $kt->name;?>"/></td>
								</tr>
								<tr>
									<td align="left" style="font-size:14pt;"><?php echo $kt->name;?></td>
								</tr>
								<tr>
									<td>
										<?php $kt->ausgabe_kampf(); ?>
									</td>
								</tr>
							</table>
						</div>
						<table style="border-collapse:collapse;" width="100%">
							<tr>
								<td align="left" style="border-bottom:1px solid white; height:70px;">
									<?php
									if ($alle_zauber = get_zauber_von_kampfteilnehmer($kt)){
										foreach ($alle_zauber as $zauber){
											?>
											<span title="<?php echo $zauber->titel ?>" >
												<img id="<?php echo "elemente_top_".$count ?>" src="<?php echo get_bild_zu_id($zauber->bilder_id) ?>" alt="<?php echo $zauber->titel ?>" />
											</span>
											<div onmousedown="dragstart(this)" class="zauberdiv" style="background:url(<?php echo get_bild_zu_id($zauber->bilder_id) ?>);background-size: 60px 60px;width:60px;height:60px;" zauber_id="<?php echo $zauber->id ?>" kt_id="<?php echo $kt->kt_id ?>">
											</div>
											<?php
											$count+=1;
										}
									}
									?>
								</td>
							</tr>
						</table>
						<?php
						$counter_0+=1;
					}
					?>
				</td>
				<td valign="top" style="border-left:1px solid white;">
					<?php
					foreach ($kt_1 as $kt){
						?>
						<div id="<?php echo "kt_div_1_".$counter_1 ?>" width="100%" height="100%" style="background-color:darkred;" kt_id="<?php echo $kt->kt_id;?>">
							<table style="border-collapse:collapse;" width="100%">
								<tr>
									<td><img align="left" src="<?php echo get_bild_zu_id($kt->bilder_id);?>" style="max-height:150px; width:auto;" alt="<?php echo $kt->name;?>"/></td>
								</tr>
								<tr>
									<td><p align="left" style="font-size:14pt;"><?php echo $kt->name;?></p></td>
								</tr>
								<tr>
									<td>
										<?php $kt->ausgabe_kampf(); ?>
									</td>
								</tr>
							</table>
						</div>
						<table style="border-collapse:collapse;" width="100%">
							<tr>
								<td align="left" style="border-bottom:1px solid white; height:70px;">
									<?php
									if ($alle_zauber = get_zauber_von_kampfteilnehmer($kt)){
										foreach ($alle_zauber as $zauber){
											?>
											<span title="<?php echo $zauber->titel ?>" >
												<img id="<?php echo "elemente_top_".$count ?>" src="<?php echo get_bild_zu_id($zauber->bilder_id) ?>" alt="<?php echo $zauber->titel ?>" />
											</span>
											<div onmousedown="dragstart(this)" class="zauberdiv" style="background:url(<?php echo get_bild_zu_id($zauber->bilder_id) ?>);background-size: 60px 60px;width:60px;height:60px;" zauber_id="<?php echo $zauber->id ?>" kt_id="<?php echo $kt->kt_id ?>">
											</div>
											<?php
											$count+=1;
										}
									}
									?>
								</td>
							</tr>
						</table>
						<?php
						$counter_1+=1;
					}
					?>
				</td>
			</tr>
		</table>
		
		<script type="text/javascript"> 
			var x = document.getElementsByClassName("zauberdiv");
			var i, f;
			for (i = 0; i < x.length; i++) {
			  f = getPosition("elemente_top_"+i);
			  x[i].style.top = f[0];
			  x[i].style.left = f[1];
			  x[i].style.display = "block";
			}
		</script>
		<?php
	} else {
		?>
		<p align="center" style="margin-top:20px; margin-bottom:0px; font-size:14pt;">
			Derzeit befindet Ihr Euch in keinem Kampf!<br>
		</p>
		<?php
	}
	?>	
</div>
<?php

######################################
# Abschließende Aktionsmöglichkeiten #
######################################

?>
<div id="kampf_log" style="width:100%;height:10%;">
	<p align="center" style="padding-top:5pt;">
		<?php
		/* ToDo: Auswertung ob Kampf vorbei ist */
		if ($kampf_vorbei){
			?>
			<input type="submit" name="aktion_abgeschlossen" value="Kampf beenden" style="width: 200px;"/>
			<?php
		}
	?>
	</p>
	<input type="submit" style="visibility: hidden;">
	<input type="hidden" id="kt_id_value" name="kt_id_value">
	<input type="hidden" id="kt_id_ziel_value" name="kt_id_ziel_value">
	<input type="hidden" id="zauber_id_value" name="zauber_id_value">
</div>



