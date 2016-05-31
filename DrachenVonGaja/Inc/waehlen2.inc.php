<h3 align="center">Auswahl des Heimatgebietes</h3>

<?php
/*$weiter2 = $_GET["weiter2"];*/
?>

<!-- Gebiete der Erddrachen-->

<table align="center">
    <tr>
        <td>Wiese<br><a href="index.php?id=5" class="wiese"><img src="Bilder/Dschungelgebiet_grosz.png" height="10%"/></a></td>
        <td>Dschungel<br><a href="index.php?id=5" class="dschungel"><img src="Bilder/Dschungelgebiet_grosz.png"  height="10%"/></a></td>
        <td>Wald&ouml;hle<br><a href="index.php?id=5" class="hoehle"><img src="Bilder/Dschungelgebiet_grosz.png" height="10%"/></a></td>
    </tr>
      <tr>
        <td></td>
        <td></td>
    </tr>
</table>

<!-- Gebiete der Wasserdrachen-->

<table align="center">
    <tr>
        <td>Sumpf</td>
        <td>See</td>
        <td>Eis</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
</table>

<!-- Gebiete der Feuerdrachen-->

<table align="center">
    <tr>
        <td>Vulkan<br><img src="Bilder/Vulkangebiet_grosz.png" height="10%"/></td>
        
        <td>W&uuml;ste<br><img src="Bilder/Vulkangebiet_grosz.png" height="10%"/></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
</table>

<!-- Gebiete der Luftdrachen-->

<table align="center">
    <tr>
        <td>Klippe<br><img src="Bilder/Klippen_grosz.png" height="10%"/></td>
        <td>Sturmh&ouml;hle<br><img src="Bilder/Klippen_grosz.png" height="10%"/></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
</table>

<form method="POST" action="index.php?id=5">
    <input type="submit" name="weiter2" value="Speichern und Weiter">
</form>