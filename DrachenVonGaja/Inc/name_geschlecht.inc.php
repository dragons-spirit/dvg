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

if(isset($_POST["button_spieleseite"]))
{
 insert_spieler($login, $gattung, $name, $geschlecht);
}

?>
