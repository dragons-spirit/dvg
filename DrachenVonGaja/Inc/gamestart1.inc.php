
<?php
/*
$staerke = mysql_query("SELECT staerke FROM Spieler") or die (mysql_error());
$intelligenz = mysql_query("SELECT intelligenz FROM Spieler") or die (mysql_error());
$magie = mysql_query("SELECT intelligenz FROM Spieler") or die (mysql_error());
$level1 = mysql_query("SELECT level1 FROM Spieler") or die (mysql_error());
$level2 = mysql_query("SELECT level2 FROM Spieler") or die (mysql_error());
$level3 = mysql_query("SELECT level3 FROM Spieler") or die (mysql_error());
$level4 = mysql_query("SELECT level4 FROM Spieler") or die (mysql_error());
$level5 = mysql_query("SELECT level5 FROM Spieler") or die (mysql_error());
$level6 = mysql_query("SELECT level6 FROM Spieler") or die (mysql_error());
$level7 = mysql_query("SELECT level7 FROM Spieler") or die (mysql_error());
$userdragon = mysql_query("SELECT userdragon FROM Spieler") or die (mysql_error());
$usergebiet = mysql_query("SELECT usergebiet FROM Spieler") or die (mysql_error());
$erd1 = mysql_query("SELECT erd1 FROM Spieler") or die (mysql_error());
$erd2 = mysql_query("SELECT erd2 FROM Spieler") or die (mysql_error());
$erd3 = mysql_query("SELECT erd3 FROM Spieler") or die (mysql_error());
$erd4 = mysql_query("SELECT erd4 FROM Spieler") or die (mysql_error());
$erd5 = mysql_query("SELECT erd5 FROM Spieler") or die (mysql_error());
$wasser1 = mysql_query("SELECT wasser1 FROM Spieler") or die (mysql_error());
$wasser2 = mysql_query("SELECT wasser2 FROM Spieler") or die (mysql_error());
$wasser3 = mysql_query("SELECT wasser3 FROM Spieler") or die (mysql_error());
$wasser4 = mysql_query("SELECT wasser4 FROM Spieler") or die (mysql_error());
$wasser5 = mysql_query("SELECT wasser5 FROM Spieler") or die (mysql_error());
$feuer1 = mysql_query("SELECT feuer1 FROM Spieler") or die (mysql_error());
$feuer2 = mysql_query("SELECT feuer2 FROM Spieler") or die (mysql_error());
$feuer3 = mysql_query("SELECT feuer3 FROM Spieler") or die (mysql_error());
$feuer4 = mysql_query("SELECT feuer4 FROM Spieler") or die (mysql_error());
$feuer5 = mysql_query("SELECT feuer5 FROM Spieler") or die (mysql_error());
$luft1 = mysql_query("SELECT luft1 FROM Spieler") or die (mysql_error());
$luft2 = mysql_query("SELECT luft2 FROM Spieler") or die (mysql_error());
$luft3 = mysql_query("SELECT luft3 FROM Spieler") or die (mysql_error());
$luft4 = mysql_query("SELECT luft4 FROM Spieler") or die (mysql_error());
$luft5 = mysql_query("SELECT luft5 FROM Spieler") or die (mysql_error());
$speipunkte = mysql_query("SELECT speipunkte FROM Spieler") or die (mysql_error());
$flugpunkte = mysql_query("SELECT flugpunkte FROM Spieler") or die (mysql_error());
*/

  include("Inc/testspieldaten.inc.php");

?>

<div id="obere_Leiste">
    <table align="center"><tr><td>St&auml;rke <?php echo $staerke ?></td><td>Intelligenz <?php echo $intelligenz ?></td><td>Magie <?php echo $magie ?></td></tr></table>  
</div>
    
   
<div id="mitte">
    <p align="center" style="margin-top:0px;margin-bottom:0px;"><img src="Bilder/Vulkangebiet_grosz.png" width="60%" height="60%"/></p>
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
    
