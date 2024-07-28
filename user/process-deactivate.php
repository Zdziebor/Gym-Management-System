            <?php

            session_start();
            require "checkUserLogin.php";


            if (!isset($_SESSION["user_id"])) {
                echo "<h1>Brak loginu</h1>";
                echo "<p><a href='login.php'>Log in</a> or <a href='signup.html'>sign up</a></p>";
                exit;
            }
                //Jeśli fraza potwierdzająca dezaktywację konta została wprowadzona, kontynuuj
                if (isset($_SESSION['confirmation_phrase_entered']) && $_SESSION['confirmation_phrase_entered'] === true) {
                    //Jeśli użytkownik zalogowany, kontynuuj.
                    if (isset($_SESSION["user_id"])) {

                        $mysqli = require __DIR__ . "/../config.php";

                        $sql = "SELECT * FROM user WHERE id = ?";
                        $stmt = $mysqli->prepare($sql);
                        if (!$stmt) {
                            die("Database error");
                        }

                        $stmt->bind_param("i", $_SESSION["user_id"]);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $user = $result->fetch_assoc();

                        //Ustaw isBlocked w bazie na 1
                        if ($user) {
                            $mysqli = require __DIR__ . "/../config.php";
                            $sql = "UPDATE user SET isBlocked = 1 WHERE email = ?";
                            $stmt = $mysqli->prepare($sql);
                            if (!$stmt) {
                                die("Database error");
                            }

                            $stmt->bind_param("s", $user["email"]);

                            if ($stmt->execute()) {

                                if ($mysqli->affected_rows) {

                                    //Przygotuj mail informujący o dezaktywacji konta
                                    $mail = require __DIR__ . "/../mailer.php";

                                    $mail->setFrom("noreply@example.com");
                                    $mail->addAddress($user["email"]);
                                    $mail->Subject = "Dezaktywacja konta";
                                    $mail->Body = <<<END
            
                Konto zostało zdezaktywowane.
            
                END;


                                    try {
                                        //Wyślij mail
                                        $mail->send();
                                        header("Location: account-deactivated.php");
                                        session_destroy();
                                    } catch (Exception $e) {

                                        echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
                                    }
                                }
                            } else {
                                echo "Bład polecenia";
                            }
                        } else {
                            echo "user not found";
                        }
                    } else {
                        echo "User not logged in";
                    }
                } else {
                    echo "Please confirm account deactivation";
                }
            
            ?>