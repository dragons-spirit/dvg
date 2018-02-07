<style>

#left
{
    position:absolute;
    width:56px;
    height:100%;
    top:0px;
    left:0px;
    bottom:0px;
    /*padding:0.1em;*/
}

#right
{
    position:absolute;
    width:56px;
    height:100%;
    top:0px;
    right:0px;
    bottom:0px;

    /*padding:0.1em;*/
}

#questinhalt
{
    position:absolute;
    background-color:#F5D0A9;
    top:18px;
    left:41px;
    right:45px;
    bottom:11px;
}


#kap_o_l
{
    position:absolute;
    top:0px;
    left:0px;
    width:187px;
    height:54px;
    background-Image: url("../Bilder/kap_o.png");
    background-repeat:no-repeat;
    background-size:30%;   
}
#kap_o_r
{
    position:absolute;
    top:0px;
    left:0px;
    width:187px;
    height:54px;
    background-Image: url("../Bilder/kap_o.png");
    background-repeat:no-repeat;
    background-size:30%;
}
#kap_u_l
{
    position:absolute;
    bottom:0px;
    left:3px;
    width:150px;
    height:19px;
    background-Image: url("../Bilder/kap_u.png");
    background-repeat:no-repeat;
    background-size:30%;
}
#kap_u_r
{
    position:absolute;
    bottom:0px;
    left:3px;
    width:150px;
    height:19px;
    background-Image: url("../Bilder/kap_u.png");
    background-repeat:no-repeat;
    background-size:30%;
}

#streifen_links
{
    position:absolute;
    background-image: url("../Bilder/streifen.png");
    background-repeat:repeat-y;
    background-size:30%;
    top:0px;
    width:100px;
    height:99%;
    left:11px;
}

#streifen_rechts
{
    position:absolute;
    background-image: url("../Bilder/streifen.png");
    background-repeat:repeat-y;
    background-size:30%;
    top:0px;
    width:100px;
    height:99%;
    left:11px;
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
 
</div>

