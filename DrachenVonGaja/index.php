<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<?php
	session_start();
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
	
		<link rel="stylesheet" type="text/css" href="index.css">
		<script src="index.js" type="text/javascript"></script>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
			<title>Drachen von Gaja</title>
    </head>
    
    <body>
        <div id="rahmen">
			<div id="header">
				<h1 align="center">Drachen von Gaja</h1>  
			</div>
			
			<div id="anmeldung" style="overflow:scroll;">
                            
                                
<?php
                include("Inc/navi.inc.php");
				include("Inc/db_funktionen.php");
				include("Inc/zusammenfassung.inc.php");
?>	
			</div>
                
           	<div id="footer">
				<h3 align="center">Impressum</h3>
 
    <p align="center" style="background-color:black">Grafik & Progammierung: Tina Schmidtbauer || Programmierung & Datenbankverwaltung: Hendrik Matthes</p>

    


			</div>
        </div>
    </body>
    
</html>