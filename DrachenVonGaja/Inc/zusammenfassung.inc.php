<form id="zusammenfassung" method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">

<?php
	
	##### Ausgabe Session-Parameter wenn gewünscht #####
	if ($debug)
	{
		print "Session-Parameter Beginn<br/>";
		print_r($_SESSION);
		print "<br/>";
	}
	
	
	##### Logout löscht alle Session-Parameter #####
	if (isset($_POST["button_logout"]))
	{
		if (isset($_SESSION["account_id"])) {
			$session = new Session($_SESSION["account_id"], true);
			$session->beenden_logout();
		}
		session_unset();
	}
	
	
	##### Adminbereich für komfortablen Datenbankzugriff und Administration #####
	if (isset($_POST["button_admin"]) and isset($_SESSION['login_name']))
	{
		$_SESSION['letzte_seite'] = "adminbereich_login";
		$ergebnis = get_anmeldung($_SESSION['login_name']);
        if (!$ergebnis)
        {
            print "Kein angemeldeter Benutzer beim Klick auf \"Adminbereich\"?";
        }
        else
        {
            if ($ergebnis[5] == 1)
            {
				print "Auf in den Loginbereich ... <br/>";
?>
				<script type="text/javascript">
					window.location.href = "Inc/admin.php"
				</script>
				<br/>
<?php            
			}
            else
            {
                print "Adminbereich nur für Admins !!! <br/>";
            }
        }
        print "<br />\n";

	}
	
	
	##### Login löst Passwortprüfung und bei Erfolg Sicherung der Logindaten aus #####
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
				$_SESSION["login_rolle"] = $ergebnis[5];
				$_SESSION["account_id"] = $ergebnis[0];
				unset ($_SESSION['registrierung_ok']);
				$jetzt = new DateTime();
				$max_gueltigkeit = new DateInterval("PT".$konfig->get("gueltigkeit_session")."M");
				$gueltig_bis = (new DateTime)->add($max_gueltigkeit);
				$session = new Session([0, $ergebnis[0], $ergebnis[5], $jetzt->format('Y-m-d H:i:s'), $gueltig_bis->format('Y-m-d H:i:s'), 1, $_SERVER["REMOTE_ADDR"]]);
				print "Anmeldung erfolgreich";
            }
            else
            {
                print "Du kommst hier nicht rein, aber du kannst es gern noch einmal versuchen.";
            }
        }
        print "<br />\n";
    }

	
	##### Registrierung prüft Daten und legt bei Erfolg neuen Account an #####
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
	
	
	##### Neuer Spieler aktualisiert letzte Seite #####
	if(isset($_POST["button_neuerSpieler"]))
	{
		$_SESSION['letzte_seite'] = "neuer_spieler_element";
	}
	
	
	##### Spieler anlegen setzt Geschlecht und fügt neuen Spieler ein #####
	if(isset($_POST["button_spieler_angelegt"]))
	{
		$_SESSION['spielername'] = $_POST["playname"];
		if ($_POST['geschlecht'] == "Weiblich"){
			$_SESSION['geschlecht'] = "W";
		} else {
			$_SESSION['geschlecht'] = "M";
		}
		
		insert_spieler($_SESSION['login_name'], $_SESSION['startgebiet'], $_SESSION['gattung'], $_SESSION['spielername'], $_SESSION['geschlecht']);
	}
	
	
	##### Spielerlogin und Weiterleitung zur Spielseite #####
	if(isset($_POST["button_spielerlogin"]))
	{
		$_SESSION['spieler_id'] = $_POST["button_spielerlogin"];
?>
		<script type="text/javascript">
			window.location.href = "Inc/drachenvongaja.php"
		</script>
<?php
	}
?>



<!--
##########################
#  Logout & Adminbereich #
##########################
-->
<?php
	if (isset($_SESSION['login_name']))
	{
?>
		<p align="right">
			<button class="button_standard" type="submit" name="button_admin" value="Adminbereich">Adminbereich</button>
			<button class="button_standard" type="submit" name="button_logout" value="Logout">Logout</button>
		</p>
<?php
	}

###################################
#	Vorhandenen Spieler löschen   #
###################################

