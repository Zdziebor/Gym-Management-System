<?php require "../urls.php";
session_start();

?>

<!DOCTYPE html>
<html>

<head>
    <title>Kontakt</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Oficjalna strona klubu Just Fit. Zapraszamy!">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="../html-elements/header.css">
    <link rel="stylesheet" href="../html-elements/footer.css">
    <link rel="stylesheet" href="../html-elements/contact/form.css">
   
</head>

<body>
    <?php require "../html-elements/header.php"?>
    <div class="image-1">
        <img src="../assets/contact/form.png" alt = "customer-support-image">
    </div>
    <div class="content">
        <h1 style="font-size:50px">Napisz do nas</h1>
        <form method="post" name="contact-form" action="<?php echo CONTACT_SEND_EMAIL ?>">
            <div class="name">
            
                <label for="name">Name <span style="color:red">*</span></label><br>
                <input required type="text" name="name" id="name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : '' ?>">
                
            </div>
            <div class="email">
   
                <label for="email">E-mail <span style="color:red">*</span></label><br>
                <input  required type="email" name="email" id="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>">
             
            </div>
            <div class="subject">
          
                <label for="subject">Temat <span style="color:red">*</span></label><br>
                <input required type="text" name="subject" id="subject" value="<?php echo isset($_POST['subject']) ? $_POST['subject'] : '' ?>">
               
            </div>
            <div class="message">
         
                <label for="message">Wiadomość <span style="color:red">*</span></label><br>
                <textarea name="message" id="message" required><?php echo isset($_POST['message']) ? $_POST['message'] : '' ?></textarea>
               
            </div>
            <br>
            <div class="text-xs-center">
                <div class="g-recaptcha" data-sitekey="6Le92K0pAAAAACLXDvUaCSXRpqd4nxN8M9PuXyG1"></div>
            </div>
            <button class="button">Wyślij</button>
        </form>
    </div>
    <?php require "../html-elements/footer.php"?>
</body>
</html>
