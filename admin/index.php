<?php
include "checkAdmin.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin</title>
  <link rel="stylesheet" href="https://unpkg.com/almond.css@latest/dist/almond.lite.min.css" />
 
  <style>
    .button-container {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      margin-top: 50px;
    }

    .button-container button {
      width: 500px;
      height: 100px;
      margin-bottom: 10px;
    }
  </style>

</head>

<body>

<a href='<?php echo LOGIN_LOGOUT ?>'">Wyloguj się</a>

  <h1 style="text-align: center;">Wybierz akcję</h1>

  <div class="button-container">

    <!-- Przycisk do wyszukiwania użytkowników -->
    <button onclick="window.location.href='<?php echo ADMIN_SEARCH ?>'">Szukaj</button>

    <!-- Przycisk do wyświetlenia komunikatów -->
    <button onclick="window.location.href='<?php echo ADMIN_READ_MESSAGE ?>'">Komunikaty</button>

    <!-- Przycisk do wyświetlenia oferty -->
    <button onclick="window.location.href='<?php echo ADMIN_EDIT_OFFER ?>'">Oferta</button>
  </div>


</body>

</html>