<html>

<?php
    include("db_funktionen.php");
?>

<form method="POST" action="<?php echo $_SERVER["PHP_SELF"];?>">
    Benutzername:<input type="text" name="reg_user" size="15">
    Passwort:<input type="password" name="reg_pswd" size="15">
    E-Mail: <input type="email" name="reg_mail" size="30">
    <input type="submit" name="button_register" value="registrieren">   
</form>

<?php
    if(isset($_POST["button_register"]))
    {
        $ergebnis = get_anmeldung($_POST['reg_user']);
        if ( ! $ergebnis) {
            insert_registrierung($_POST['reg_user'], $_POST['reg_pswd'], $_POST['reg_mail']);
            print "Registrierung erfolgt";
        }
        else {
            if ($ergebnis[3] == $_POST['reg_mail']){
                print "Wie w&auml;re es mit anmelden statt regisrieren?";
            }
            else {
                print "Nutzer existiert bereits. Bitte w&auml;hlen Sie einen anderen Benutzernamen!";
            }
        }
        print "<br />\n";
    }
?>

<br /><br />

<form method="POST" action="<?php echo $_SERVER["PHP_SELF"];?>">
    Benutzername:<input type="text" name="login_user" size="15">
    Passwort:<input type="password" name="login_pswd" size="15">
    <input type="submit" name="button_login" value="anmelden">   
</form>

<?php
    if(isset($_POST["button_login"])) {
        $ergebnis = get_anmeldung($_POST['login_user']);
        if (!$ergebnis) {
            print "Benutzer existiert nicht!";
        }
        else {
            if ($ergebnis[2] == $_POST['login_pswd']){
                print "Super! Passwort erraten!";
                #Spielseite aufrufen
            }
            else {
                print "Du kommst hier nicht rein, aber du kannst es gern noch einmal versuchen.";
                #Irgendwas aufrufen (z.B. GalgenmŠnnchen)
            }
        }
        print "<br />\n";
    }
    
?>


</html>