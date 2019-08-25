<div id="divzauber" align="left" > <!-- style="background-color:darkred;" -->
	<?php

#######################################
# Initialisierung Variablen für Kampf #
#######################################

	/* Kampf laufend? */
	if ($aktion_spieler->titel == "kampf") $im_kampf = true;
		else $im_kampf = false;
	
	/* Kampf vorbei? */
	if (1==1) $kampf_vorbei = true;
		else $kampf_vorbei = false;

################################
# Anzeige/Aufbau Kampfumgebung #
################################
	
	if ($im_kampf){
		?>
		<table>
			<tr>
				<td colspan="2"><img src="<?php echo get_bild_zu_id($spieler->bilder_id); ?>" width="400px" alt="<?php echo $spieler->name; ?>"/></td>
			</tr>
			<tr>
				<td colspan="2"><p align="center" style="font-size:14pt"><?php echo get_gattung_titel($spieler->gattung_id) . " " . $spieler->name;?></p></td>
			</tr>
			<tr><td><br/>    </td></tr>
		</table>
		
		<?php
		
		$count=0;
		$zauber = get_zauber_von_spieler($spieler->spieler_id);
		
		while($row = $zauber->fetch_array(MYSQLI_NUM))
		{
			$zauber_id = $row[0];
			$zauber_titel = $row[2];
			$zauber_beschreibung = $row[3];
			$zauber_bilder_id = $row[1];
			?>
			
			<span title="<?php echo $zauber_titel ?>" >
				<img id="<?php echo "elemente_top_".$count ?>" src="<?php echo get_bild_zu_id($zauber_bilder_id) ?>" alt="<?php echo $zauber_titel ?>" />
			</span>
			
			<div onmousedown="dragstart(this)" class="zauberdiv" style="background:url(<?php echo get_bild_zu_id($zauber_bilder_id) ?>);background-size: 60px 60px;width:60px;height:60px;" >
			</div>
			
			<?php
			$count+=1;
		}
		?>
		
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
		if (1==1){
			?>
			<input type="submit" name="zurueck" value="zurück">
			<?php
		}
	?>
	</p>
	<p align="center" style="padding-top:5pt;">
		<?php
		/* ToDo: Auswertung ob Kampf vorbei ist */
		if ($im_kampf){
			?>
			<input type="submit" name="aktion_abgeschlossen" value="Kampf beenden" style="width: 200px;"/>
			<?php
		}
	?>
	</p>	
</div>



