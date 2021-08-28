<style>

#left
{
    position:absolute;
    width:56px;
    height:100%;
    top:0px;
    left:0px;
    bottom:0px;
}

#right
{
    position:absolute;
    width:56px;
    height:100%;
    top:0px;
    right:0px;
    bottom:0px;
}

#questinhalt
{
    position:absolute;
    background-color:#F5D0A9;
    top:18px;
    left:43px;
    right:43px;
    bottom:11px;
	color:black;
}


#kap_o_l
{
    position:absolute;
    top:0px;
    left:0px;
	width:100%;
	height:5%;
    background-Image: url("../Bilder/kap_o.png");
    background-repeat:no-repeat;
    background-size:100%;
	background-position:top center;
}
#kap_o_r
{
    position:absolute;
    top:0px;
    right:0px;
    width:100%;
    height:5%;
    background-Image: url("../Bilder/kap_o.png");
    background-repeat:no-repeat;
    background-size:100%;
	background-position:top center;
}
#kap_u_l
{
    position:absolute;
    bottom:0px;
	width:100%;
    height:5%;
    background-Image: url("../Bilder/kap_u.png");
    background-repeat:no-repeat;
    background-size:80%;
	background-position:bottom center;
}
#kap_u_r
{
    position:absolute;
    bottom:0px;
    width:100%;
    height:5%;
    background-Image: url("../Bilder/kap_u.png");
    background-repeat:no-repeat;
    background-size:80%;
	background-position:bottom center;
}

#streifen_links
{
    position:absolute;
    background-image: url("../Bilder/streifen.png");
    background-repeat:repeat-y;
    background-size:56%;
    top:0px;
    width:100%;
    height:99%;
    background-position:bottom center;
}

#streifen_rechts
{
    position:absolute;
    background-image: url("../Bilder/streifen.png");
    background-repeat:repeat-y;
    background-size:56%;
    top:0px;
    width:100%;
    height:99%;
    background-position:bottom center;
}

</style>


<div id="left">
    <div id="streifen_links"></div>
    <div id="kap_o_l"></div>
    <div id="kap_u_l"></div>
    
</div>

<div id="right">
    <div id="streifen_rechts"></div>
    <div id="kap_o_r"></div>
     <div id="kap_u_r"></div>
</div>
     
        
<div id="questinhalt">
    
    <h1 align="center">Titel</h1>
    <p align="center">Textinhalt</p>
	<?php
	if($anzeige_gaja_3d){
		?>
		<div>
			<h1 style="margin-top:15px">3D-Ansicht vom Planeten Gaja</h1>
			<iframe src="planetgaja.html" width="100%" height="80%"></iframe>
			<!--
			<?php
				include("planetgaja.html");
			?>
			-->
		</div>
		<?php
	}
	?>
</div>




