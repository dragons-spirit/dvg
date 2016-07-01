<?php
    include('funktionen.php');
?><
    
    !-- Registrierung -->
<?php
    if(isset($_POST['button_register']))
    {
?>
<form method="POST" action="<?php
            registrierung($ausername, $auserpswd, $ausermail);
            echo $_SERVER['SELF_PHP'];
            ?>">
    Benutzername:<input type="text" name="ausername" size="15">
    Passwort:<input type="password" name="auserpswd" size="15">
    E-Mail: <input type="email" name="ausermail" size="30">
    <input type="submit" name="button_register" value="registrieren">
    
</form>  

<?php
    }
?>
   
   

    
<!-- Element auswŠhlen -->

<h3 align="center">Auswahl des Elementes</h3>

<table align="center">
    <tr>
        <td><p align="center"><a href="index.php?id=4" class="erde"><img src="Bilder/erde.png" height="100%"/></a><br>Erdelement</p></td>
        <td><p align="center"><a href="index.php?id=4" class="wasser"><img src="Bilder/wasser.png" height="100%"/></a><br>Wasserelement</p></td>
        <td><p align="center"><a href="index.php?id=4" class="feuer"><img src="Bilder/feuer.png" height="100%"/></a><br>Feuerelement</p></td>
        <td><p align="center"><a href="index.php?id=4" class="luft"><img src="Bilder/luft.png" height="100%"/></a><br>Luftelement</p></td>
    </tr>
    <tr>
        <td>Beschreibung von Erdelement</td>
        <td>Beschreibung von Wasserelement</td>
        <td>Beschreibung von Feuerelement</td>
        <td>Beschreibung von Luftelement</td>
    </tr>   
</table>



<!-- Gebiet auswŠhlen -->

<h3 align="center">Auswahl des Heimatgebietes</h3>

<!-- Gebiete der Erddrachen-->

<table align="center">
    <tr>
        <td>Waldgebiet<br><a href="index.php?id=5" class=""><img src="Bilder/Dschungelgebiet_grosz.png" height="10%"/></a></td>
        <td>Dschungelgebiet<br><a href="index.php?id=5" class=""><img src="Bilder/Dschungelgebiet_grosz.png"  height="10%"/></a></td>
        <td>Kristallgebiet<br><a href="index.php?id=5" class=""><img src="Bilder/Dschungelgebiet_grosz.png" height="10%"/></a></td>
    </tr>
      <tr>
        <td></td>
        <td></td>
    </tr>
</table>


<table align="center">
    <tr>
        <td>Eisgebiet<br><a href="index.php?id=5" class=""><img src="Bilder/Vulkangebiet_grosz.png" height="10%"/></a></td>
        <td>Klippengebiet<br><a href="index.php?id=5" class=""><img src="Bilder/Vulkangebiet_grosz.png" height="10%"/></a></td>
        <td>Vulkangebiet<br><a href="index.php?id=5" class=""><img src="Bilder/Vulkangebiet_grosz.png" height="10%"/></a></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
</table>


<table align="center">
    <tr>
        <td>W&uuml;stengebiet<br><a href="index.php?id=5" class=""><img src="Bilder/Klippen_grosz.png" height="10%"/></a></td>       
        <td>Steppengebiet<br><a href="index.php?id=5" class=""><img src="Bilder/Klippen_grosz.png" height="10%"/></a></td>
        <td>Wassergebiet<br><a href="index.php?id=5" class=""><img src="Bilder/Klippen_grosz.png" height="10%"/></td>
    </tr>
</table>

</form>

<!-- Spielername & Geschlecht -->

<table align="center">
 <form method="POST" action="index.php?id=spieleseite">   
    <tr><td>Bild von Element</td><td>Bild von Gebiet</td></tr>
    <tr><td>Name:<input type="text" name="playname" size="15"></td><td>
    <select name="geschlecht">
        <option value="Weiblich" name="gesch1">Weiblich</option>
        <option value="M&auml;nnlich" name="gesch2">M&auml;nnlich</option>
    </select>
    </td><td><input type="submit" name="weiter3" value="Speichern und Weiter"></td></tr>
    
</form>
</table>


<a href="index.php?id=2">Noch nicht registriert ? Hier klicken !</a>


