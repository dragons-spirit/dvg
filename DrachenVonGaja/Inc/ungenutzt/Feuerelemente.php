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

<h2>Feuerelemente</h2>

<table id="Feuerelemente">
    <tr><td colspan="6"><h3>Materieformung</h3></td></tr>
    <tr>
        <td><span title="Asche"><input type="button" style="background:url(Bilder/Elemente/Asche.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_asche" value=""></span></td>
        <td>---------------></td>
        <td><span title="Glas"><input type="button" style="background:url(Bilder/Elemente/Glas.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_glas" value=""></span></td>
        <td><span title="Metall"><input type="button" style="background:url(Bilder/Elemente/Metall.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_metall" value=""></span></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td><span title="Alkohol"><input type="button" style="background:url(Bilder/Elemente/Alkohol.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_alkohol" value=""></span></td>
        <td>---------------></td>
        <td><span title="S&auml;ure"><input type="button" style="background:url(Bilder/Elemente/Saeure.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_saeure" value=""></span></td>
        <td>---------------></td>
        <td><span title="Gift"><input type="button" style="background:url(Bilder/Elemente/Gift.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_gift" value=""></span></td>
        <td></td>
    </tr>
    <tr><td colspan="6"><h3><br>Sicht</h3></td></tr>
    <tr>
        <td><span title="Funke"><input type="button" style="background:url(Bilder/Elemente/Funke.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_funke" value=""></td>
        <td><span title="Flamme"><input type="button" style="background:url(Bilder/Elemente/Flamme.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_flamme" value=""></td>
        <td>---------------></td>
        <td><span title="Lichtstrahl"><input type="button" style="background:url(Bilder/Elemente/Lichtstrahl.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_lichtstrahl" value=""></td>
        <td></td>
        <td></td>
    </tr>
    <tr><td colspan="6"><h3><br>Sofort-Feuerschaden</h3></td></tr>
    <tr>
        <td>----------------</td>
        <td>----------------</td>
        <td>----------------</td>
        <td>----------------</td>
        <td>---------------></td>
        <td><span title="Explosion"><input type="button" style="background:url(Bilder/Elemente/Explosion.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_explosion" value=""></span></td>
    </tr>
    <tr><td colspan="6"><h3><br>Verbrennung</h3></td></tr>
    <tr>
        <td><span title="Schwellbrand"><input type="button" style="background:url(Bilder/Elemente/Schwelbrand.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_schwelbrand" value=""></span></td>
        <td><span title="Lava"><input type="button" style="background:url(Bilder/Elemente/Lava.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_lava" value=""></span></td>
        <td>----------------</td>
        <td>---------------></td>
        <td><span title="Magma"><input type="button" style="background:url(Bilder/Elemente/Magma.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_magma" value=""></span></td>
        <td></td>
    </tr>
    <tr>
        <td>----------------</td>
        <td>---------------></td>
        <td><span title="Feuerball><input type="button" style="background:url(Bilder/Elemente/Feuerball.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_feuerball" value=""></span></td>
        <td>---------------></td>
        <td><span title="Blitz"><input type="button" style="background:url(Bilder/Elemente/Blitz.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_blitz" value=""></span></td>
        <td><span title="Feuersturm"><input type="button" style="background:url(Bilder/Elemente/Feuersturm.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_feuersturm" value=""></span></td>
    </tr>
    <tr>
        <td>---------------></td>
        <td><span title="Glut"><input type="button" style="background:url(Bilder/Elemente/Glut.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_glut" value=""></span></td>
        <td>---------------></td>
        <td><span title="&Ouml;lbrand"><input type="button" style="background:url(Bilder/Elemente/Oelbrand.png);height:80px;width:80px;background-repeat:no-repeat;" name="button_oelbrand" value=""></span></td>
        <td></td>
        <td></td>
    </tr>
    
</table>
