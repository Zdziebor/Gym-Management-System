<?php

include "config.php";
include "checkAdmin.php";
error_reporting(0);


// Dodanie wiadomości
if (isset($_POST['submit'])) {
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    if ($subject != '' && $message != '') {
        //Jeśli wiadomość i treść nie są puste - przygotuj zapytanie
        $stmt = $mysqli->prepare("INSERT INTO messages (subject, message) VALUES (?, ?)");

        //Przypisanie parametrow
        $stmt->bind_param("ss", $subject, $message);

        //Wykonanie query
        $stmt->execute();

        //Zamknięcie query
        $stmt->close();

        //Przekierowanie do readMessage.php
        header('Location: readMessage.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dodaj komunikat</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

    <style type="text/css">
        .ck-editor__editable {
            min-height: 250px;
        }
    </style>


</head>

<body>
<a href='<?php echo ADMIN_READ_MESSAGE ?>'>Powrót do listy komunikatów</a>
        <h1 style="text-align:center">Dodaj komunikat</h1>
    <div class="container">
        <div class="row">
            <div class="col-md-8 mt-5" style="margin: 0 auto;">

                <form method="post" action="">

                    <div class="form-group mb-4">

                        <label class="control-label col-sm-2" for="subject">Subject:</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="subject" placeholder="Enter Subject" name="subject" value="">
                        </div>

                    </div>

                    <div class="form-group mb-4">

                        <label class="control-label col-sm-2" for="message">Message:</label>
                        <div class="col-sm-10">
                            <textarea class="form-control editor" name="message" placeholder="Treść komunikatu.."> </textarea>
                        </div>

                    </div>

                    <div class="form-group ">
                        <div class="col-sm-offset-2 col-sm-10">

                            <input type="submit" class="btn btn-info" name="submit" value="Zatwierdź">
                        </div>
                    </div>

                </form>

            </div>

        </div>

    </div>

    <!-- Script -->
    <script src="https://cdn.ckeditor.com/ckeditor5/35.4.0/classic/ckeditor.js"></script>
    <script type="text/javascript">
        ClassicEditor
            .create(document.querySelector('.editor'), {
                ckfinder: {
                    uploadUrl: "ckfileupload.php",
                }
            })
            .then(editor => {

                console.log(editor);

            })
            .catch(error => {
                console.error(error);
            });
    </script>
</body>

</html>