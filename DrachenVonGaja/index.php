<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
    
    <head>
    <meta http-equiv="Content-Language" content="de">
    <meta http-equiv="Content-Script-Type" content="text/javascript">
    <meta http-equiv="Content-Style-Type" content="text/css">
    <meta http-equiv="last-modified" content="11.05.2016 21:00:00" >
    <meta name="description" content="Lichtdrachen der Elemente - Browsergame">
    <meta name="keywords" content="Lichtdrachen, Elemente, Browsergame, Browserspiel,">
    <meta name="Author" content="Tina Schmidtbauer" >

    <link rel="stylesheet" type="text/css" href="index.css">
    <script src="index.js" type="text/javascript"></script>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <title>Lichtdrachen der Elemente</title>
    
    </head>
    
    <body>
        
        <div id="rahmen">
        
        <div id="header">
            <h3 align="center">Lichtdrachen der Elemente</h3>  
        </div>
         <?php
         
  include("Inc/testspieldaten.inc.php");
         
        /*
         
         include("Inc/connect.inc.php");
        
        SESSION_START();
        
        $result = mysql_query ($sql)OR die(mysql_error());; 
              
        */
        ?>
        <div id="anmeldung">
            
            <?php
            include("Inc/navi.inc.php");
            ?>
          
        </div>
        
         
        <?php
        /*mysql_close();*/
        ?>
        
           
          <div id="footer">
              <h4 align="center">Impressum</h4>
          </div>
        </div>
    </body>
    
</html>