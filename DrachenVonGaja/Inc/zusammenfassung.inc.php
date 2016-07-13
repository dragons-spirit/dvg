<form method="POST" action="<?php echo $_SERVER["PHP_SELF"];?>">
<?php    

#################
#	Anmeldung	#
#################

	if ($_SESSION['letzte_seite'] = "index" or $_SESSION['letzte_seite'] = "registrierung")
	{
		$_SESSION['letzte_seite'] = "login";
?>
		Benutzername:<input type="text" name="login_user" size="15">
		Passwort:<input type="password" name="login_pswd" size="15">
		<input type="submit" name="button_login" value="anmelden">
		<br />
		<br />
		<input type="submit" name="button_acc_neu" value="Neuer Account">
<?php
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
                $_SESSION['login'] = $_POST['login_user'];
				print "Anmeldung erfolgreich";
?>
                <!-- Neuer Spieler anlegen -->
                <h3 align="center">Account</h3>
                <input type="submit" name="button_neuerSpieler" value="Neuen Spieler anlegen">
<?php
            }
            else
            {
                print "Du kommst hier nicht rein, aber du kannst es gern noch einmal versuchen.";
                /*Irgendwas aufrufen (z.B. Galgenmaennchen)*/
            }
        }
        print "<br />\n";
    }


#####################
#	Registrierung	#
#####################
	
	if (isset($_POST["button_acc_neu"]))
	{
		$_SESSION['letzte_seite'] = "registrierung";
?>
		Benutzername:<input type="text" name="reg_user" size="15">
		Passwort:<input type="password" name="reg_pswd" size="15">
		E-Mail: <input type="email" name="reg_mail" size="30">
		<input type="submit" name="button_register" value="registrieren">
<?php
	}

	
	if(isset($_POST["button_register"]))
    {
		$ergebnis = get_anmeldung($_POST['reg_user']);
        if ( ! $ergebnis and $_POST['reg_user'] != '' and $_POST['reg_pswd'] != '')
        {
            insert_registrierung($_POST['reg_user'], $_POST['reg_pswd'], $_POST['reg_mail']);
            print "Registrierung erfolgt";
        }
        else
        {
            if ($ergebnis[1] == $_POST['reg_user']) print "Nutzer existiert bereits.<br />";
            if ($ergebnis[2] == $_POST['reg_pswd']) print "Wählen Sie ein Passwort.<br />";
			if ($ergebnis[3] == $_POST['reg_mail']) print "E-Mailadresse schon vorhanden.<br />";
        }
        print "<br />\n";
    }
    else
    {
        echo "<p align='center'>Bitte registriere dich, wenn du noch nicht angemeldet bist!</p>";
        echo "<br />";
        echo "<br />";
    }

#######################
#	Hintergrundbild   #
#######################
	
?>
	<p align="center"><img src="Bilder/Deckblatt.png" height=300px"/></p>
<?php


#######################################
#	Neuer Spieler - Element waehlen   #
#######################################
    
	if(isset($_POST["button_neuerSpieler"]))
	{
		$_SESSION['letzte_seite'] = "neuer_spieler_element";
?>
		<!-- Element und Heimatgebiet auswaehlen -->
		<h3 align="center">Auswahl des Elementes</h3>
		<table align="center">
			<tr>
				<td><input type="submit" style="background:url(./Bilder/Erdei_klein.png); height:110px; width:90px; background-repeat:no-repeat;" alt="Erdelement" name="button_erdelement" value=""></td>
				<td><input type="submit" style="background:url(./Bilder/Wasserei_klein.png); height:110px;width:90px; background-repeat:no-repeat;" alt="Wasserelement" name="button_wasserelement" value=""></td>
				<td><input type="submit" style="background:url(./Bilder/Feuerei_klein.png); height:110px;width:90px; background-repeat:no-repeat;" alt="Feuerelement" name="button_feuerelement" value=""></td>
				<td><input type="submit" style="background:url(./Bilder/Luftei_klein.png); height:110px;width:90px; background-repeat:no-repeat;" alt="Luftelement" name="button_luftelement" value=""></td>
			</tr>
			<tr>
				<td>Beschreibung von Erdelement</td>
				<td>Beschreibung von Wasserelement</td>
				<td>Beschreibung von Feuerelement</td>
				<td>Beschreibung von Luftelement </td>
			</tr>
		</table>
<?php
	}

	
