<form id="temp" method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">

<?php

	if ($debug)
	{
		print "Session-Parameter Beginn<br/>";
		print_r($_SESSION);
		print "<br/>";
	}
	
	if (isset($_POST["button_logout"]))
	{
		session_unset();
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
                $_SESSION["login_name"] = $_POST['login_user'];
				unset ($_SESSION['registrierung_ok']); 
				print "Anmeldung erfolgreich";
            }
            else
            {
                print "Du kommst hier nicht rein, aber du kannst es gern noch einmal versuchen.";
            }
        }
        print "<br />\n";
    }


	if(isset($_POST["button_register"]))
    {
		$daten_ok = true;
		if ($_POST['reg_user'] == ""){ print "Bitte geben Sie einen Benutzernamen ein.<br />\n"; $daten_ok = false;}
		if ($_POST['reg_pswd'] == ""){ print "Bitte geben Sie ein Passwort ein.<br />\n";  $daten_ok = false;}
		if ($_POST['reg_mail'] == ""){ print "Bitte geben Sie eine E-Mailadresse ein.<br />\n"; $daten_ok = false;}
		if (get_anmeldung($_POST['reg_user'])){ "Nutzer bereits vorhanden.<br />\n"; $daten_ok = false;}
		if (get_anmeldung_email($_POST['reg_mail'])){ "E-Mailadresse bereits registriert.<br />\n"; $daten_ok = false;}
				
		if ($daten_ok)
        {
            $_SESSION['registrierung_ok'] = true;
			insert_registrierung($_POST['reg_user'], $_POST['reg_pswd'], $_POST['reg_mail']);
			print "Registrierung erfolgt";
        }
		print "<br />\n";
    }
	
	
	if(isset($_POST["button_neuerSpieler"]))
	{
		$_SESSION['letzte_seite'] = "neuer_spieler_element";
	}
?>



<!--
############
#  Logout  #
############
-->
<?php
	if (isset($_SESSION['login_name']))
	{
?>
		<p align="right">
			<input type="submit" name="button_logout" value="Logout">
		</p>
<?php
	}
?>



<!--
#################
#	Anmeldung	#
#################
-->
<?php
	if ((!isset($_POST["button_acc_neu"]) and !isset($_SESSION['login_name'])) or isset($_SESSION['registrierung_ok']))
	{
		$_SESSION['letzte_seite'] = "login";
?>
		Benutzername:<input type="text" name="login_user" size="15">
		Passwort:<input type="password" name="login_pswd" size="15">
		<input type="submit" name="button_login" value="anmelden">
		<br />
		<br />
<?php
		if (!isset($_SESSION['registrierung_ok']))
		{
?>
			<input id="temp2" type="submit" name="button_acc_neu" value="Neuer Account">
			<br />
			<br />
<?php
		}
	}
	
<<<<<<< HEAD
	if(isset($_SESSION['login_name']) and $_SESSION['letzte_seite'] == "login")
=======
	if(isset($_SESSION["login_name"]) and isset($_SESSION['letzte_seite']) == "login")
>>>>>>> origin/master
    {
?>
        <h3 align="center">Account</h3>
<?php
    	if ($spieler_zu_account = get_spieler($_SESSION['login_name']))
		{
			$count = 0;
?>			
			<table align="center" border="1px" color="black">
				<tr>
					<td>Nummer</td>
					<td>Name</td>
					<td>Gattung</td>
					<td>Geschlecht</td>
					<td>Level</td>
					<td>Aktueller Ort</td>
				</tr>
<?php			
			while($row = $spieler_zu_account->fetch_array(MYSQLI_NUM))
			{
				$count = $count + 1;
?>			
				<tr>
					<td><?php echo $count ?></td>
					<td><?php echo $row[6] . "<br />\n"; ?></td>
					<td><?php echo $row[3] . "<br />\n"; ?></td>
					<td><?php echo $row[7] . "<br />\n"; ?></td>
					<td><?php echo $row[4] . "<br />\n"; ?></td>
					<td><?php echo $row[5] . "<br />\n"; ?></td>
				</tr>
<?php
			}
?>
			</table>
<?php
			
		}
		else{
			echo "<br />\nKeine Spieler zum Account vorhanden.<br />\n";
		}
?>
		<input type="submit" name="button_neuerSpieler" value="Neuen Spieler anlegen">
		<br />
<?php
    }
?>

<!--
#####################
#	Registrierung	#
#####################
-->
<?php
	if (isset($_POST["button_acc_neu"]) or isset($_POST["button_nochmal"]))
	{
		$_SESSION['letzte_seite'] = "registrierung";
?>
		Benutzername:<input type="text" name="reg_user" size="15">
		Passwort:<input type="password" name="reg_pswd" size="15">
		E-Mail: <input type="email" name="reg_mail" size="30">
		<input type="submit" name="button_register" value="registrieren">
<?php
	}

	
	if(isset($_POST["button_register"]) and !isset($_SESSION['registrierung_ok']))
    {
?>		
		<input type="submit" name="button_acc_neu" value="Nochmal">
		<br />
<?php
    }
?>


<!--	
#######################
#	Hintergrundbild   #
#######################
-->

	<p align="center"><img src="Bilder/Deckblatt.png" height=300px"/></p>

	
<!--
#######################################
#	Neuer Spieler - Element waehlen   #
#######################################
-->
<?php
	if(isset($_POST["button_neuerSpieler"]))
	{
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
	
    
	if(isset($_POST["button_mammutbaum"]) or isset($_POST["button_klippe"]) or isset($_POST["button_wueste"]) or isset($_POST["button_dschungel"]) or isset($_POST["button_kristallhoehle"]) or isset($_POST["button_eissee"]) or isset($_POST["button_sumpf"]) or isset($_POST["button_vulkan"]))
	#if( $_SESSION['letzte_seite'] == "neuer_spieler_startgebiet")
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
		
		insert_spieler($_SESSION['login_name'], $_SESSION['startgebiet'], $_SESSION['gattung'], $_SESSION['spielername'], $_SESSION['geschlecht']);
	}


	if ($debug)
	{
		print "Session-Parameter Ende<br/>";
		print_r($_SESSION);
		print "<br/>";
	}
?>
</form>



