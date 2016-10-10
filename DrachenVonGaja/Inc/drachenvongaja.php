<?php
	session_start();
	
	include("navi.inc.php");
	include("db_funktionen.php");
	
	if (!isset($_SESSION['login_name']))
	{
?>
		<script type="text/javascript">
			window.location.href = "../index.php"
		</script>
<?php
	}
	else
	{
		echo "Los geht's ...<br/>";
		echo "Der Spieler [spieler_id=" . $_SESSION['spieler_id'] . "] ist über Account [" . $_SESSION['login_name'] . "] eingeloggt. <br/>";
	}
	
	if ($spielerdaten = get_spieler($_SESSION['spieler_id']))
	{
		$id = $spielerdaten[0]; 
		$account_id = $spielerdaten[1]; 
		$bilder_id = $spielerdaten[2]; 
		$gattung_id = $spielerdaten[3]; 
		$level_id = $spielerdaten[4]; 
		$gebiet_id = $spielerdaten[5]; 
		$name = $spielerdaten[6]; 
		$geschlecht = $spielerdaten[7]; 
		$staerke = $spielerdaten[8]; 
		$intelligenz = $spielerdaten[9]; 
		$magie = $spielerdaten[10];
		$element_feuer = $spielerdaten[11];
		$element_wasser = $spielerdaten[12];
		$element_erde = $spielerdaten[13];
		$element_luft = $spielerdaten[14];
		$gesundheit = $spielerdaten[15];
		$max_gesundheit = $spielerdaten[16];
		$energie = $spielerdaten[17];
		$max_energie = $spielerdaten[18];
		$balance = $spielerdaten[19];
	}
	else
	{
		echo "Keine Spielerdaten gefunden.<br/>";
	}
	
	
	# Für Fehlerfreien Durchlauf
	$speipunkte = 5;
	$flugpunkte = 5;
	
	$level7 = 7;
	$level6 = 6;
	$level5 = 5;
	$level4 = 4;
	$level3 = 3;
	$level2 = 2;
	$level1 = 1;
	
	$erd1 = 1; $wasser1 = 1; $feuer1 = 1; $luft1 = 1;
	$erd2 = 2; $wasser2 = 2; $feuer2 = 2; $luft2 = 2;
	$erd3 = 3; $wasser3 = 3; $feuer3 = 3; $luft3 = 3;
	$erd4 = 4; $wasser4 = 4; $feuer4 = 4; $luft4 = 4;
	$erd5 = 5; $wasser5 = 5; $feuer5 = 5; $luft5 = 5;
	
	
	
	
	
	
?>



<div id="obere_Leiste">
    <table align="center"><tr><td>St&auml;rke <?php echo $staerke ?></td><td>Intelligenz <?php echo $intelligenz ?></td><td>Magie <?php echo $magie ?></td></tr></table>
</div>
    
   
<div id="mitte">
    <p align="center" style="margin-top:0px;margin-bottom:0px;"><img src="../Platzhalter_gebiete/Grosz/<?php bild_zu_startgebiet($gebiet_id); ?>" width="60%" height="60%"/></p> 
    
</div>

<div id="untere_Leiste">
             <table align="center"><tr><td>Feuer Speien <?php echo $speipunkte ?></td><td>Karte</td><td>Fliegen <?php echo $flugpunkte ?></td></tr></table>
</div>


    <div id="l7"><?php echo $level7 ?></div>
    <div id="l6"><?php echo $level6 ?></div>
    <div id="l5"><?php echo $level5 ?></div>
    <div id="l4"><?php echo $level4 ?></div>
    <div id="l3"><?php echo $level3 ?></div>
    <div id="l2"><?php echo $level2 ?></div>
    <div id="l1"><?php echo $level1 ?></div>
    
    <div id="elemente">
        <table cellpadding="1px">
            <tr><td bgcolor="darkgrey">E</td><td colspan="3" height="15px"></td></tr>
            <tr>
                <td bgcolor="darkgrey">L</td><td height="15px" width="50px" bgcolor="brown">Erde</td><td width="50px" bgcolor="blue">Wasser</td><td width="50px" bgcolor="red">Feuer</td><td width="50px" bgcolor="lightblue">Luft</td>
            </tr>
            <tr><td bgcolor="darkgrey">E</td><td height="15px" bgcolor="brown"><?php echo $erd1 ?></td><td bgcolor="blue"><?php echo $wasser1 ?></td><td bgcolor="red"><?php echo $feuer1 ?></td><td bgcolor="lightblue"><?php echo $luft1 ?></td></tr>
            <tr><td bgcolor="darkgrey">M</td><td height="15px" bgcolor="brown"><?php echo $erd2 ?></td><td bgcolor="blue"><?php echo $wasser2 ?></td><td bgcolor="red"><?php echo $feuer2 ?></td><td bgcolor="lightblue"><?php echo $luft2 ?></td></tr>
            <tr><td bgcolor="darkgrey">E</td><td height="15px" bgcolor="brown"><?php echo $erd3 ?></td><td bgcolor="blue"><?php echo $wasser3 ?></td><td bgcolor="red"><?php echo $feuer3 ?></td><td bgcolor="lightblue"><?php echo $luft3 ?></td></tr>
            <tr><td bgcolor="darkgrey">N</td><td height="15px" bgcolor="brown"><?php echo $erd4 ?></td><td bgcolor="blue"><?php echo $wasser4 ?></td><td bgcolor="red"><?php echo $feuer4 ?></td><td bgcolor="lightblue"><?php echo $luft4 ?></td></tr>
            <tr><td bgcolor="darkgrey">T</td><td height="15px" bgcolor="brown"><?php echo $erd5 ?></td><td bgcolor="blue"><?php echo $wasser5 ?></td><td bgcolor="red"><?php echo $feuer5 ?></td><td bgcolor="lightblue"><?php echo $luft5 ?></td></tr>
            <tr><td bgcolor="darkgrey">E</td><td height="15px"></td><td colspan="3"></td></tr>
        </table>
        
         
    </div>
    
