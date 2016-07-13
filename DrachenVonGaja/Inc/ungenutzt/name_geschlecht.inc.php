	<!-- Spielername & Geschlecht -->
	<table align="center">  
		<tr><td>Bild von Element</td><td>Bild von Gebiet</td></tr>
		<tr><td>Name:<input type="text" name="playname" size="15"></td><td>
		<select name="geschlecht">
			<option value="Weiblich" name="gesch1">Weiblich</option>
			<option value="M&auml;nnlich" name="gesch2">M&auml;nnlich</option>
		</select>
		<tr><td><input type="submit" name="button_spieleseite" value="Zur Spielseite"></td></tr>
	</table>
<?php

	
	
	
	if(isset($_POST["button_klippe"]))
	{
		$_SESSION['spielername'] = $_POST["spielername"];
		if ($_POST["geschlecht"] = "Weiblich"){
			$_SESSION['geschlecht'] = "W";
		} else {
			$_SESSION['geschlecht'] = "M";
		}
	}
	
		
	if(isset($_POST["button_spieleseite"]))
	{
		echo "Test";
		
		echo $_SESSION['login'];
		echo $_SESSION['startgebiet'];
		echo $_SESSION['element'];
		echo $_SESSION['gattung'];
		echo $_SESSION['spielername'];
		echo $_SESSION['geschlecht'];
		
		insert_spieler($_SESSION['login'], $_SESSION['gattung'], $_SESSION['spielername'], $_SESSION['geschlecht']);
	}

?>
