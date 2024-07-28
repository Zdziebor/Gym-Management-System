<?php
//wylogowanie użytkownika
session_start();
session_destroy();
header("Location: ../index.php");
exit;