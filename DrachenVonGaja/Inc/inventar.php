<?php  $nutze_item = explode("_", $_POST["button_inventar"]); $nutzung = $nutze_item[0]; if(count($nutze_item) == 2) $nutzung_item_id = $nutze_item[1];	else $nutzung_item_id = 0;if($aktion_spieler->titel == "kampf"){	?>	<p align="center" style="margin-top:20px; margin-bottom:0px; font-size:14pt;">		Ihr seid in einem Kampf und könnt bis zu seinem Ende keine Items benutzen!<br />	</p>	<?php} else {	# Item wurde verspeist	if($nutzung == "essen"){		if(insert_items_spieler($spieler->id, $nutzung_item_id, -1)){			if($benutztes_item = get_item($nutzung_item_id)){				if($benutztes_item->prozent == 0){					$spieler->erholung_punkte($benutztes_item->gesundheit, $benutztes_item->energie, $benutztes_item->zauberpunkte);				} else {					$spieler->erholung_prozent($benutztes_item->gesundheit, $benutztes_item->energie, $benutztes_item->zauberpunkte);				}			}		} else {			echo "Das hätte nicht passieren dürfen.<br />";		}	}	# Item wurde angelegt/abgelegt	if($nutzung == "ausruesten"){		# Hier fehlt noch die Funktion	}}# Anzeige Tabelle mit vorhandenen Items des Spielersif ($items = get_all_items_spieler($spieler->id)){	?>	<table border="1px" border-color="white" align="center" style="margin-top:10%;" width="700px" >		<tr>			<td>Item</td>			<td>Bild</td>			<td>Beschreibung</td>			<td>Platz</td>			<td>Anzahl</td>			<td>Verwendung</td>		</tr>		<?php		foreach ($items as $item){			?>			<tr>				<td align="left"><?php echo $item->name ?></td>				<td align="center"><img src="<?php echo get_bild_zu_id($item->bilder_id) ?>" width="75px" alt=""/></td>				<td align="left"><?php echo $item->beschreibung ?></td>				<td align="left" style="min-width:80px;"><?php echo $item->slot->name ?></td>				<td align="right"><?php echo $item->anzahl ?></td>				<td align="center">					<?php 					if($item->essbar){						?> 						<button type="submit" alt="Essen" name="button_inventar" value="<?php echo "essen_".$item->id ?>">Essen</button><br />						<?php					}					if($item->ausruestbar){						?> 						<button type="submit" alt="Ausruesten" name="button_inventar" value="<?php echo "ausruesten_".$item->id ?>">Anlegen/Ablegen</button><br />						<?php					}					if($item->verarbeitbar) /*Toll, aber das nützt mir nur was im Handwerksfenster -> Hier keine Anzeige*/;					?>				</td>			</tr>			<?php		}		if(!isset($items[0])){			?>			<tr>				<td colspan=4>Keine Items gefunden.</td>			</tr>			<?php		}		?>	</table>	<p align="center" style="padding-top:10pt;">		<input type="submit" name="zurueck" value="zurück">	</p>	<?php} else {	echo "<br />\nEs sind noch keine Items im Rucksack vorhanden.<br />\n";}?>