<?php
session_start();
include "model.php";
$model = new model();

if (isset($_POST['signup'])) {
    $name = $_POST['name']; //here getting result from the post array after submitting the form.
    $pass = $_POST['pass']; //same
    $email = $_POST['email']; //same
    

    if ($name == '' || $pass == "" || $email == "" ) {
        echo "<span id=error>Please fill all fields</span>";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<span id=error>Email non valid</span>";
    } else if ($model->alreadyexistuser($email) > 0) {
        echo "<span id=error>Email user already exists </span>";
    } else {
        $model->add_user(array($name, $email, $pass));
        echo "<span id=error>You were added now! </span>";

    }

}
if (isset($_POST['login'])) {
    $pass = $_POST['pass']; //same
    $email = $_POST['email']; //same
    if ($pass == "" || $email == "") {
        echo "<span id=error>Please fill all fields</span>";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<span id=error>Email non valid</span>";
    } else if ($model->check_user($email, $pass) == 1) {
        //session start for user
        $user = $model->get_user_login($email, $pass);
        $_SESSION["user_id"] = $user['user_id'];
        $_SESSION["name"] = $user['name'];
        header('location:controller2.php');
    }
     elseif($model->check_admin($email, $pass) == 1)
  {
    $admin = $model->get_admin($email, $pass);
    $_SESSION['admin_id'] = $admin['admin_id'];
    $_SESSION['name'] = $admin['name'];
     header('location:controller.php');
  }
    else {
        echo "<span id=error>Email or password non correct</span>";}

}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="login_signup.css">
    <title>Login</title>
</head>

<body>


    <div class="section">
        <div class="container">
            <div class="row full-height justify-content-center">
                <div class="col-12 text-center align-self-center py-5">
                    <div class="section pb-5 pt-5 pt-sm-2 text-center">
                        <h6 class="mb-0 pb-3"><span>Log In </span><span>Sign Up</span></h6>
                        <input class="checkbox" type="checkbox" id="reg-log" name="reg-log" />
                        <label for="reg-log"></label>
                        <div class="card-3d-wrap mx-auto">
                            <div class="card-3d-wrapper">
                                <div class="card-front">
                                    <div class="center-wrap">
                                        <div class="section text-center">
                                            <h4 class="mb-4 pb-3">Log In</h4>
                                            <form method='post' action="<?php echo $_SERVER['PHP_SELF']; ?>" novalidate>
                                                <div class="form-group">
                                                    <input type="email" name="email" class="form-style"
                                                        placeholder="Your Email" id="logemail" autocomplete="off">
                                                    <i class="input-icon uil uil-at"></i>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <input type="password" name="pass" class="form-style"
                                                        placeholder="Your Password" id="logpass" autocomplete="off">
                                                    <i class="input-icon uil uil-lock-alt"></i>
                                                </div>
                                                <button type="submit" class="btn mt-4" value="login"
                                                    name="login">Login</button>
                                            </form>
                                            <!-- <p class="mb-0 mt-4 text-center"><a href="#0" class="link">Forgot your
                                                    password?</a></p> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="card-back">
                                    <div class="center-wrap">
                                        <div class="section text-center">
                                            <h4 class="mb-4 pb-3">Sign Up</h4>
                                            <form method='post' action="<?php echo $_SERVER['PHP_SELF']; ?>" novalidate>

                                                <div class="form-group mt-2">
                                                    <input type="text" name="name" class="form-style"
                                                        placeholder="Your Full Name" id="logname" autocomplete="off">
                                                    <i class="input-icon uil uil-user"></i>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <input type="email" name="email" class="form-style"
                                                        placeholder="Your Email" id="logemail" autocomplete="off">
                                                    <i class="input-icon uil uil-at"></i>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <input type="password" name="pass" class="form-style"
                                                        placeholder="Your Password" id="logpass" autocomplete="off">
                                                    <i class="input-icon uil uil-lock-alt"></i>
                                                </div>

                                                <!-- <a href="#" class="btn mt-4">submit</a> -->
                                                <button type="submit" class="btn mt-4" value="signup" name="signup">Sign
                                                    Up</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>