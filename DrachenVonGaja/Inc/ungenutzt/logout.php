<?php
	include("db_funktionen.php");
?>
<form method="POST" action="../index.php">
<?php
	session_start();
	session_destroy();
	echo "Logout erfolgreich<br /><br />";
?>
	<input type="submit" name="button_to_index" value="Back to the roots ...">
</form>
