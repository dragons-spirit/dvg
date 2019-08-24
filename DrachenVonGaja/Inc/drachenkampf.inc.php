<div id="divzauber" align="left" style="background-color:red;">
	<table>
		<tr>
			<td colspan="2"><img src="<?php echo get_bild_zu_id($bilder_id); ?>" width="400px" alt="Spielerbild"/></td>
		</tr>
		<tr>
			<td colspan="2"><p align="center" style="font-size:14pt"><?php echo get_gattung_titel($gattung_id) . " " . $name;?></p></td>
		</tr>
		<tr><td><br/>    </td></tr>
		<tr>
			<td><p align="left">Geschlecht</p></td>
			<td><p align="left">
			<?php 
				switch ($geschlecht){
				case "W":
					echo "weiblich";
					break;
				default:
					echo "männlich";
					break;
				}
			?>
			</p></td>
		</tr>
		<tr>
			<td><p align="left">Element Feuer</p></td>
			<td><p align="left"><?php echo $element_feuer;?></p></td>
		</tr>
		<tr>
			<td><p align="left">Element Erde</p></td>
			<td><p align="left"><?php echo $element_erde;?></p></td>
		</tr>
		<tr>
			<td><p align="left">Element Wasser</p></td>
			<td><p align="left"><?php echo $element_wasser;?></p></td>
		</tr>
		<tr>
			<td><p align="left">Element Luft</p></td>
			<td><p align="left"><?php echo $element_luft;?></p></td>
		</tr>
		<tr>
			<td><p align="left">Gesundheit</p></td>
			<td><p align="left"><?php echo $gesundheit . "/" . $max_gesundheit;?></p></td>
		</tr>
		<tr>
			<td><p align="left">Energie</p></td>
			<td><p align="left"><?php echo $energie . "/" . $max_energie;?></p></td>
		</tr>
		<tr>
			<td><p align="left">Balance</p></td>
			<td><p align="left"><?php echo $balance;?></p></td>
		</tr>
	</table>
	
	<?php
	
	$count=0;
	$zauber = get_zauber_von_spieler($spieler_id);
	
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
	
</div>



