function sichtbar(wert)
		{
			switch(wert)
			{
				case 1:
                                document.getElementById('erdinhalt').style.block = "block";
                                document.getElementById('wasserinhalt').style.block = "none";
                                document.getElementById('feuerinhalt').style.block = "none";
                                document.getElementById('luftinhalt').style.block = "none";
				break;
				case 2:
                                document.getElementById('erdinhalt').style.block = "none";
                                document.getElementById('wasserinhalt').style.block = "block";
                                document.getElementById('feuerinhalt').style.block = "none";
                                document.getElementById('luftinhalt').style.block = "none";
                         
				break;
				case 3:
                                document.getElementById('erdinhalt').style.block = "none";
                                document.getElementById('wasserinhalt').style.block = "none";
                                document.getElementById('feuerinhalt').style.block = "block";
                                document.getElementById('luftinhalt').style.block = "none";
                                
				break;
				case 4:
                                document.getElementById('erdinhalt').style.block = "none";
                                document.getElementById('wasserinhalt').style.block = "none";
                                document.getElementById('feuerinhalt').style.block = "none";
                                document.getElementById('luftinhalt').style.block = "block";                                
				break;
				default:
				{
				
				}
				break;
			}
		}
		