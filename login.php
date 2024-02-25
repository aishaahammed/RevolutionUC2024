<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;

                            // Redirect user to welcome page
                            header("location: welcome.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid username or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Best Dressed</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background-image: url('waste_is_out_of_fashion_shutterstock.jpg');
    }

    .login-container {
      color: white;
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      background-color: rgba(0, 0, 0, 40%);
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
      text-align: center;
      margin-bottom: 20px; /* Added to create space between containers */
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 15px;
    }

    label {
      display: block;
      margin-bottom: 5px;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    button {
      width: 100%;
      padding: 10px;
      background-color: grey;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background-color: black;
    }

    .error-message {
      color: black;
      text-align: center;
      margin-top: 10px;
    }

    .toggle-form {
      text-align: center;
      margin-top: 20px;
    }

    .toggle-form a {
      color: black;
      cursor: pointer;
    }

    .social-buttons {
      display: flex;
      justify-content: center;
      margin-top: 20px;
    }
    .social-button {
      padding: 10px 20px;
      font-size: 16px;
      cursor: pointer;
      border-radius: 5px;
      border: none;
      margin: 0 5px;
    }
    .facebook-button {
      background-color: black;
      color: #fff;
    }
    .google-button {
      background-color: black;
      color: #fff;
    }
    .X-button {
      background-color: black;
      color: #fff;
    }

    /* Added styles */
    .guest-button-container {
      text-align: center;
      margin-top: 20px;
      position: absolute;
      bottom: 20px;
      width: 100%;
    }
  </style>
</head>
<body>
  <div class="login-container" id="login-container">
    <h2>Best Dressed</h2>
    <div id="signin-form">
      <?php
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <a href="tryClothesPage.php" type="submit" value="Login">Login</a>
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
      <p id="error-message" class="error-message"></p>
    </div>
    <div class="social-buttons">
      <button class="social-button facebook-button" onclick="loginWithFacebook()">Login with Facebook</button>
      <button class="social-button google-button" onclick="loginWithGoogle()">Login with Google</button>
      <button class="social-button X-button" onclick="loginWithX()">Login with X</button>
    </div>
    <div class="toggle-form">
      Don't have an account? <a href="#" onclick="toggleForm()">Sign Up</a>
    </div>
    <div class="toggle-form">
      <button onclick="continueAsGuest()">Continue as Guest</button>
    </div>
  </div>

  <div class="login-container" id="signup-container" style="display: none;">
    <h2>Create Account</h2>
    <form id="create-account-form">
      <div class="form-group">
        <label for="new-username">Create Username:</label>
        <input type="text" id="new-username" name="new-username" required>
      </div>
      <div class="form-group">
        <label for="new-password">Create Password:</label>
        <input type="password" id="new-password" name="new-password" required>
      </div>
      <button type="submit">Create Account</button>
    </form>
    <p id="create-account-message" class="error-message"></p>
    <div class="toggle-form">
      Already have an account? <a href="#" onclick="toggleForm()">Login</a>
    </div>
  </div>

  <script>

   function validation()  {
     var id=document.f1.user.value;
     var ps=document.f1.pass.value;
     if(id.length=="" && ps.length=="") {
        alert("User Name and Password fields are empty");
        return false;
     }  else  {
       if(id.length=="") {
          alert("User Name is empty");
          return false;
       }
       if (ps.length=="") {
            alert("Password field is empty");
            return false;
        }
    }
  }
    function toggleForm() {
      var loginContainer = document.getElementById('login-container');
      var signupContainer = document.getElementById('signup-container');

      if (loginContainer.style.display === 'none') {
        loginContainer.style.display = 'block';
        signupContainer.style.display = 'none';
      } else {
        loginContainer.style.display = 'none';
   </script>
</body>