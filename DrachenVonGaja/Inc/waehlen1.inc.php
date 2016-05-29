<h3 align="center">Auswahl des Elementes</h3>

<?php
/*$weiter1 = $_GET["weiter1"];*/
?>

<table align="center">
    <tr>
        <td><p align="center"><img src="Bilder/erde.png" height="100%"/><br>Erdelement</p></td>
        <td><p align="center"><img src="Bilder/wasser.png" height="100%"/><br>Wasserelement</p></td>
        <td><p align="center"><img src="Bilder/feuer.png" height="100%"/><br>Feuerelement</p></td>
        <td><p align="center"><img src="Bilder/luft.png" height="100%"/><br>Luftelement</p></td>
    </tr>
    <tr>
        <td>Beschreibung von Erdelement</td>
        <td>Beschreibung von Wasserelement</td>
        <td>Beschreibung von Feuerelement</td>
        <td>Beschreibung von Luftelement</td>
    </tr>   
</table>

<form method="POST" action="index.php?id=4">
    <input type="submit" name="weiter1" value="Speichern und Weiter">
</form>