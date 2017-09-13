    <!--
    
    Beispiel: Wenn Button Lehm gedrŸckt wurde, prŸfe ob Erdpunkte 2 und Wasserpunkte 1 ist.
    Wenn Ja, dann erstelle Lehm und schiebe es in den Rucksack rein.
    Wenn Nein, gebe Meldung aus, dass Lehm nicht erstellt wurden konnte.
    
    Noch Machen: Tabelle mit allen Elementen und ihren Bedingungen, sowie ErlŠuterungen darŸber was man damit machen kann.
    
    -->

	<!-- 
	value="                    "
	-->
	
	<style>
				input[type=button]
				{
				    border-color:#885533;
				    outline: none;
				}
	</style>
	
	
<div style="background-color:#884444;width:530px;height:750px;padding:10px;">

<h2>Erdelemente</h2>

<table id="Erdelemente" cellspacing="5px">
    <tr><td colspan="6"><h3>Materieformung</h3></td></tr>
    <tr>
        <td><span title="Lehm"><input type="button" style="background:url(Bilder/Elemente/Lehm.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_lehm" value=""></span></td>
		<!--<td><input type="button" style="background:url(Bilder/Elemente/Lehm.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_lehm" value=""></td>-->
        <td><span title="Tropfstein"><input type="button" style="background:url(Bilder/Elemente/Tropfstein.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_tropfstein" value=""></span></td>
        <td><span title="Treibsand"><input type="button" style="background:url(Bilder/Elemente/Treibsand.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_treibsand" value=""></span></td>
        <td><span title="Sandstein"><input type="button" style="background:url(Bilder/Elemente/Sandstein.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_sandstein" value=""></span></td>
        <td><span title="Kreide"><input type="button" style="background:url(Bilder/Elemente/Kreide.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_kreide" value=""></span></td>
        <td><span title="Ger&ouml;lllawine"><input type="button" style="background:url(Bilder/Elemente/Geroelllawine.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_geršlllawine" value=""></span></td>
    </tr>
    <tr>
        <td><span title="Kohle"><input type="button" style="background:url(Bilder/Elemente/Kohle.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_kohle" value=""></span></td>
        <td><span title="Erd&ouml;l"><input type="button" style="background:url(Bilder/Elemente/Erdoel.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_erdoel" value=""></span></td>
        <td><span title="Ton"><input type="button" style="background:url(Bilder/Elemente/Ton.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_ton" value=""></span></td>
        <td><span title="Keramik"><input type="button" style="background:url(Bilder/Elemente/Keramik.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_keramik" value=""></span></td>
        <td><span title="Kristall"><input type="button" style="background:url(Bilder/Elemente/Kristall.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_kristall" value=""></span></td>
        <td></td>
    </tr>
    <tr>
        <td><span title="Staub"><input type="button" style="background:url(Bilder/Elemente/Staub.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_staub" value=""></span></td>
        <td><span title="Sandh&ouml;hle"><input type="button" style="background:url(Bilder/Elemente/Sandhoehle.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_sandhoehle" value=""></span></td>
        <td><span title="Erdgas"><input type="button" style="background:url(Bilder/Elemente/Erdgas.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_erdgas" value=""></span></td>
        <td>----------------</td>
        <td>---------------></td>
        <td><span title="Asteroid"><input type="button" style="background:url(Bilder/Elemente/Asteroid.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_asteroid" value=""></span></td>
    </tr>
    <tr><td colspan="6"><h3><br>Zerteilung</h3></td></tr>
    <tr>
        <td>----------------</td>
        <td>----------------</td>
        <td>----------------</td>
        <td>----------------</td>
        <td>---------------></td>
        <td><span title="Erdbeben"><input type="button" style="background:url(Bilder/Elemente/Erdbeben.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_erdbeben" value=""></span></td>
    </tr>
    <tr>
        <td>----------------</td>
        <td>----------------</td>
        <td>----------------</td>
        <td>---------------></td>
        <td><span title="Steinschlag"><input type="button" style="background:url(Bilder/Elemente/Steinschlag.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_steinschlag" value=""></span></td>
        <td></td>
    </tr>
     <tr><td colspan="6"><h3><br>Verjagen</h3></td></tr>
    <tr>
        <td>----------------</td>
        <td>----------------</td>
        <td>---------------></td>
        <td><span title="Wanderd&uuml;ne"><input type="button" style="background:url(Bilder/Elemente/Wanderduene.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_wanderduene" value=""></span></td>
        <td></td>
        <td></td>
    </tr>
    
</table>
</div>