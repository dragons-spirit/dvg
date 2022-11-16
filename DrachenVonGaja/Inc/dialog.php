<div id="dialog" style="font-size:14pt;">
    <?php
	# Dialog initinalisieren
	$dialog = new Dialog();
	if (isset($_POST["button_dialog_start"])){
		$npc_id = $_POST["button_dialog_start"];
		# echo "Button 'Neuer Dialog' gedrückt. Dialog mit NPC (id = ".$npc_id.") soll gestartet werden.<br />";
		$dialog->neu($spieler->id, $npc_id);
	}
	if (isset($_POST["button_dialog_weiter"])){
		$dialog_spieler_id = $_POST["button_dialog_weiter"];
		# echo "Button 'Dialog fortsetzen' gedrückt. Dialogoption (id = ".$dialog_spieler_id.") wurde gewählt.<br />";
		$dialog->fortsetzen($spieler->id, $dialog_spieler_id);
	}
	
	$hintergrundbild_anzeigen = true;
	if (isset($dialog->aktion_offen)){
		switch ($dialog->aktion_offen[0]){
			case "kaempfen":
				$npc_id = $dialog->aktion_offen[1];
				$npc = get_npc($npc_id);
				?>
				<p align="center" style="margin-top:10%; margin-bottom:0px; font-size:14pt;">
					<?php echo "Ihr macht euch zum Kampf gegen " . $npc->name . " bereit."; ?>
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
				insert_kampf_teilnehmer($kampf_id, $spieler->id, "spieler", 1);
				#insert_kampf_teilnehmer($kampf_id, $npc_id, "npc", 1);
				$hintergrundbild_anzeigen = false;
				break;
			case "handeln":
				$npc_id = $dialog->aktion_offen[1];
				$npc = get_npc($npc_id);
				?>
				<p align="center" style="margin-top:10%; margin-bottom:0px; font-size:14pt;">
					<?php echo "Irgendwann könnte man hier mit " . $npc->name . " handeln."; ?>
				</p>
				<br /><br />
				<p align="center" style="padding-top:10pt;">
					<button class="button_standard" type="submit" name="zurück" value="zurück">zurück</button>
				</p>
				<?php
				$hintergrundbild_anzeigen = false;
				break;
			case "quest_start":
				$any_id = $dialog->aktion_offen[1];
				?>
				<p align="center" style="margin-top:10%; margin-bottom:0px; font-size:14pt;">
					<?php echo "Eine Quest mit id = " . $any_id . " soll hier GESTARTET werden."; ?>
				</p>
				<?php
				break;
			case "quest_ende":
				$any_id = $dialog->aktion_offen[1];
				?>
				<p align="center" style="margin-top:10%; margin-bottom:0px; font-size:14pt;">
					<?php echo "Eine Quest mit id = " . $any_id . " soll hier BEENDET werden."; ?>
				</p>
				<?php
				break;
			default:
				break;
		}
	}
	
	if ($dialog->id != null){
		$npc = get_npc($dialog->npc_id);
		?>
		<h1 align="center"><?php echo $npc->name ?></h1>
		<img src="<?php echo get_bild_zu_id($npc->bilder_id) ?>" style="max-height:100px; max-width:200px;" alt=""/>
		<!--<?php var_dump($dialog); echo "<br />"; ?>-->
		<p align="center"><?php if($debug) echo "(".$dialog->npc_text_aktuell_id.") ".$dialog->npc_text_aktuell; else echo $dialog->npc_text_aktuell; ?></p>
		<?php
		if (is_array($dialog->spieler_texte)){
			foreach ($dialog->spieler_texte as $spieler_text){
				if($debug) $button_text = "(".$spieler_text->id.") ".$spieler_text->text;
					else $button_text = $spieler_text->text;
				if ($spieler_text->aktion_id != null){
					$button_text = $button_text." [".$spieler_text->aktion_text."]";
				}
				?>
				<p>
				<button class="button_standard" type="submit" name="button_dialog_weiter" value="<?php echo $spieler_text->id ?>"><?php echo $button_text ?></button>
				</p>
				<?php
			}
		}
		?>
		<br /><br />
		<p align="center" style="padding-top:10pt;">
			<button class="button_standard" type="submit" name="zurück" value="zurück">zurück</button>
		</p>
		<?php
	} else {
		$dialog = null;
		if ($hintergrundbild_anzeigen){
			zeige_hintergrundbild($spieler->gebiet_id);
		}
	}
	?>
</div>




