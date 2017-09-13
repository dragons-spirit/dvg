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

<div style="background-color:#226688;width:600px;height:1650px;padding:10px;">
     
<h2>Wasserelemente</h2>

<table id="Wasserelemente">
    
     <tr><td colspan="6"><h3>Materieformung</h3></td></tr>
    <tr>
        <td><span title="Schlamm"><input type="button" style="background:url(Bilder/Elemente/Schlamm.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_schlamm" value=""></span></td>
        <td><span title="Salzwasser"><input type="button" style="background:url(Bilder/Elemente/Salzwasser.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_salzwasser" value=""></span></td>
        <td><span title="Gletscher"><input type="button" style="background:url(Bilder/Elemente/Gletscher.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_gletscher" value=""></span></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>----------------</td>
        <td>----------------</td>
        <td>---------------></td>
        <td><span title="Wolke"><input type="button" style="background:url(Bilder/Elemente/Wolke.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_wolke" value=""></span></td>
        <td></td>
        <td></td>
    </tr>
     <tr><td colspan="6"><h3><br>Wasserschaden</h3></td></tr>
    <tr>
        <td>----------------</td>
        <td>----------------</td>
        <td>---------------></td>
        <td><span title="Tsunami"><input type="button" style="background:url(Bilder/Elemente/Tsunami.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_tsunami" value=""></span></td>
        <td>---------------></td>
        <td><span title="Mor&auml;ne"><input type="button" style="background:url(Bilder/Elemente/Moraene.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_moraene" value=""></span></td>
    </tr>
    <tr>
        <td>----------------</td>
        <td>----------------</td>
        <td>---------------></td>
        <td><span title="Monsun"><input type="button" style="background:url(Bilder/Elemente/Monsun.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_monsun" value=""></span></td>
        <td><span title="Hurrikan"><input type="button" style="background:url(Bilder/Elemente/Hurrikan.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_hurrikan" value=""></span></td>
        <td></td>
    </tr>
    <tr>
        <td>----------------</td>
        <td>----------------</td>
        <td>----------------</td>
        <td>----------------</td>
        <td>---------------></td>
        <td><span title="Schneesturm"><input type="button" style="background:url(Bilder/Elemente/Schneesturm.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_schneesturm" value=""></span></td>
    </tr>
    <tr><td colspan="6"><h3><br>Wasserversteck</h3></td></tr>
    <tr>
        <td><span title="Wasserdampf"><input type="button" style="background:url(Bilder/Elemente/Wasserdampf.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_wasserdampf" value=""></span></td>
        <td><span title="Nebel"><input type="button" style="background:url(Bilder/Elemente/Nebel.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_nebel" value=""></span></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr><td colspan="6"><h3><br>Sicht</h3></td></tr>
    <tr>
        <td>----------------</td>
        <td>---------------></td>
        <td><span title="Regenbogen"><input type="button" style="background:url(Bilder/Elemente/Regenbogen.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_regenbogen" value=""></span></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr><td colspan="6"><h3><br>Verbrennung</h3></td></tr>
    <tr>
        <td>----------------</td>
        <td>----------------</td>
        <td>----------------</td>
        <td>----------------</td>
        <td>---------------></td>
        <td><span title="Geysir"><input type="button" style="background:url(Bilder/Elemente/Geysir.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_geysir" value=""></span></td>
    </tr>
    <tr><td colspan="6"><h3><br>Heilung</h3></td></tr>
    <tr>
        <td><span title="Regenschauer"><input type="button" style="background:url(Bilder/Elemente/Regenschauer.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_regenschauer" value=""></span></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>----------------</td>
        <td>----------------</td>
        <td>----------------</td>
        <td>---------------></td>
        <td><span title="Hei&szlig;e Quelle"><input type="button" style="background:url(Bilder/Elemente/Heisse_Quelle.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_heisse_quelle" value=""></span></td>
        <td></td>
    </tr>
    <tr>
        <td>----------------</td>
        <td>----------------</td>
        <td>---------------></td>
        <td><span title="Wasserfall"><input type="button" style="background:url(Bilder/Elemente/Wasserfall.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_wasserfall" value=""></span></td>
        <td></td>
        <td></td>
    </tr>
    <tr><td colspan="6"><h3><br>Zerteilung</h3></td></tr>
    <tr>
        <td>---------------></td>
        <td><span title="Wasserstrahl"><input type="button" style="background:url(Bilder/Elemente/Wasserstrahl.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_wasserstrahl" value=""></span></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr><td colspan="6"><h3><br>Verjagen</h3></td></tr>
    <tr>
        <td>----------------</td>
        <td>---------------></td>
        <td><span title="Wasserwelle"><input type="button" style="background:url(Bilder/Elemente/Wasserwelle.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_wasserwelle" value=""></span></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    
</table>
</div>