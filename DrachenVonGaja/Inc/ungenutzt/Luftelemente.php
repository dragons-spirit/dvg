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

<h2>Luftelemente</h2>

<table id="Luftelemente">
    
    <tr><td colspan="6"><h3>Luftschaden</h3></td></tr>
    <tr>
        <td>---------------></td>
        <td><span title="Korrosion"><input type="button" style="background:url(Bilder/Elemente/Korrosion.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_korrosion" value=""></span></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>----------------</td>
        <td>----------------</td>
        <td>---------------></td>
        <td><span title="Tornado"><input type="button" style="background:url(Bilder/Elemente/Tornado.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_tornado" value=""></span></td>
        <td><span title="Orkan"><input type="button" style="background:url(Bilder/Elemente/Orkan.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_orkan" value=""></span></td>
        <td><span title="Eissturm"><input type="button" style="background:url(Bilder/Elemente/Eissturm.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_eissturm" value=""></span></td>
    </tr>
    <tr><td colspan="6"><h3><br>Luftversteck</h3></td></tr>
    <tr>
        <td>----------------</td>
        <td>----------------</td>
        <td>----------------</td>
        <td>---------------></td>
        <td><span title="W&uuml;stenwind"><input type="button" style="background:url(Bilder/Elemente/Wuestenwind.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_wuestenwind" value=""></span></td>
        <td></td>
    </tr>
    <tr>
        <td>----------------</td>
        <td>---------------></td>
        <td><span title="Smog"><input type="button" style="background:url(Bilder/Elemente/Smog.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_smog" value=""></span></td>
        <td>---------------></td>
        <td><span title="Erdloch"><input type="button" style="background:url(Bilder/Elemente/Erdloch.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_erdloch" value=""></span></td>
        <td></td>
    </tr>
    <tr><td colspan="6"><h3><br>Sicht</h3></td></tr>
    <tr>
        <td>----------------</td>
        <td>---------------></td>
        <td><span title="Lichtwelle"><input type="button" style="background:url(Bilder/Elemente/Lichtwelle.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_lichtwelle" value=""></span></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr><td colspan="6"><h3><br>Verbrennung</h3></td></tr>
    <tr>
        <td>----------------</td>
        <td>----------------</td>
        <td>---------------></td>
        <td><span title="Funkenflug"><input type="button" style="background:url(Bilder/Elemente/Funkenflug.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_funkenflug" value=""></span></td>
        <td>---------------></td>
        <td><span title="Feuerschnei&szlig;e"><input type="button" style="background:url(Bilder/Elemente/Feuerschneisse.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_feuerschneisse" value=""></span></td>
    </tr>
    <tr><td colspan="6"><h3><br>Heilung</h3></td></tr>
    <tr>
        <td><span title="Hauch"><input type="button" style="background:url(Bilder/Elemente/Hauch.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_hauch" value=""></span></td>
        <td><span title="Feueratem"><input type="button" style="background:url(Bilder/Elemente/Feueratem.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_feueratem" value=""></span></td>
        <td></td><td></td>
        <td></td>
        <td></td>
    </tr>
    <tr><td colspan="6"><h3><br>Verjagen</h3></td></tr>
    <tr>
        <td><span title="B&ouml;e"><input type="button" style="background:url(Bilder/Elemente/Boee.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_boee" value=""></span></td>
        <td><span title="Windhose"><input type="button" style="background:url(Bilder/Elemente/Windhose.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_windhose" value=""></span></td>
        <td><span title="Gewitter"><input type="button" style="background:url(Bilder/Elemente/Gewitter.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_gewitter" value=""></span></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td><span title="Donner"><input type="button" style="background:url(Bilder/Elemente/Donner.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_donner" value=""></span></td>
        <td>----------------</td>
        <td>----------------</td>
        <td><span title="Windsto&szlig;"><input type="button" style="background:url(Bilder/Elemente/Windstoss.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_windstoss" value=""></span></td>
        <td>---------------></td>
        <td><span title="Sandsturm"><input type="button" style="background:url(Bilder/Elemente/Sandsturm.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_sandsturm" value=""></span></td>
    </tr>
     
</table>
