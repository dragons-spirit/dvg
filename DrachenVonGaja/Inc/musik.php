<!--DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"-->

<html>
	
	<head>
		<meta http-equiv="Content-Language" content="de">
		<meta http-equiv="Content-Script-Type" content="text/javascript">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<meta name="description" content="Drachen von Gaja - Administration">
		<meta name="Author" content="Tina Schmidtbauer, Hendrik Matthes" >
		<meta charset="utf-8">
		<!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->
	
		<link rel="stylesheet" type="text/css" href="../index_admin.css">
		<script src="../index.js" type="text/javascript"></script>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<title>Drachen von Gaja - Musik</title>
	</head>
	
	<body>

		<!--<audio id="audio" controls autoplay loop>
			<source src="../Musik/Geheimnisse-der-Geschichte.mp3" type="audio/mp3"/>
			<source src="../Musik/mystik.mp3" type="audio/mp3"/>
			Ihr Browser kann dieses Tondokument nicht wiedergeben.
		</audio>-->
		
		<audio id="audio" controls autoplay controlsList="nodownload"></audio>
		<script>
		    var idx = 0;
		    var tracks = ["../Musik/Geheimnisse-der-Geschichte.mp3", "../Musik/mystik.mp3", "../Musik/film.mp3"];
		    audio.addEventListener("ended", playnext);
		    function playnext() {
			idx = Math.floor(Math.random() * tracks.length);
			if (idx < tracks.length) {
			    audio.pause();
			    audio.src = tracks[idx];
			    audio.play();
			}
		    }
		    playnext();
		</script>
	
	</body>
</html>