###########################################
#	Neuer Spieler - Startgebiet waehlen   #
###########################################
	
    if(isset($_POST["button_erdelement"]))
    {
        $_SESSION['letzte_seite'] = "neuer_spieler_startgebiet";
		echo "Du hast das Erdelement ausgew&auml;hlt!";
        $_SESSION['element'] = "Erde";
		$_SESSION['gattung'] = "Erddrache";
        #Zeige Gebiete fuer Erdelement
?>
        <h3 align="center">Auswahl des Heimatgebietes</h3>
        <table align="center">
            <tr>
                <td>Dschungel<br><input type="submit" style="background:url(./Bilder/Dschungel.png); height:100px;" alt="Dschungel" name="button_dschungel" value=""></td>
                <td>Kristallh&ouml;hle<br><input type="submit" style="background:url(./Bilder/Kristallhoehle.png); height:100px;" alt="Kristallhoehle" name="button_kristallhoehle" value=""></td>
            </tr>            
        </table>
 <?php
    }

    if(isset($_POST["button_wasserelement"]))
    {
        $_SESSION['letzte_seite'] = "neuer_spieler_startgebiet";
		echo "Du hast das Wasserelement ausgew&auml;hlt!";
		$_SESSION['element'] = "Wasser";
		$_SESSION['gattung'] = "Wasserdrache";
        #Zeige Gebiete fuer Wasserelement
?>
        <h3 align="center">Auswahl des Heimatgebietes</h3>
        <table align="center">
            <tr>
				<td>Eissee<br><input type="submit" style="background:url(./Bilder/Eissee.png); height:100px;" alt="Eissee" name="button_eissee" value=""></td>
				<td>Sumpf<br><input type="submit" style="background:url(./Bilder/Sumpf.png); height:100px;" alt="Sumpf" name="button_sumpf" value=""></td>
            </tr>
        </table>
<?php
    }

    if(isset($_POST["button_feuerelement"]))
    {
        $_SESSION['letzte_seite'] = "neuer_spieler_startgebiet";
		echo "Du hast das Feuerelement ausgew&auml;hlt!";
		$_SESSION['element'] = "Feuer";
		$_SESSION['gattung'] = "Feuerdrache";
        #Zeige Gebiete fuer Feuerelement
?>
        <h3 align="center">Auswahl des Heimatgebietes</h3>
        <table align="center">
            <tr>
                <td>Vulkan<br><input type="submit" style="background:url(./Bilder/Vulkan.png); height:100px;" alt="Vulkan" name="button_vulkan" value=""></td>
                <td>W&uml;ste<br><input type="submit" style="background:url(./Bilder/Wueste.png); height:100px;" alt="Wueste" name="button_wueste" value=""></td>
            </tr>
        </table>
<?php
	}

    if(isset($_POST["button_luftelement"]))
    {
        $_SESSION['letzte_seite'] = "neuer_spieler_startgebiet";
		echo "Du hast das Luftelement ausgew&auml;hlt!";
		$_SESSION['element'] = "Luft";
		$_SESSION['gattung'] = "Luftdrache";
        #Zeige Gebiete fuer Luftelement
?>
        <h3 align="center">Auswahl des Heimatgebietes</h3>
        <table align="center">
            <tr>
                <td>Klippe<br><input type="submit" style="background:url(./Bilder/Klippe.png); height:100px;" alt="Klippe" name="button_klippe" value=""></td>
                <td>Mammutbaum<br><input type="submit" style="background:url(./Bilder/Mammutbaum.png); height:100px;" alt="Mammutbaum" name="button_mammutbaum" value=""></td>
            </tr>
        </table>
<?php
	}

	if(isset($_POST["button_dschungel"])) $_SESSION['startgebiet'] = "Dschungel";
	if(isset($_POST["button_kristallhoehle"])) $_SESSION['startgebiet'] = "Kristallhoehle";
	if(isset($_POST["button_eissee"])) $_SESSION['startgebiet'] = "Eissee";
	if(isset($_POST["button_sumpf"])) $_SESSION['startgebiet'] = "Sumpf";
	if(isset($_POST["button_vulkan"])) $_SESSION['startgebiet'] = "Vulkan";
	if(isset($_POST["button_wueste"])) $_SESSION['startgebiet'] = "Wueste";
    if(isset($_POST["button_klippe"])) $_SESSION['startgebiet'] = "Klippe";
	if(isset($_POST["button_mammut"])) $_SESSION['startgebiet'] = "Mammutbaum"; 


###############################################
#	Neuer Spieler - Name/Geschlecht waehlen   #
###############################################
	
    
	#if(isset($_POST["button_mammutbaum"]) or isset($_POST["button_klippe"]) or isset($_POST["button_wueste"]) or isset($_POST["button_dschungel"]) or isset($_POST["button_kristallhoehle"]) or isset($_POST["button_eissee"]) or isset($_POST["button_sumpf"]) or isset($_POST["button_vulkan"]))
	if( $_SESSION['letzte_seite'] = "neuer_spieler_startgebiet")
    {
		$_SESSION['letzte_seite'] = "neuer_spieler_name_geschlecht";
?>
        <!-- Spielername & Geschlecht -->
		<table align="center">  
			<tr><td>Bild von Element</td><td>Bild von Gebiet</td></tr>
			<tr><td>Name:<input type="text" name="playname" size="15"></td><td>
			<select name="geschlecht">
				<option value="Weiblich" name="gesch1">Weiblich</option>
				<option value="Maennlich" name="gesch2">M&auml;nnlich</option>
			</select>
			<tr><td><input type="submit" name="button_spieleseite" value="Zur Spielseite"></td></tr>
		</table>
<?php
	}
	

#############################
#	Neuen Spieler anlegen   #
#############################
	
	if(isset($_POST["button_spieleseite"]))
	{
		$_SESSION['spielername'] = $_POST["playname"];
		if ($_POST["geschlecht"] = "Weiblich"){
			$_SESSION['geschlecht'] = "W";
		} else {
			$_SESSION['geschlecht'] = "M";
		}
		
		insert_spieler($_SESSION['login'], $_SESSION['startgebiet'], $_SESSION['gattung'], $_SESSION['spielername'], $_SESSION['geschlecht']);
	}
?>
</form>

         


