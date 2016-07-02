<html>

<?php
    include("db_funktionen.php");
?>

<form method="POST" action="<?php echo $_SERVER["PHP_SELF"];?>">
    Benutzername:<input type="text" name="ausername" size="15">
    Passwort:<input type="password" name="auserpswd" size="15">
    E-Mail: <input type="email" name="ausermail" size="30">
    <input type="submit" name="button_register" value="registrieren">   
</form>

<?php
    if(isset($_POST["button_register"]))
    {
        insert_registrierung($_POST['ausername'], $_POST['auserpswd'], $_POST['ausermail']);
    }
?>

</html>