

<?php
    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    session_start();
    if (isset($_SESSION['SESSION_EMAIL'])) {
        header("Location: index.php");
        die();
    }
    //Load Composer's autoloader
    require 'vendor/autoload.php';

    include 'dbcon.php';
    $msg = "";

    if (isset($_POST['reg_Btn'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, md5($_POST['password']));
        $confirm_password = mysqli_real_escape_string($conn, md5($_POST['confirm-password']));
        $verify_token = mysqli_real_escape_string($conn, md5(rand()));

        if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE email='{$email}'")) > 0) {
            $msg = "<div class='alert alert-danger'>{$email} - This email address has been already exists.</div>";
        } else {
            if ($password === $confirm_password) {
                $sql = "INSERT INTO users (name, email, password, verify_token) VALUES ('{$name}', '{$email}', '{$password}', '{$verify_token}')";
                $result = mysqli_query($conn, $sql);
                 if ($result) {
                    echo "<div style='display: none;'>";
                    //Create an instance; passing `true` enables exceptions
                    $mail = new PHPMailer(true);

                    try {
                        //Server settings
                        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                        $mail->isSMTP();                                            //Send using SMTP
                        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                        $mail->Username   = 'caxinc1@gmail.com';                     //SMTP username
                        $mail->Password   = 'hopkpmzlxqtkhrth';                               //SMTP password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                        //Recipients
                        $mail->setFrom('caxinc1@gmail.com');
                        $mail->addAddress($email);

                        //Content
                        $mail->isHTML(true);                                  //Set email format to HTML
                        $mail->Subject = 'no reply';
                        $mail->Body    = 'Here is the verification link <b><a href="http://localhost/login/?verification='.$verify_token.'">http://localhost/login/?verification='.$verify_token.'</a></b>';

                        $mail->send();
                        echo 'Message has been sent';
                    } catch (Exception $e) {
                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }
                    echo "</div>";
                    $msg = "<div class='alert alert-info'>We've send a verification link on your email address.</div>";
                } else {
                    $msg = "<div class='alert alert-danger'>Something wrong went.</div>";
                }
            } else {
                $msg = "<div class='alert alert-danger'>Password and Confirm Password do not match</div>";
            }
        }
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <link href="bootstrap-4.3.1-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="login.css">
</head>
<body id="bd">
    <section id="login">
    <form action='' method='POST'>
        <div class="log">
            <img src="./pay/knowFx.png" class="image" alt="">
        <h2 style="border:none;">Register</h2>
        <?php echo $msg ?>
            <div class="ep">
                <input class="form-control" type="text" name="name" id="" placeholder="Username">
                <input class="form-control" type="email" name="email" id="" placeholder="Email">
                <input  class="form-control" type="password" name="password" id="" placeholder="Password">
                <input class="form-control" type="password" name="confirm-password" id="" placeholder="Confirm Password">
            </div>
            <button type="submit" name = "reg_Btn" class="btn btn-primary">Register</button>
        </div>
        <span class="sp"> <h3 style="font-size:15px; padding-left:10px; text-align:center;">Already Have an Account? <a href="Login.php">Login</a></span>
</form>
    </section>



    <script src="bootstrap-4.3.1-dist/js/jQuery.min.js"></script>
    <script src="bootstrap-4.3.1-dist/js/popper.min.js"></script>
    <script src="bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function (c) {
            $('.alert-close').on('click', function (c) {
                $('.main-mockup').fadeOut('slow', function (c) {
                    $('.main-mockup').remove();
                });
            });
        });
    </script>
</body>
</html>