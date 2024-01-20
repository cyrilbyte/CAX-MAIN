

<?php
    session_start();
    if (isset($_SESSION['SESSION_EMAIL'])) {
        header("Location: index.php");
        die();
    }

    include 'dbcon.php';
    $msg = "";

    if (isset($_GET['verification'])) {
        if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE verify_token='{$_GET['verification']}'")) > 0) {
            $query = mysqli_query($conn, "UPDATE users SET verify_token='' WHERE verify_token='{$_GET['verification']}'");
            
            if ($query) {
                $msg = "<div class='alert alert-success'>Account verification has been successfully completed.</div>";
            }
        } else {
            header("Location: Login.php");
        }
    }

    if (isset($_POST['Lg_Btn'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, md5($_POST['password']));

        $sql = "SELECT * FROM users WHERE email='{$email}' AND password='{$password}'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);

            if (empty($row['verify_token'])) {
                $_SESSION['SESSION_EMAIL'] = $email;
                header("Location: Login.php");
            } else {
                $msg = "<div class='alert alert-info'>First verify your account and try again.</div>";
            }
        } else {
            $msg = "<div class='alert alert-danger'>Email or password do not match.</div>";
        }
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="bootstrap-4.3.1-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="login.css">
</head>
<body id="bd">
        <section id="login">
            <form action='' method='POST'>
                <div class="log">
                    <img src="./pay/knowFx.png" class="image" alt="">
                <h2 style="border:none; font-size:25px">Log In</h2>
                <div class="ep">
                    
                        <input class="form-control" type="email" name="email" id="" placeholder="Username"  required=""autofocus="">
                        <input type="password" class="form-control"  name="password" id="" placeholder="Password"  required=""autofocus=""> 
                        <div class="checkb">
                        <input type="checkbox"  class="cb" name="" id=""> <label for="checkbox">Remember Me</label>
                    </div>
                    <button type="submit" name = "Lg_Btn" class="btn btn-primary">Login</button>

                </div>
                
            </form>
                <span class="sp"> <h3 style="font-size:15px; padding-left:10px; text-align:center;">Forget Password? <a href="Register.php" sytle="font-size: 15px;">  Sign In</a></span>

        </section>
        <script src="js/jquery.min.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>

</body>
</html>