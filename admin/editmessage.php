<?php
include "checkAdmin.php";


error_reporting(0);

//Pobranie komunikatu z bazy danych na podstawie id
$sql = "SELECT * FROM messages WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $_GET["id"]);
$stmt->execute();
$result = $stmt->get_result();
$messages = $result->fetch_assoc();



//Aktualizacja komunikatu
if (isset($_POST['submit'])) {


    $subject = $_POST['subject'];
    $message = $_POST['message'];


    if ($subject != '' && $message != '') {

        $sql = "UPDATE messages SET subject = ?, message = ?, isEdited = 1 WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssi", $subject, $message, $_GET["id"]);
        $stmt->execute();

        header("Location: readMessage.php");
        exit();
    }
}

//Ustawienie wiadomości na aktualną lub usuniętą w zależności od jej obecnego stanu
if (isset($_POST['isActual'])) {
    switch ($messages["isActual"]) {
        case 0:
            $sql = "UPDATE messages SET isActual = 1 WHERE id = ?";
            break;
        case 1:
            $sql = "UPDATE messages SET isActual = 0 WHERE id = ?";
    }
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $_GET["id"]);
    $stmt->execute();


    header("Location: readMessage.php");
    exit();
}


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Add and Save CKEditor 5 data to MySQL database with PHP</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

    <style type="text/css">
        .ck-editor__editable {
            min-height: 250px;
        }
    </style>


</head>

<body>
<a href='<?php echo ADMIN_READ_MESSAGE ?>'>Powrót do listy komunikatów</a>

<h1 style="text-align:center">Edytuj komunikat</h1>

    <div class="container">
        <div class="row">
            <div class="col-md-8 mt-5" style="margin: 0 auto;">

                <form method="post" action="">

                    <div class="form-group mb-4">

                        <label class="control-label col-sm-2" for="subject">Subject:</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="subject" placeholder="Enter Subject" name="subject" value="<?php echo $messages["subject"] ?>">
                        </div>

                    </div>

                    <div class="form-group mb-4">

                        <label class="control-label col-sm-2" for="message">Message:</label>
                        <div class="col-sm-10">
                            <textarea class="form-control editor" name="message" placeholder="testing"> <?php echo $messages["message"] ?></textarea>
                        </div>

                    </div>

                    <div class="form-group ">
                        <div class="col-sm-offset-2 col-sm-10">

                            <input type="submit" class="btn btn-info" name="submit" value="Zatwierdź">
                        </div>
                    </div>

                </form>

                <form method="post" action="">
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" class="<?php echo ($messages["isActual"] == 0) ? "btn btn-success" : "btn btn-danger" ?>" name="isActual" value="<?php echo ($messages["isActual"] == 0) ? "Przywróć" : "Usuń" ?>">
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