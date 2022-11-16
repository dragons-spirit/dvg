
<h1 align="center">Handwerk</h1>
    

<?php
if ($items = get_all_items_spieler($spieler->id))
{
	?>
	<table class="tabelle" style="margin-top:10%;" width="700px" cellpadding="5px">
		<tr class="tabelle_kopf">
			<td align="center">Material</td>
			<td align="center">Anzahl vorhanden</td>
			
		</tr>
		<?php
		foreach ($items as $item)
		{
		    if ($item->verarbeitbar==1)
		    {
			?>
			<tr class="tabelle_inhalt">
				<td align="left" valign="top"><?php echo $item->name ?></td>
				<td align="right" valign="top"><?php echo $item->anzahl ?></td>
			</tr>
			<?php
		}}
		if(!isset($items[0]))
		{
			?>
			<tr class="tabelle_inhalt">
				<td colspan=4>Keine Items gefunden.</td>
			</tr>
			<?php
		}
		?>
</table>
	<?php
		}
		?>

<p align="left" style="font-size:12pt;padding-left:10px;"><b>Herstellungsmöglichkeiten:</b> <br><br>
<button class="button_standard" type="submit" name="trommel" value="">Trommel</button> aus 2 Knochen für die Sticks, 24 Knochen für das Gerüst, 12 Rindstücke, ein Stück Leder<br><br>
<button class="button_standard" type="submit" name="puzzle" value="">Drachenpuzzle</button> aus 12 Teilen <br><br>
<button class="button_standard" type="submit" name="gefaess" value="">Gefäß</button> aus 10 Ton und 4 Rindenstücken für den Deckel <br><br>

<table>
	<tr><td>Material</td><td>Anzahl</td></tr>
	<tr class="tabelle_inhalt"><td>Ton</td><td><input type="text" size="5" value=""/></td></tr>
	<tr class="tabelle_inhalt"><td>Glas</td><td><input type="text" size="5" value=""/></td></tr>
	<tr class="tabelle_inhalt"><td>Metall</td><td><input type="text" size="5" value=""/></td></tr>
	<tr class="tabelle_inhalt"><td>Kristall</td><td><input type="text" size="5" value=""/></td></tr>
  </table>
<details style="position:absolute;">
  <summary>Herstellung von Materialien</summary>
  
  
  <br>
    
    <input type="text" size="5" value=""/>
    <select>
	<option>Ton</option>
	<option>Glas</option>
	<option>Metall</option>
	<option>Kristall</option>
    </select>
  <button class="button_standard" type="submit" name="formen" value="">formen</button>
    
</details>

</p>
<p align="center" style="padding-top:10pt;">
		<button class="button_standard" type="submit" name="zurück" value="zurück">zurück</button>
</p>
    
   
    

    

 


