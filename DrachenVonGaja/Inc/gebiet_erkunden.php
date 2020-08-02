<div id="test">
    <h1 align="center">Gebiet erkunden</h1>
	<br /><br />
	<?php $aktion = get_aktion("erkunden_normal");
	$zusatz_jagen = "Wahrscheinlichkeit auf jagbare NPC";
	$zusatz_sammeln = "Wahrscheinlichkeit auf sammelbare NPC";
	?>
	<top_mover>
		<mover>
			<p align="left" style="font-size:10pt;">
			<?php
			echo $aktion->beschreibung."<br />"; 
			$faktor_1 = floor_x((($aktion->faktor_1 - 1) * 100), 0);
			if ($faktor_1 < 0) echo "<font color='darkred'><b>".$faktor_1." %</b> ".$zusatz_jagen."</font><br />";
			if ($faktor_1 > 0) echo "<font color='darkgreen'><b>+".$faktor_1." %</b> ".$zusatz_jagen."</font><br />";
			$faktor_2 = floor_x((($aktion->faktor_2 - 1) * 100), 0);
			if ($faktor_2 < 0) echo "<font color='darkred'><b>".$faktor_2." %</b> ".$zusatz_sammeln."</font><br />";
			if ($faktor_2 > 0) echo "<font color='darkgreen'><b>+".$faktor_2." %</b> ".$zusatz_sammeln."</font><br />";
			echo $aktion->energiebedarf." Energiebedarf";
			?>
			</p>
		</mover>
		<button class="button_standard" type="submit" name="button_gebiet_erkunden" value="<?php echo $aktion->titel; ?>">Normale Erkundung</button>
	</top_mover>
	<br /><br />
	<?php $aktion = get_aktion("erkunden_jagen"); ?>
	<top_mover>
		<mover>
			<p align="left" style="font-size:10pt;">
			<?php
			echo $aktion->beschreibung."<br />"; 
			$faktor_1 = floor_x((($aktion->faktor_1 - 1) * 100), 0);
			if ($faktor_1 < 0) echo "<font color='darkred'><b>".$faktor_1." %</b> ".$zusatz_jagen."</font><br />";
			if ($faktor_1 > 0) echo "<font color='darkgreen'><b>+".$faktor_1." %</b> ".$zusatz_jagen."</font><br />";
			$faktor_2 = floor_x((($aktion->faktor_2 - 1) * 100), 0);
			if ($faktor_2 < 0) echo "<font color='darkred'><b>".$faktor_2." %</b> ".$zusatz_sammeln."</font><br />";
			if ($faktor_2 > 0) echo "<font color='darkgreen'><b>+".$faktor_2." %</b> ".$zusatz_sammeln."</font><br />";
			echo $aktion->energiebedarf." Energiebedarf";
			?>
			</p>
		</mover>
		<button class="button_standard" type="submit" name="button_gebiet_erkunden" value="<?php echo $aktion->titel; ?>">Erkundung mit Fokus Jagd</button>
	</top_mover>
	<br /><br />
	<?php $aktion = get_aktion("erkunden_sammeln"); ?>
	<top_mover>
		<mover>
			<p align="left" style="font-size:10pt;">
			<?php
			echo $aktion->beschreibung."<br />"; 
			$faktor_1 = floor_x((($aktion->faktor_1 - 1) * 100), 0);
			if ($faktor_1 < 0) echo "<font color='darkred'><b>".$faktor_1." %</b> ".$zusatz_jagen."</font><br />";
			if ($faktor_1 > 0) echo "<font color='darkgreen'><b>+".$faktor_1." %</b> ".$zusatz_jagen."</font><br />";
			$faktor_2 = floor_x((($aktion->faktor_2 - 1) * 100), 0);
			if ($faktor_2 < 0) echo "<font color='darkred'><b>".$faktor_2." %</b> ".$zusatz_sammeln."</font><br />";
			if ($faktor_2 > 0) echo "<font color='darkgreen'><b>+".$faktor_2." %</b> ".$zusatz_sammeln."</font><br />";
			echo $aktion->energiebedarf." Energiebedarf";
			?>
			</p>
		</mover>
		<button class="button_standard" type="submit" name="button_gebiet_erkunden" value="<?php echo $aktion->titel; ?>">Erkundung mit Fokus Sammeln</button>
	</top_mover>
	<br /><br />
	<?php $aktion = get_aktion("erkunden_reden"); ?>
	<top_mover>
		<mover>
			<p align="left" style="font-size:10pt;">
			<?php
			echo $aktion->beschreibung."<br />"; 
			$faktor_1 = floor_x((($aktion->faktor_1 - 1) * 100), 0);
			if ($faktor_1 < 0) echo "<font color='darkred'><b>".$faktor_1." %</b> ".$zusatz_jagen."</font><br />";
			if ($faktor_1 > 0) echo "<font color='darkgreen'><b>+".$faktor_1." %</b> ".$zusatz_jagen."</font><br />";
			$faktor_2 = floor_x((($aktion->faktor_2 - 1) * 100), 0);
			if ($faktor_2 < 0) echo "<font color='darkred'><b>".$faktor_2." %</b> ".$zusatz_sammeln."</font><br />";
			if ($faktor_2 > 0) echo "<font color='darkgreen'><b>+".$faktor_2." %</b> ".$zusatz_sammeln."</font><br />";
			echo $aktion->energiebedarf." Energiebedarf";
			?>
			</p>
		</mover>
		<button class="button_standard" type="submit" name="button_gebiet_erkunden" value="<?php echo $aktion->titel; ?>">Interessantes vor Ort</button>
	</top_mover>
</div>




