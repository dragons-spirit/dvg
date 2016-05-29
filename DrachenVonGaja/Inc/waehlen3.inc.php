<h3 align="center">Name und Geschlecht bestimmen</h3>

<?php
/*$playname = $_GET["playname"];
$gesch1 = $_GET["gesch1"];
$gesch2 = $_GET["gesch2"];
$weiter3 = $_GET["weiter3"];*/
?>

<table align="center">
 <form method="POST" action="index.php?id=6">   
    <tr><td>Bild von Element</td><td>Bild von Gebiet</td></tr>
    <tr><td>Name:<input type="text" name="playname" size="15"></td><td>
    <select name="geschlecht">
        <option value="Weiblich" name="gesch1">Weiblich</option>
        <option value="M&auml;nnlich" name="gesch2">M&auml;nnlich</option>
    </select>
    </td><td><input type="submit" name="weiter3" value="Speichern und Weiter"></td></tr>
    
</form>
</table>


    
