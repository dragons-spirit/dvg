<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<?php
	session_start();
	include("Inc/funktionen_system.php");
	$_SESSION['browser'] = get_browser_name($_SERVER['HTTP_USER_AGENT']);
	include("Inc/connect.inc.php");
	$connect_db_dvg = open_connection($default_user, $default_pswd, $default_host, $default_db);
?>

<html>
	<head>
		<meta http-equiv="Content-Language" content="de">
		<meta http-equiv="Content-Script-Type" content="text/javascript">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<meta http-equiv="last-modified" content="11.05.2016 21:00:00" >
		<meta name="description" content="Drachen von Gaja - Browsergame">
		<meta name="keywords" content="Drachen, Elemente, Browsergame, Browserspiel,">
		<meta name="Author" content="Tina Schmidtbauer, Hendrik Matthes" >
		<meta charset="utf-8">
	
		<link rel="stylesheet" type="text/css" href="index.css">
		<script src="index.js" type="text/javascript"></script>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<title>Drachen von Gaja</title>
		<?php
		if($_SESSION['browser'] == "Opera"){
		?>
			<style>
				head {font-family:Lucida Calligraphy,Georgia,fantasy,EG Dragon Caps; font-size:smaller;}
				body {font-family:Lucida Calligraphy,Georgia,fantasy,EG Dragon Caps; font-size:smaller;}
				input {outline:none;}
                input[type=submit] {font-family:Lucida Calligraphy,Georgia,fantasy,EG Dragon Caps; font-size:smaller;}
				input[type=button] {font-family:Lucida Calligraphy,Georgia,fantasy,EG Dragon Caps; font-size:smaller;}
			</style>
		<?php
		}
		?>
		
    </head>
    
    <body>
        <div id="rahmen">
			<div id="header">
				<h1 align="center" style="font-family:Elementary Gothic,EG Dragon Caps;">Drachen von Gaja</h1>  
			</div>
			
			<div id="anmeldung" style="overflow-y: auto;">

<?php
		include("Inc/klassen.php");
		if (isset($_SESSION['account_id'])){
			$konfig = new Konfig($_SESSION['account_id']);
		} else {
			$konfig = new Konfig();
		}
		include("Inc/db_funktionen.php");
		include("Inc/zusammenfassung.inc.php");
		close_connection($connect_db_dvg);
?>	
			</div>
			
			<div id="footer">
				<h3 align="center">Impressum</h3>
 				<p align="center" style="background-color:black">Grafik & Progammierung: Tina Schmidtbauer || Programmierung & Datenbankverwaltung: Hendrik Matthes</p>
			</div>
        </div>
    </body>
    
</html>