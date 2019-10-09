<div id="divzauber" align="left" > <!-- style="background-color:darkred;" -->
	<?php

#######################################
# Initialisierung Variablen für Kampf #
#######################################
	$kampf = select_kampf($aktion_spieler->any_id_1);
	$neue_aktion = false;
	$kampf_anzeigen = false;
	$kampf_vorbei = false;
	
	if ($aktion_spieler->titel == "kampf"){
		$kt_0 = get_all_kampf_teilnehmer($kampf->id, 0);
		$kt_1 = get_all_kampf_teilnehmer($kampf->id, 1);
		$kt_all = array_merge($kt_0, $kt_1);
		# Kampf vorbei?
		if (ist_kampf_beendet($kt_all) < 2) $kampf_vorbei = true;
	}
	
	# Kampf laufend?
	if ($aktion_spieler->titel == "kampf" OR isset($_POST["button_kampf"])) $im_kampf = true;
		else $im_kampf = false;
	

########################################################################
# Ausführung von Angriffen / Zaubern -> Aktualisierung Kampfteilnehmer #
########################################################################

	if ($im_kampf AND !$kampf_vorbei){
		# Spieleraktion verarbeiten
		if (isset($_POST["kt_id_ziel_value"]) AND $_POST["kt_id_ziel_value"] > 0){
			$kampf->log = "<br>" . $kampf->log;
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
			$temp = insert_kampf_aktion($kampf->id, $kt_zaubert, $kt_ziel, $zauber);
			$kampf->log = $temp[2] . "<br>" . $kampf->log;
			# Wenn kein Fehler dann neue Aktion und nachfolgende Kampfrunden (NPCs)
			if ($temp AND !$temp[1]){
				$neue_aktion = true;
			}
		}
		
		if ($neue_aktion){
			# Positive Effekte für Spieler ausführen
			kampf_effekte_ausführen($kt_zaubert, "verteidigung");
			
			# Kampfeffekte für alle NPC ausführen, falls neue Aktionen für diese vorhanden und Effekt als "ausgeführt" kennzeichnen.
			foreach ($kt_all as $kt){
				if ($kt_zaubert->kt_id != $kt->kt_id AND !$kt->ist_tot()){
					kampf_effekte_ausführen($kt, "angriff", false);
				}
			}
			
			# Nächsten Kampfteilnehmer bestimmen
			while ($kt = naechster_kt($kt_all) AND $kt->seite != 0){
				# Negative Effekte für Nicht-Spieler ausführen
				# Alle Kampfeffekte ausführen, die noch nicht "ausgeführt" wurden und anschließend bei allen Kampfeffekten zum KT "ausgeführt" wieder auf false setzen.
				kampf_effekte_ausführen($kt, "angriff");
				
				if ($kt->gesundheit > 0){
					# KI ausführen
					if ($alle_zauber = get_zauber_von_objekt($kt)){
						$zauber_und_ziel = ki_ausfuehren($kt, $alle_zauber);
					} else {
						$zauber_und_ziel = false;
						echo $kt->name." wurden keine verfügbaren Angriffe/Zauber zugewiesen oder es wurden keine gefunden.<br>";
					}
					# Mit ermitteltem Angriff/Zauber und Ziel Kampfaktion hinzufügen
					if ($zauber_und_ziel){
						$temp = insert_kampf_aktion($kampf->id, $kt, $zauber_und_ziel[1], $zauber_und_ziel[0]);
						$kampf->log = $temp[2] . "<br>" . $kampf->log;
					}
					# Wenn keine Aktion durchgeführt dann Abbruch
					if ($temp == null OR $temp[1]) break;
					
					# Positive Effekte für Nicht-Spieler ausführen
					kampf_effekte_ausführen($kt, "verteidigung");
				}
			}
			
			# Negative Effekte für Spieler ausführen
			kampf_effekte_ausführen($kt, "angriff");
			
			# Kampf vorbei?
			$gewinner_seite = ist_kampf_beendet($kt_all);
			switch($gewinner_seite){
				case 0:
					$kampf_vorbei = true;
					$kampf->log = "<font color='green'>Gewonnen</font><br>" . $kampf->log;
					break;
				case 1:
					$kampf_vorbei = true;
					$kampf->log = "<font color='red'>Verloren</font><br>" . $kampf->log;
					break;
				default:
					break;
			}
			
			# Aktualisierte Daten der Kampfteilnehmer in Datenbank zurückschreiben
			update_kampf_teilnehmer($kt_all);
		}
	}

