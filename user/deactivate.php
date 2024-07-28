<?php
session_start();
require "../urls.php";
require "checkUserLogin.php";


// $user = null;


// if (isset($_SESSION["user_id"])) {
    
//     $mysqli = require __DIR__ . "/../config.php";
    
  
//     $sql = "SELECT * FROM user WHERE id = ?";
//     $stmt = $mysqli->prepare($sql);

//     if (!$stmt) {
//         die("Database error");
//     }

//     $stmt->bind_param("i", $_SESSION["user_id"]);
//     $stmt->execute();

//     $result = $stmt->get_result();

//     $user = $result->fetch_assoc();

//     $stmt->close();
// }

// if (isset($_SESSION['confirmation_phrase_entered']) && $_SESSION['confirmation_phrase_entered'] === true) {
//     // Redirect the user to process-deactivate.php if the confirmation phrase is entered
//    // header("Location: process-deactivate.php");
//     //exit();
// }

?>
<!DOCTYPE html>
<html>
<head>
    <title>Deaktywacja Konta</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../html-elements/header.css">
    <link rel="stylesheet" href="../html-elements/footer.css">
    <link rel="stylesheet" href="../html-elements/user/deactivate.css">
</head>
<body>
<?php require "../html-elements/header.php"?>

    
    
    <?php //if ($user): ?>
        
        <div class=  "content">
            <div class ="title">
            <h1>Dezaktywacja konta</h1>
            </div>
        <form method="post" action="process-deactivate.php">
            <div class = "phrase">
            
            <label for="confirmation_phrase">W celu dezaktywacji konta prosimy o wpisanie frazy 
                “<span style="color:red">Potwierdzam dezaktywację konta</span>” w polu tekstowym</label>
            <br>
            <br>
            <input type="text" id="confirmation_phrase" name="confirmation_phrase" required>
            </div>
            <div class="g-recaptcha" data-sitekey="6Le92K0pAAAAACLXDvUaCSXRpqd4nxN8M9PuXyG1"></div>
            <br>
            <button class="button disabled" id="confirm_button"><b>Dezaktywuj</b></button>

        </form>
        </div>
        
        <script>
        //skrypt odblokowujący przycisk dezaktywacji konta po wpisaniu frazy  "Potwierdzam dezaktywację konta"
         document.getElementById('confirmation_phrase').addEventListener('input', function() {
    var confirmationPhrase = this.value.trim();
    var confirmButton = document.getElementById('confirm_button');
    if (confirmationPhrase === 'Potwierdzam dezaktywację konta') {
        confirmButton.classList.remove('disabled');
    } else {
        confirmButton.classList.add('disabled');
    }
});

document.querySelector('form').addEventListener('submit', function() {
    <?php $_SESSION['confirmation_phrase_entered'] = true; ?>
});

        </script>

  
    <?php require "../html-elements/footer.php"?>

</body>
</html>