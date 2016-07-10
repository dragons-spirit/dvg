<form method="POST" action="<?php echo $_SERVER["PHP_SELF"];?>">
<?php
    if(isset($_POST["button_register"]))
    {
        $ergebnis = get_anmeldung($_POST['reg_user']);
        if ( ! $ergebnis)
        {
            insert_registrierung($_POST['reg_user'], $_POST['reg_pswd'], $_POST['reg_mail']);
            print "Registrierung erfolgt";
        }
        else
        {
            if ($ergebnis[3] == $_POST['reg_mail'])
            {
                print "Wie w&auml;re es mit anmelden statt regisrieren?";
            }
            else
            {
                print "Nutzer existiert bereits. Bitte w&auml;hlen Sie einen anderen Benutzernamen!";
            }
        }
        print "<br />\n";
    }


    if(isset($_POST["button_login"]))
    {
        $ergebnis = get_anmeldung($_POST['login_user']);
        if (!$ergebnis)
        {
            print "Benutzer existiert nicht!";
        }
        else
        {
            if ($ergebnis[2] == $_POST['login_pswd'])
            {
                print "Super! Passwort erraten!";
                ?>
                <!-- Neuer Spieler anlegen -->

<h3 align="center">Account</h3>


<input type="submit" name="button_neuerSpieler" value="Neuen Spieler anlegen">
    
<?php
if(isset($_POST["button_neuerSpieler"]))
{

?>

<!-- Element und Heimatgebiet auswŠhlen -->

<h3 align="center">Auswahl des Elementes</h3>

<table align="center">
           <tr>
                <td><input type="submit" style="background:url(./Bilder/Erdei_klein.png); height:110px; width:90px; background-repeat:no-repeat;" alt="Erdelement" name="button_erdelement" value=""></td>
                <td><input type="submit" style="background:url(./Bilder/Wasserei_klein.png); height:110px;width:90px; background-repeat:no-repeat;" alt="Wasserelement" name="button_wasserelement" value=""></td>
                <td><input type="submit" style="background:url(./Bilder/Feuerei_klein.png); height:110px;width:90px; background-repeat:no-repeat;" alt="Feuerelement" name="button_feuerelement" value=""></td>
                <td><input type="submit" style="background:url(./Bilder/Luftei_klein.png); height:110px;width:90px; background-repeat:no-repeat;" alt=Luftelement" name="button_luftelement" value=""></td>
            </tr>
           <tr>
                <td>Beschreibung von Erdelement</td>
                <td>Beschreibung von Wasserelement</td>
                <td>Beschreibung von Feuerelement</td>
                <td>Beschreibung von Luftelement </td>
           </tr>

       
        
</table>


<?php
    if(isset($_POST["button_erdelement"]))
        {
        echo "Du hast das Erdelement ausgewŠhlt!";
        #Zeige Gebiete fŸr Erdelement
        ?>
        <h3 align="center">Auswahl des Heimatgebietes</h3>
        
        <table align="center">
            <tr>
                <td>Dschungel<br><input type="submit" style="background:url(./Bilder/Dschungel.png); height:100px;" alt="Dschungel" name="button_dschungel" value=""></td>
                <td>Kristallh&ouml;hle<br><input type="submit" style="background:url(./Bilder/Kristallhoehle.png); height:100px;" alt="Kristallhoehle" name="button_kristallhoehle" value=""></td>
            </tr>            
        </table>
        <?php
                if(isset($_POST["button_dschungel"]))
                {
                include("Inc/name_geschlecht.inc.php");
                echo "Hier mŸsste etwas dastehen!";
                }
            
                if(isset($_POST["button_kristallhoehle"]))
                {
                include("Inc/name_geschlecht.inc.php");
                }
                else
                {
                echo "W&auml;hle ein Heimatgebiet aus!";
                }
        } 
        
    if(isset($_POST["button_wasserelement"]))
        {
        echo "Du hast das Wasserelement ausgew&auml;hlt!";
        #Zeige Gebiete fŸr Wasserelement
        ?>
        <h3 align="center">Auswahl des Heimatgebietes</h3>
        
        <table align="center">
            <tr>
            <td>Eissee<br><input type="submit" style="background:url(./Bilder/Eissee.png); height:100px;" alt="Eissee" name="button_eissee" value=""></td>
            <td>Sumpf<br><input type="submit" style="background:url(./Bilder/Sumpf.png); height:100px;" alt="Sumpf" name="button_sumpf" value=""></td>
            </tr>
        </table>
        <?php
                if(isset($_POST["button_eissee"]))
                {
                include("Inc/name_geschlecht.inc.php");
                }
        
                if(isset($_POST["button_sumpf"]))
                {
                include("Inc/name_geschlecht.inc.php");
                }
                else
                {
                echo "W&auml;hle ein Heimatgebiet aus!";
                }
        }
        
        if(isset($_POST["button_feuerelement"]))
        {
        echo "Du hast das Feuerelement ausgew&auml;hlt!";
        #Zeige Gebiete fŸr Feuerelement
        ?>
        <h3 align="center">Auswahl des Heimatgebietes</h3>
        
        <table align="center">
            <tr>
                <td>Vulkan<br><input type="submit" style="background:url(./Bilder/Vulkan.png); height:100px;" alt="Vulkan" name="button_vulkan" value=""></td>
                <td>W&uml;ste<br><input type="submit" style="background:url(./Bilder/Wueste.png); height:100px;" alt="Wueste" name="button_wueste" value=""></td>
            </tr>
        </table>
        <?php
                if(isset($_POST["button_vulkan"]))
                {
                include("Inc/name_geschlecht.inc.php");
                 }
                        
                if(isset($_POST["button_wueste"]))
                 {
                 include("Inc/name_geschlecht.inc.php");
                 }
                 else
                {
                echo "W&auml;hle ein Heimatgebiet aus!";
                }
        } 

       if(isset($_POST["button_luftelement"]))
        {
        echo "Du hast das Luftelement ausgew&auml;hlt!";
        #Zeige Gebiete fŸr Luftelement
        ?>
        <h3 align="center">Auswahl des Heimatgebietes</h3>
        
        <table align="center">
            <tr>
                <td>Klippe<br><input type="submit" style="background:url(./Bilder/Klippe.png); height:100px;" alt="Klippe" name="button_klippe" value=""></td>
                <td>Mamutbaum<br><input type="submit" style="background:url(./Bilder/Mamutbaum.png); height:100px;" alt="Mamutbaum" name="button_mamutbaum" value=""></td>
            </tr>
        </table>
        <?php
                if(isset($_POST["button_klippe"]))
                {
                include("Inc/name_geschlecht.inc.php");
                }
                        
                if(isset($_POST["button_mamutbaum"]))
                {
                include("Inc/name_geschlecht.inc.php");
                }
                else
                {
                echo "W&auml;hle ein Heimatgebiet aus!";
                }
        } 
        else
        {
        echo "W&auml;hle ein Element aus!";
                
        }

}
    


else
{
?>
    <!-- Registrierung -->


    Benutzername:<input type="text" name="reg_user" size="15">
    Passwort:<input type="password" name="reg_pswd" size="15">
    E-Mail: <input type="email" name="reg_mail" size="30">
    <input type="submit" name="button_register" value="registrieren">
        
        
<br />
<br />

<!-- Anmeldung -->

    Benutzername:<input type="text" name="login_user" size="15">
    Passwort:<input type="password" name="login_pswd" size="15">
    <input type="submit" name="button_login" value="anmelden">
        <?php
}
                
            
            }          
            else
            {
                print "Du kommst hier nicht rein, aber du kannst es gern noch einmal versuchen.";
                /*Irgendwas aufrufen (z.B. GalgenmŠnnchen)*/
                   ?>
                   <!-- Registrierung -->


    Benutzername:<input type="text" name="reg_user" size="15">
    Passwort:<input type="password" name="reg_pswd" size="15">
    E-Mail: <input type="email" name="reg_mail" size="30">
    <input type="submit" name="button_register" value="registrieren">
        
        
<br />
<br />

<!-- Anmeldung -->

    Benutzername:<input type="text" name="login_user" size="15">
    Passwort:<input type="password" name="login_pswd" size="15">
    <input type="submit" name="button_login" value="anmelden">

        
 <p align="center"><img src="Bilder/Deckblatt.png" height=300px"/></p>
           <?php }
        }
        print "<br />\n";
    }
    
   ?>
   
    Benutzername:<input type="text" name="reg_user" size="15">
    Passwort:<input type="password" name="reg_pswd" size="15">
    E-Mail: <input type="email" name="reg_mail" size="30">
    <input type="submit" name="button_register" value="registrieren">
        
        
<br />
<br />

<!-- Anmeldung -->

    Benutzername:<input type="text" name="login_user" size="15">
    Passwort:<input type="password" name="login_pswd" size="15">
    <input type="submit" name="button_login" value="anmelden">
        
    <p align="center"><img src="Bilder/Deckblatt.png" height=300px"/></p>
</form>
         


