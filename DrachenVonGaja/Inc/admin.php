<?php
	session_start();
	
	include("db_funktionen.php");
	include("db_funktionen_admin.php");
	
	if (!isset($_SESSION['login_name']))
	{
?>
		<script type="text/javascript">
			window.location.href = "../index.php"
		</script>
<?php
	}
	else
	{
		echo "Los geht's ...<br/>\n";
		if ($alle_spieler = get_spieler_all())
		{
			$count_zeile = 0;
			$count_spalte_max = 19;
?>			
			<table align="center" border="1px" color="black">
				<tr>
					<td>Lfd. Nr.</td>
					<td>id</td>
                    <td>account_id</td>
					<td>bilder_id</td>
					<td>gattung_id</td>
					<td>level_id</td>
					<td>gebiet_id</td>
                    <td>name</td>
					<td>geschlecht</td>
					<td>staerke</td>
					<td>intelligenz</td>
					<td>magie</td>
					<td>element_feuer</td>
					<td>element_wasser</td>
					<td>element_erde</td>
					<td>element_luft</td>
					<td>gesundheit</td>
					<td>max_gesundheit</td>
					<td>energie</td>
					<td>max_energie</td>
					<td>balance</td>
				</tr>
<?php			
			while($zeile = $alle_spieler->fetch_array(MYSQLI_NUM))
			{
				$count_zeile = $count_zeile + 1;
				$count_spalte = 0;
?>				
				<tr>
					<td><?php echo "#" . $count_zeile ?></td>
<?php				
				while($count_spalte <= $count_spalte_max)
				{
					$spalte = $zeile[$count_spalte];
?>			
					<td><?php echo $spalte . "<br />\n"; ?></td>
<?php
					$count_spalte = $count_spalte + 1;
				}
?>
				</tr>
<?php
			}
?>
			</table>
<?php
			
		}
		else{
			echo "<br />\nKeine Spieler zum Account vorhanden.<br />\n";
		}
		
	}
	
	
    
