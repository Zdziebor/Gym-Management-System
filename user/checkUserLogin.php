<?php 


//sprawdzenie czy użytkownik jest zalogowany, jeśli nie - przekieruj do ekranu logowania
if (!isset($_SESSION["user_id"])){
    header("Location: http://localhost/pracainz/login/login.php");
}

?>