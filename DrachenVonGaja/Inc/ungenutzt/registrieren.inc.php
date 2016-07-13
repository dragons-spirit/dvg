
<?php
    if(isset($_POST["registrier"]))
        {
            /*$sql = "INSERT INTO `Spieler` (`spielername` , `passwort` , `mail`)" . "VALUES (`$rusername` , `$ruserpswd` , `$rusermail`);";*/
            header(`Location: http://`.$_SERVER[`HTTP_HOST`].`/localhost/lichtdrachen%20der%20Elemente/index.php?id=3`);
            exit;
            $rusername = $_GET["rusername"];
            $ruserpswd = $_GET["ruserpswd"];
            $rusermal = $_GET["rusermail"];
            ?>
    <table align="center">    
    <form method="POST" action="index.php?id=3">
    <tr><td>Benutzernamen:</td><td><input type="text" name="rusername" size="15"></td></tr>
    <tr><td>Passwort:</td><td><input type="password" name="ruserpswd" size="15"></td></tr>
    <tr><td>E - Mail:</td><td><input type="text" name="rusermail" size="15"></td></tr>
    <tr><td></td><td><input type="submit" value="Registrieren" name="registrier"></td></tr>
    </form>
    </table>
         <?php
        }
    else
        {      
         ?>
    <table align="center">    
    <form method="POST" action="index.php?id=3">
    <tr><td>Benutzernamen:</td><td><input type="text" name="rusername" size="15"></td></tr>
    <tr><td>Passwort:</td><td><input type="password" name="ruserpswd" size="15"></td></tr>
    <tr><td>E - Mail:</td><td><input type="text" name="rusermail" size="15"></td></tr>
    <tr><td></td><td><input type="submit" value="Registrieren" name="registrier"></td></tr>
    </form>
    </table>
         <?php
        }
        
?>


