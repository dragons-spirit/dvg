<?php
    include('funktionen.php');
?><
    
    !-- Registrierung -->
<?php
    if(isset($_POST['button_register']))
    {
?>
<form method="POST" action="<?php
            registrierung($ausername, $auserpswd, $ausermail);
            echo $_SERVER['SELF_PHP'];
            ?>">
    Benutzername:<input type="text" name="ausername" size="15">
    Passwort:<input type="password" name="auserpswd" size="15">
    E-Mail: <input type="email" name="ausermail" size="30">
    <input type="submit" name="button_register" value="registrieren">
    
</form>  

<?php
    }
?>