if(isset($_POST["button_spielerloeschen_endgueltig"]))
{
    delete_Spieler($_POST["button_spielerloeschen_endgueltig"]);
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
		Benutzername: <input type="text" name="login_user" size="15">
		Passwort: <input type="password" name="login_pswd" size="15">
		<button class="button_standard" type="submit" name="button_login" value="anmelden">anmelden</button>
<?php
		if (!isset($_SESSION['registrierung_ok']))
		{
?>
			<button class="button_standard" id="temp2" type="submit" name="button_acc_neu" value="Neuer Account">Neuer Account</button>
<?php
		}
?>
		<!-- Hintergrundbild der Startseite-->
                
        <p align="center"><img src="Bilder/Deckblatt.png" height="500px"/></p>
		<p align="center"><a class="storylink" href="Inc/story.php" alt="Storylink"/>Wer sind die Drachen von Gaja ?</a></p>

<?php
	}
	
	if(isset($_SESSION['login_name']) and ($_SESSION['letzte_seite'] == "login" or $_SESSION['letzte_seite'] == "neuer_spieler_name_geschlecht" or $_SESSION['letzte_seite'] == "adminbereich_login"))
    {
?>
        <h3 align="center">Account</h3>
<?php
    	if ($spieler_zu_account = get_spieler_login($_SESSION['login_name']))
		{
			$count = 0; 
?>			
			<table align="center" border="1px" color="black">
				<tr>
					<td>Nummer</td>
					<td>Name</td>
                    <td>Bild</td>
					<td>Gattung</td>
					<td>Geschlecht</td>
					<td>Level</td>
					<td>Aktueller Ort</td>
                    <td>LÖSCHEN</td>
				</tr>
<?php		
			$spieler = new LoginSpieler();
			while($row = $spieler_zu_account->fetch_array(MYSQLI_NUM))
			{
				$spieler->set($row);
				$count = $count + 1;
?>			
				<tr>
					<td><?php echo $count ?></td>
					<td><?php echo $spieler->name . "<br />\n";?></td>
                    <td style="background-image:url(<?php echo pfad_fuer_style(get_bild_zu_id($spieler->bilder_id)); ?>); background-repeat:no-repeat; background-size:contain;">
						<input type="submit" style="height:94px; width:150px; opacity: 0.0;" alt="Spieler auswählen" name="button_spielerlogin" value="<?php echo $spieler->id;?>">
					</td>
					<td><?php echo $spieler->gattung . "<br />\n"; ?></td>
					<td><?php echo $spieler->geschlecht . "<br />\n"; ?></td>
					<td><?php echo $spieler->level_id . "<br />\n"; ?></td>
					<td><?php echo $spieler->startgebiet . "<br />\n"; ?></td>
                    <td align="center">
						<input type="button" id="<?php echo 'b_sp_loe_' . $spieler->id . '_1' ?>" name="button_spielerloeschen" value="Ja" onclick="<?php echo 'buttonwechsel(' . $row[0] . ')' ?>" >
						<input type="submit" id="<?php echo 'b_sp_loe_' . $spieler->id . '_2' ?>" name="button_spielerloeschen_endgueltig" value="<?php echo $spieler->id; ?>" style="visibility:hidden;">
					</td>
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
		<button class="button_standard" type="submit" name="button_neuerSpieler" value="Neuen Spieler anlegen">Neuen Spieler anlegen</button>
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
		<button class="button_standard" type="submit" name="button_register" value="registrieren">registrieren</button>
<?php
	}

	
	if(isset($_POST["button_register"]) and !isset($_SESSION['registrierung_ok']))
    {
?>		
		<button class="button_standard" type="submit" name="button_acc_neu" value="Nochmal">Nochmal</button>
		<br />
<?php
    }
?>
	
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
		<table align="center" cellspacing="10px">
			<tr>
				<td align="center"><input type="submit" style="background-image:url(./Bilder/Erdei_klein.png); background-color:white; height:110px; width:90px; background-repeat:no-repeat;" alt="Erdelement" name="button_erdelement" value=""></td>
				<td align="center"><input type="submit" style="background-image:url(./Bilder/Wasserei_klein.png); background-color:white; height:110px;width:90px; background-repeat:no-repeat;" alt="Wasserelement" name="button_wasserelement" value=""></td>
				<td align="center"><input type="submit" style="background-image:url(./Bilder/Feuerei_klein.png); background-color:white; height:110px;width:90px; background-repeat:no-repeat;" alt="Feuerelement" name="button_feuerelement" value=""></td>
				<td align="center"><input type="submit" style="background-image:url(./Bilder/Luftei_klein.png); background-color:white; height:110px;width:90px; background-repeat:no-repeat;" alt="Luftelement" name="button_luftelement" value=""></td>
			</tr>
			<tr>
				<td align="center"><b>Erddrache</b><br>Wächter über die Dunkelheit, <br>der in den Tiefen lebt.</td>
				<td align="center"><b>Wasserdrache</b><br>Wächter über die Kälte, <br>der viel heilen kann.</td>
				<td align="center"><b>Feuerdrache</b><br>Wächter über die Hitze, <br>der viel zerstören kann.</td>
				<td align="center"><b>Luftdrache</b><br>Wächter über das Licht, <br>der in der Höhe lebt.</td>
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
		echo "Du hast das Erdelement ausgewählt!";
        $_SESSION['element'] = "Erde";
		$_SESSION['gattung'] = "Erddrache";
        #Zeige Gebiete fuer Erdelement
?>
        <h3 align="center">Auswahl des Heimatgebietes</h3>
        <table align="center">
            <tr>
                <td>Karmana<br><input type="submit" style="background-image:url(./Platzhalter_gebiete/Klein/dschungel_klein.jpg); width:150px; height:94px; background-repeat:no-repeat;" alt="Karmana" name="button_dschungel" value="                                             "></td>
                <td>Koban<br><input type="submit" style="background-image:url(./Platzhalter_gebiete/Klein/kristallhoehle_klein.jpg); width:150px; height:94px; background-repeat:no-repeat;" alt="Koban" name="button_kristallhoehle" value="                                             "></td>
            </tr>            
        </table>
 <?php
    }

    if(isset($_POST["button_wasserelement"]))
    {
		$_SESSION['letzte_seite'] = "neuer_spieler_startgebiet";
		echo "Du hast das Wasserelement ausgewählt!";
		$_SESSION['element'] = "Wasser";
		$_SESSION['gattung'] = "Wasserdrache";
        #Zeige Gebiete fuer Wasserelement
?>
        <h3 align="center">Auswahl des Heimatgebietes</h3>
        <table align="center">
            <tr>
				<td>Irakon<br><input type="submit" style="background-image:url(./Platzhalter_gebiete/Klein/eissee_klein.jpg); width:150px; height:94px; background-repeat:no-repeat;" alt="Irakon" name="button_eissee" value="                                             "></td>
				<td>Ormanko<br><input type="submit" style="background-image:url(./Platzhalter_gebiete/Klein/sumpf_klein.jpg);width:150px; height:94px; background-repeat:no-repeat;" alt="Ormanko" name="button_sumpf" value="                                             "></td>
            </tr>
        </table>
<?php
    }

    if(isset($_POST["button_feuerelement"]))
    {
		$_SESSION['letzte_seite'] = "neuer_spieler_startgebiet";
		echo "Du hast das Feuerelement ausgewählt!";
		$_SESSION['element'] = "Feuer";
		$_SESSION['gattung'] = "Feuerdrache";
        #Zeige Gebiete fuer Feuerelement
?>
        <h3 align="center">Auswahl des Heimatgebietes</h3>
        <table align="center">
            <tr>
                <td>Rapano<br><input type="submit" style="background-image:url(./Platzhalter_gebiete/Klein/vulkan_klein.jpg); width:150px; height:94px; background-repeat:no-repeat;" alt="Rapano" name="button_vulkan" value="                                      "></td>
                <td>Aktor<br><input type="submit" style="background-image:url(./Platzhalter_gebiete/Klein/wueste_klein.jpg); width:150px; height:94px; background-repeat:no-repeat;" alt="Aktor" name="button_wueste" value="                                      "></td>
            </tr>
        </table>
<?php
	}

    if(isset($_POST["button_luftelement"]))
    {
		$_SESSION['letzte_seite'] = "neuer_spieler_startgebiet";
		echo "Du hast das Luftelement ausgewählt!";
		$_SESSION['element'] = "Luft";
		$_SESSION['gattung'] = "Luftdrache";
        #Zeige Gebiete fuer Luftelement
?>
        <h3 align="center">Auswahl des Heimatgebietes</h3>
        <table align="center">
            <tr>
                <td>Rastagy<br><input type="submit" style="background-image:url(./Platzhalter_gebiete/Klein/klippe_klein.jpg); width:150px; height:94px; background-repeat:no-repeat;" alt="Rastagy" name="button_klippe" value="                                             "></td>
                <td>Everleen<br><input type="submit" style="background-image:url(./Platzhalter_gebiete/Klein/mammutbaum_klein.jpg); width:150px; height:94px; background-repeat:no-repeat;" alt="Everleen" name="button_mammutbaum" value="                                             "></td>
            </tr>
        </table>
<?php
	}

	if(isset($_POST["button_dschungel"])) $_SESSION['startgebiet'] = "Karmana";
	if(isset($_POST["button_kristallhoehle"])) $_SESSION['startgebiet'] = "Koban";
	if(isset($_POST["button_eissee"])) $_SESSION['startgebiet'] = "Irakon";
	if(isset($_POST["button_sumpf"])) $_SESSION['startgebiet'] = "Ormanko";
	if(isset($_POST["button_vulkan"])) $_SESSION['startgebiet'] = "Rapano";
	if(isset($_POST["button_wueste"])) $_SESSION['startgebiet'] = "Aktor";
    if(isset($_POST["button_klippe"])) $_SESSION['startgebiet'] = "Rastagy";
	if(isset($_POST["button_mammutbaum"])) $_SESSION['startgebiet'] = "Everleen"; 


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
			<tr><td>Name: <input type="text" name="playname" size="15"></td><td>
			<select name="geschlecht">
				<option value="Weiblich" name="gesch1">Weiblich</option>
				<option value="Maennlich" name="gesch2">Männlich</option>
			</select>
			<tr><td><button class="button_standard" type="submit" name="button_spieler_angelegt" value="Spieler erstellen">Spieler erstellen</button></td></tr>
		</table>
<?php
	}
	
	
	
	if ($debug)
	{
		print "Session-Parameter Ende<br/>";
		print_r($_SESSION);
		print "<br/>";
	}
?>
</form>