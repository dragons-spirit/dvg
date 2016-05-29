
<?php
/*
    $ausername = $_GET["ausername"];
    $auserpswd = $_GET["auserpswd"];*/
        
 if(isset($_POST["anmelde"]))
        {
            /*$sql = "SELECT * FROM Spieler WHERE spielername = `$ausername` AND passwort = `$auserpswd`";*/
            header(`Location: http://`.$_SERVER[`HTTP_HOST`].`/localhost/lichtdrachen%20der%20Elemente/index.php?id=6`);
            exit;
        }
else
        {
            
        }

?>

<form method="POST" action="index.php?id=6">
    Benutzername:<input type="text" name="ausername" size="15">
    Passwort:<input type="password" name="auserpswd" size="15">
    <input type="submit" value="Anmelden" name="anmelde">
    <br>
    <a href="index.php?id=2">Noch nicht registriert ? Hier klicken !</a>
</form>

<p align="center"><img src="Bilder/Deckblatt.png" height="500x"/></p>

