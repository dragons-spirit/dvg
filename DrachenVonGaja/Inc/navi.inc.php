<?php
	
	if (isset($_GET['id']))
	{
		switch($_GET['id'])
		{
			case 1: include("Inc/anmelden.inc.php");
			break;
			case 2: include("Inc/registrieren.inc.php");
			break;
			case 3: include("Inc/waehlen1.inc.php");
			break;
			case 4: include("Inc/waehlen2.inc.php");
			break;
			case 5: include("Inc/waehlen3.inc.php");
			break;
			case 6: include("Inc/gamestart1.inc.php");
			break;
			default: include("Inc/anmelden.inc.php");
			break;
		}
	}
	else 
	{
	include("Inc/anmelden.inc.php");
	}
?>