################################
# Anzeige/Aufbau Kampfumgebung #
################################
	
	if ($im_kampf){ #  AND !$kampf_vorbei
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
							<table style="border-collapse:collapse;">
								<tr>
									<!-- Spielerbild -->
									<td><img align="left" src="<?php echo get_bild_zu_id($kt->bilder_id);?>" style="max-height:150px; width:auto;" alt="<?php echo $kt->name;?>"/></td>
									<!-- Aktive Zauber auf Spieler -->
									<td valign="top">
										<table style="border-collapse:collapse;">
											<?php
											$alle_aktiven_zauber = get_zauber_aktiv($kt);
											if ($alle_aktiven_zauber){
												foreach ($alle_aktiven_zauber as $zauber){
													?>
													<tr>
														<td>
															<span title="
																<?php 
																	echo $zauber->titel.'&#10;';
																	foreach ($zauber->zaubereffekte as $eff){
																		echo $eff->wert.' '.anzeige_attribut($eff->attribut).' noch '.($eff->runden_max-$eff->runden).' Runden';
																		if ($eff->jede_runde == 0) echo ' (temporär)';
																		echo '&#10;';
																	}
																?>">
																<img style="max-height:30px; width:auto;"
																	src="<?php echo get_bild_zu_id($zauber->bilder_id) ?>" 
																	alt="<?php echo $zauber->titel ?>" 
																	<?php
																	if($zauber->zaubereffekte[0]->art == "angriff"){
																		echo "style='border:1px red solid;'";
																	} else {
																		echo "style='border:1px green solid;'";
																	}?>/>
															</span>
														</td>
													</tr>
													<?php
												}
											}
											?>
										</table>
									</td>
								</tr>
								<tr>
									<td align="left" style="font-size:14pt;"><?php echo $kt->name;?></td>
								</tr>
								<tr>
									<td>
										<?php $kt->ausgabe_kampf($kampf_detail); ?>
									</td>
								</tr>
							</table>
						</div>
						<table style="border-collapse:collapse;" width="100%">
							<tr>
								<td align="left" style="border-bottom:1px solid white; height:70px; padding-top:5px; padding-left:5px;">
									<?php
									if ($alle_zauber = get_zauber_von_objekt($kt)){
										foreach ($alle_zauber as $zauber){
											$inaktiv = ($kt->zauberpunkte < berechne_zauberpunkte_verbrauch($zauber))
											?>
											<span title="<?php echo $zauber->titel ?>">
												<img id="<?php echo "zauber_img_#".$count ?>" 
													src="<?php echo get_bild_zu_id($zauber->bilder_id) ?>" 
													alt="<?php echo $zauber->titel ?>" 
													<?php
													if($inaktiv){
														echo "style='border:1px red solid;'";
													} else {
														echo "style='border:1px green solid;'";
													}?>/>
											</span>
											<?php 
											if(!$inaktiv){ 
												?>
												<div id="<?php echo "zauber_div_#".$count ?>" onmousedown="dragstart(this)" class="zauberdiv" style="background:url(<?php echo get_bild_zu_id($zauber->bilder_id) ?>);background-size: 60px 60px;width:60px;height:60px;" zauber_id="<?php echo $zauber->id ?>" kt_id="<?php echo $kt->kt_id ?>">
												</div>
											<?php
											}
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
							<table style="border-collapse:collapse;">
								<tr>
									<!-- NPC-Bild -->
									<td><img align="left" src="<?php echo get_bild_zu_id($kt->bilder_id);?>" style="max-height:150px; width:auto;" alt="<?php echo $kt->name;?>"/></td>
									<!-- Aktive Zauber auf Spieler -->
									<td valign="top">
										<table style="border-collapse:collapse;">
											<?php
											$alle_aktiven_zauber = get_zauber_aktiv($kt);
											if ($alle_aktiven_zauber){
												foreach ($alle_aktiven_zauber as $zauber){
													?>
													<tr>
														<td>
															<span title="
																<?php 
																	echo $zauber->titel.'&#10;';
																	foreach ($zauber->zaubereffekte as $eff){
																		echo $eff->wert.' '.anzeige_attribut($eff->attribut).' noch '.($eff->runden_max-$eff->runden).' Runden';
																		if ($eff->jede_runde == 0) echo ' (temporär)';
																		echo '&#10;';
																	}
																?>">
																<img style="max-height:30px; width:auto;"
																	src="<?php echo get_bild_zu_id($zauber->bilder_id) ?>" 
																	alt="<?php echo $zauber->titel ?>" 
																	<?php
																	if($zauber->zaubereffekte[0]->art == "angriff"){
																		echo "style='border:1px red solid;'";
																	} else {
																		echo "style='border:1px green solid;'";
																	}?>/>
															</span>
														</td>
													</tr>
													<?php
												}
											}
											?>
										</table>
									</td>
								</tr>
								<tr>
									<td><p align="left" style="font-size:14pt;"><?php echo $kt->name;?></p></td>
								</tr>
								<tr>
									<td>
										<?php $kt->ausgabe_kampf($kampf_detail); ?>
									</td>
								</tr>
							</table>
						</div>
						<table style="border-collapse:collapse;" width="100%">
							<tr>
								<td align="left" style="border-bottom:1px solid white; height:70px; padding-top:5px; padding-left:5px;">
									<?php
									if ($anzeige_npc_zauber AND $alle_zauber = get_zauber_von_objekt($kt)){
										foreach ($alle_zauber as $zauber){
											$inaktiv = ($kt->zauberpunkte < berechne_zauberpunkte_verbrauch($zauber))
											?>
											<span title="<?php echo $zauber->titel ?>">
												<img id="<?php echo "zauber_img_#".$count ?>" 
													src="<?php echo get_bild_zu_id($zauber->bilder_id) ?>" 
													alt="<?php echo $zauber->titel ?>" 
													<?php
													if($inaktiv){
														echo "style='border:1px red solid;'";
													} else {
														echo "style='border:1px green solid;'";
													}?>/>
											</span>
											<?php 
											if(!$inaktiv){ 
												?>
												<div id="<?php echo "zauber_div_##".$count ?>" onmousedown="dragstart(this)" class="zauberdiv" style="background:url(<?php echo get_bild_zu_id($zauber->bilder_id) ?>);background-size: 60px 60px;width:60px;height:60px;" zauber_id="<?php echo $zauber->id ?>" kt_id="<?php echo $kt->kt_id ?>">
												</div>
											<?php
											}
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
		
		<input type="submit" style="visibility: hidden;">
		<input type="hidden" id="kt_id_value" name="kt_id_value">
		<input type="hidden" id="kt_id_ziel_value" name="kt_id_ziel_value">
		<input type="hidden" id="zauber_id_value" name="zauber_id_value">
		
		<script type="text/javascript"> 
			var x = document.getElementsByClassName("zauberdiv");
			var i, j, f, rahmen_top=1, rahmen_left=1;
			for (i = 0; i < x.length; i++) {
				if (x[i].id.split("#")[1] != ""){
					j = x[i].id.split("#")[1];
				} else {
					j = x[i].id.split("#")[2];
					rahmen_top = 1;
					rahmen_left = 2;
				}
				f = getPosition(document.getElementById("zauber_img_#"+j));
				x[i].style.top = f[0]+rahmen_top;
				x[i].style.left = f[1]+rahmen_left;
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
<div id="kampf_log">
	<p align="left" style="padding-top:5pt;">
		<?php
		update_kampf($kampf);
		echo $kampf->log;
		?>
	</p>
</div>
<div id="kampf_menue">
	<p align="center" style="padding-top:5pt;">
		<?php
		/* ToDo: Auswertung ob Kampf vorbei ist */
		if ($kampf_vorbei){
			?>
			<input type="submit" name="aktion_abgeschlossen" value="Kampf beenden" style="width: 200px;"/>
			<?php
		}
		?>
		<p align="center" style="padding-top:10pt;">
			<input type="submit" name="zurueck" value="zurück">
		</p>
	</p>
</div>



