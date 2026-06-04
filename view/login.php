<?php
include '../control/login_process.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - A$ADORÉ</title>
    <link rel="stylesheet" type="text/css" href="../css/task1_style.css">
</head>
<body>

<nav>
    <div class="nav-inner">
        <a class="nav-brand" href="Home.php">A$ADORÉ</a>
        <div class="nav-links">
            <a href="login.php">Login</a>
            <a href="Registration.php">Register</a>
        </div>
    </div>
</nav>

<div class="auth-wrapper">
    <div class="auth-box">
        <h2>Login to A$ADORÉ</h2>

        <?php if(isset($_GET["registered"])){ ?>
            <div class="msg-success">Registration successful. Please login.</div>
        <?php } ?>

        <?php if(isset($_GET["password_changed"])){ ?>
            <div class="msg-success">Password changed successfully. Please login with your new password.</div>
        <?php } ?>

        <?php if($errorMsg != ""){ ?>
            <div class="msg-error"><?php echo htmlspecialchars($errorMsg); ?></div>
        <?php } ?>

        <form id="loginForm" action="" method="post" autocomplete="on" onsubmit="return validateLogin()">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" autocomplete="username"
                       <?php if($email === ""){ echo 'readonly '; } ?>
                       value="<?php echo htmlspecialchars($email); ?>" placeholder="Enter your email">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" autocomplete="current-password"
                       <?php if($email === ""){ echo 'readonly '; } ?>
                       placeholder="Enter your password">
            </div>

            <div class="form-group">
                <label class="checkbox-group">
                    <input type="checkbox" name="remember" value="1"> Remember Me (7 days)
                </label>
            </div>

            <div class="form-group">
                <input type="submit" name="login" value="Login">
            </div>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="Registration.php">Register here</a>
        </div>
    </div>
</div>

<script src="../js/task1_script.js?v=3"></script>
<script>
(function(){
    var loginEmail = document.getElementById("email");
    var loginPassword = document.getElementById("password");
    if(!loginEmail || !loginPassword){
        return;
    }

    function unlockLoginFields(){
        loginEmail.removeAttribute("readonly");
        loginPassword.removeAttribute("readonly");
    }

    loginEmail.addEventListener("mousedown", unlockLoginFields);
    loginEmail.addEventListener("focus", unlockLoginFields);
})();

function validateLogin()
{
    var email    = document.getElementById("email").value.trim();
    var password = document.getElementById("password").value;

    if(email == ""){
        alert("Email is required");
        return false;
    }
    if(password == ""){
        alert("Password is required");
        return false;
    }
    return true;
}
</script>
</body>
</html>
